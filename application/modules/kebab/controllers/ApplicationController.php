<?php

/**
 * Kebab Framework
 *
 * LICENSE
 *
 * This source file is subject to the  Dual Licensing Model that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.kebab-project.com/cms/licensing
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@lab2023.com so we can send you a copy immediately.
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Kebab
 * @subpackage  Controllers
 * @author      Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 * @version     1.5.0
 */

/**
 * Application Manager
 *
 * This service is list all application and set them active and passive.
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Kebab
 * @subpackage  Controllers
 * @author      Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 * @version     1.5.0
 */
class Kebab_ApplicationController extends Kebab_Rest_Controller
{
    public function indexAction()
    {
        // Mapping
        $mapping = array(
            'id' => 'application.id',
            'active' => 'application.active',
            'description' => 'applicationTranslation.description',
            'title' => 'title',
        );

        //KBBTODO move dql to models
        $ids = $this->_helper->search('Model_Entity_Application', true);
        $query = Doctrine_Query::create()
                ->select('application.id,
                    applicationTranslation.title as title,
                    applicationTranslation.description as description,
                    application.active')
                ->from('Model_Entity_Application application')
                ->leftJoin('application.Translation applicationTranslation')
                ->where('applicationTranslation.lang = ?', Zend_Auth::getInstance()->getIdentity()->language)
                ->whereIn('application.id', $ids)
                ->orderBy($this->_helper->sort($mapping));

        $pager = $this->_helper->pagination($query);
        $story = $pager->execute();

        // Response
        $responseData = is_object($story) ? $story->toArray() : array();
        $this->_helper->response(true)->addData($responseData)->addTotal($pager->getNumResults())->getResponse();
    }

    public function putAction()
    {
        // Getting parameters
        $params = $this->_helper->param();

        // Convert data collection array if not
        $collection = $this->_helper->array()->isCollection($params['data'])
                    ? $params['data']
                    : $this->_helper->array()->convertRecordtoCollection($params['data']);

        // Updating status
        Doctrine_Manager::connection()->beginTransaction();
        try {
             // Doctrine
            foreach ($collection as $record) {
                $story = new Model_Entity_Application();
                $story->assignIdentifier($record['id']);
                $story->set('active', $record['active']);
                $story->save();
            }
            Doctrine_Manager::connection()->commit();
            unset($story);
            
            // Response
            $this->_helper->response(true, 202)->getResponse();
        } catch (Zend_Exception $e) {
            Doctrine_Manager::connection()->rollback();
            throw $e;
        } catch (Doctrine_Exception $e) {
            Doctrine_Manager::connection()->rollback();
            throw $e;
        }
    }
}