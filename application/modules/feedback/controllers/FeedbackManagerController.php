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
 * Feedback_FeedbackManager
 *
 * @category   Kebab (kebab-reloaded)
 * @package    Administration
 * @subpackage Controllers
 * @author     lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */
class Feedback_FeedbackManagerController extends Kebab_Rest_Controller
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

        $query = Doctrine_Query::create()
                ->select('feedback.*,
                    application.*,
                    user.firstName,
                    user.lastName,
                    applicationTranslate.title as title')
                ->from('Model_Entity_Feedback feedback')
                ->leftJoin('feedback.Application application')
                ->leftJoin('application.Translation applicationTranslate')
                ->leftJoin('feedback.User user')
                ->where('applicationTranslate.lang = ?', Zend_Auth::getInstance()->getIdentity()->language)
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
    
    /**
     * @return void
     */
    public function putAction()
    {
        // Getting parameters
        $params = $this->_helper->param();
        $id = $params['id'];
        $status = $params['status'];

        // Updating status
        try {
            $feedback = new Feedback_Model_Feedback();
            $feedback->assignIdentifier($id);
            $feedback->set('status', $status);
            $feedback->save();
            
            // Returning response
            $this->getResponse()
                        ->setHttpResponseCode(202)
                        ->appendBody(
                    $this->_helper->response()
                            ->setSuccess(true)
                            ->getResponse()
                );
        } catch (Zend_Exception $e) {
            die('ZE');
        } catch (Doctrine_Exception $e) {
            throw $e;
        }

    }
}