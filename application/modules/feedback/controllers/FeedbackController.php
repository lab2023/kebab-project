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
 * @package    System
 * @subpackage Controllers
 * @author     lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */


/**
 * Feedback_Feedback
 *
 * @category   Kebab (kebab-reloaded)
 * @package    Administration
 * @subpackage Controllers
 * @author     lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */
class Feedback_FeedbackController extends Kebab_Rest_Controller
{
    /**
     * @return void
     */
    public function indexAction()
    {
        // Mapping
        $mapping = array(
            'id' => 'feedback.id',
            'status' => 'feedback.status',
            'description' => 'feedback.description'
        );

        $userSessionId = Zend_Auth::getInstance()->getIdentity()->id;

        $query = Doctrine_Query::create()
                ->select('feedback.*, application.*')
                ->from('Model_Entity_Feedback feedback')
                ->leftJoin('feedback.Application application')
                ->where('feedback.user_id = ?', $userSessionId)
                ->orderBy($this->_helper->sort($mapping));

        $pager = $this->_helper->pagination($query);
        $feedbacks = $pager->execute();

        $responseData = array();

        if (is_object($feedbacks)) {
            $responseData = $feedbacks->toArray();
        }

        $this->getResponse()
                ->setHttpResponseCode(200)
                ->appendBody(
            $this->_helper->response()
                    ->setSuccess(true)
                    ->addData($responseData)
                    ->addTotal($pager->getNumResults())
                    ->getResponse()
        );
    }

    public function postAction()
    {
        $params = $this->_helper->param();
        $userId = $params['userId'];
        $applicationIdentity = $params['applicationIdentity'];
        $description = $params['description'];

        $userSessionId = Zend_Auth::getInstance()->getIdentity()->id;
        if (!$userId == $userSessionId) {
            throw new Zend_Exception('User is not valid.');
        }

        $applicationId = Doctrine_Core::getTable('Model_Entity_Application')->findOneByidentity($applicationIdentity)->id;

        $feedback = new Model_Entity_Feedback();
        $feedback->application_id = $applicationId;
        $feedback->description = $description;
        $feedback->user_id = $userId;

        $feedback->save();

        $this->getResponse()
                ->setHttpResponseCode(200)
                ->appendBody(
            $this->_helper->response()
                    ->setSuccess(true)
                    ->addData($feedback->toArray())
                    ->getResponse()
        );
    }
}