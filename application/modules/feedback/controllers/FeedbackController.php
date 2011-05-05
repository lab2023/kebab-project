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


        $userSessionId = Zend_Auth::getInstance()->getIdentity()->id;

        // Mapping
        $mapping = array(
            'id' => 'feedback.id',
            'title' => 'applicationTranslate.title',
            'status' => 'feedback.status',
            'description' => 'feedback.description'
        );

        // Doctrine
        Doctrine_Manager::connection()->beginTransaction();
        try {
            $query = Doctrine_Query::create()
                    ->select('feedback.*,
                    application.*,
                    applicationTranslate.title as title')
                    ->from('Model_Entity_Feedback feedback')
                    ->innerJoin('feedback.Application application')
                    ->leftJoin('application.Translation applicationTranslate')
                    ->where('feedback.user_id = ?', $userSessionId)
                    ->andWhere('applicationTranslate.lang = ?', Zend_Auth::getInstance()->getIdentity()->language)
                    ->orderBy($this->_helper->sort($mapping));

            $pager = $this->_helper->pagination($query);
            $feedbacks = $pager->execute();

            $responseData = array();

            if (is_object($feedbacks)) {
                $responseData = $feedbacks->toArray();
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

    public function postAction()
    {
        $params = $this->_helper->param();
        $applicationIdentity = $params['applicationIdentity'];
        $description = $params['description'];

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

            $this->getResponse()
                    ->setHttpResponseCode(200)
                    ->appendBody(
                $this->_helper->response()
                        ->setSuccess(true)
                        ->addData($feedback->toArray())
                        ->getResponse()
            );
            unset($feedback);

        } catch (Zend_Exception $e) {
            throw $e;
        } catch (Doctrine_Exception $e) {
            Doctrine_Manager::connection()->rollback();
            throw $e;
        }
    }
}