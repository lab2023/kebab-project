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
 * @category   Kebab (kebab-reloaded)
 * @package    System
 * @subpackage Controllers
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */


/**
 * Feedback
 *
 * Member can open and list their own feedback
 *
 * @category   Kebab (kebab-reloaded)
 * @package    System
 * @subpackage Controllers
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */
class Kebab_FeedbackController extends Kebab_Rest_Controller
{
    public function indexAction()
    {
        // Get User Id
        $userSessionId = Zend_Auth::getInstance()->getIdentity()->id;

        // Mapping
        $mapping = array(
            'id' => 'feedback.id',
            'title' => 'applicationTranslate.title',
            'status' => 'feedback.status',
            'description' => 'feedback.description'
        );

        //KBBTODO move DQL to models
        $query = Doctrine_Query::create()
                ->select('feedback.*,
                application.*,
                applicationTranslate.title as title')
                ->from('Model_Entity_Feedback feedback')
                ->innerJoin('feedback.Application application')
                ->leftJoin('application.Translation applicationTranslate')
                ->where('feedback.user_id = ?', $userSessionId)
                ->andWhere('applicationTranslate.lang = ?', Zend_Auth::getInstance()->getIdentity()->language)
                ->orderBy($this->_helper->sort($mapping))
                ->useQueryCache(Kebab_Cache_Query::isEnable());

        $pager = $this->_helper->pagination($query);
        $feedbacks = $pager->execute();

        $responseData = array();

        if (is_object($feedbacks)) {
            $responseData = $feedbacks->toArray();
        }
        $this->_helper->response(true, 200)->addData($responseData)->addTotal($pager->getNumResults())->getResponse();
    }

    public function postAction()
    {
        // Get Params
        $params = $this->_helper->param();
        $applicationIdentity = $params['applicationIdentity'];
        $description = $params['description'];

        //KBBTODO move dql to model class
        Doctrine_Manager::connection()->beginTransaction();
        try {
            $userSessionId = Zend_Auth::getInstance()->getIdentity()->id;

            $application = Doctrine_Core::getTable('Model_Entity_Application')->findOneByidentity($applicationIdentity);

            $feedback = new Model_Entity_Feedback();
            $feedback->Application = $application;
            $feedback->status = 'open';
            $feedback->description = $description;
            $feedback->user_id = $userSessionId;
            $feedback->save();
            Doctrine_Manager::connection()->commit();

            $this->_helper->response(true, 200)->addData($feedback->toArray())->getResponse();
            unset($feedback);

        } catch (Zend_Exception $e) {
            Doctrine_Manager::connection()->rollback();
            throw $e;
        } catch (Doctrine_Exception $e) {
            Doctrine_Manager::connection()->rollback();
            throw $e;
        }
    }
}