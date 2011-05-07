<?php

if (!defined('BASE_PATH'))
    exit('No direct script access allowed');
/**
 * Kebab Framework
 *
 * LICENSE
 *
 * This source file is subject to the  Dual Licensing Model that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.kebab-project.com/licensing
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@lab2023.com so we can send you a copy immediately.
 *
 * @category   Kebab (kebab-reloaded)
 * @package
 * @subpackage Controllers
 * @author       lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */

/**
 * Preferences_AboutMeController
 *
 * @category   Kebab (kebab-reloaded)
 * @package
 * @subpackage Controllers
 * @author       lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */
class Access_StoryManagerController extends Kebab_Rest_Controller
{
    public function indexAction()
    {
        // Mapping
        $mapping = array(
            'id' => 'story.id',
            'status' => 'story.status',
            'active' => 'story.active',
            'description' => 'storyTranslation.description',
            'title' => 'storyTranslation.title',
        );

        Doctrine_Manager::connection()->beginTransaction();
        try {
            $query = Doctrine_Query::create()
                    ->select('story.id, storyTranslation.title as title,
                        storyTranslation.description as description, story.status, story.active')
                    ->from('Model_Entity_Story story')
                    ->leftJoin('story.Translation storyTranslation')
                    ->where('storyTranslation.lang = ?', Zend_Auth::getInstance()->getIdentity()->language)
                    ->orderBy($this->_helper->sort($mapping));

            $pager = $this->_helper->pagination($query);
            $story = $pager->execute();

            $responseData = array();
            if (is_object($story)) {
                $responseData = $story->toArray();
            }
            Doctrine_Manager::connection()->commit();
            $this->getResponse()
                    ->setHttpResponseCode(200)
                    ->appendBody(
                $this->_helper->response()
                        ->setSuccess(true)
                        ->addData($responseData)
                        ->addTotal($pager->getNumResults())
                        ->getResponse()
            );

        } catch (Zend_Exception $e) {
            throw $e;
        } catch (Doctrine_Exception $e) {
            Doctrine_Manager::connection()->rollback();
            throw $e;
        }
    }

    public function putAction()
    {
        // Getting parameters
        $params = $this->_helper->param();
        $id = $params['data']['id'];
        $active = $params['data']['active'];

        // Updating status
        Doctrine_Manager::connection()->beginTransaction();
        try {
            $story = new Access_Model_Story();
            $story->assignIdentifier($id);
            $story->set('active', $active);
            $story->save();
            Doctrine_Manager::connection()->commit();
            unset($story);
            // Returning response
            $this->getResponse()
                    ->setHttpResponseCode(202)
                    ->appendBody(
                $this->_helper->response()
                        ->setSuccess(true)
                        ->getResponse()
            );
        } catch (Zend_Exception $e) {
            throw $e;
        } catch (Doctrine_Exception $e) {
            Doctrine_Manager::connection()->rollback();
            throw $e;
        }
    }
}