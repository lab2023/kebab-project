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
 * @category   KEBAB
 * @package    Controller
 * @subpackage Error 
 * @author     Tayfun Öziş ERİKAN <tayfun.ozis.erikan@lab2023.com>
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */

/**
 * Kebab Application Error Controller
 * 
 * <p>If the request is ajax, response's type is json or html/text </p>
 *
 * @category   Kebab (kebab-reloaded)
 * @package    Controller
 * @subpackage Error
 * @author     Tayfun Öziş ERİKAN <tayfun.ozis.erikan@lab2023.com>
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */
class ErrorController extends Kebab_Controller_Action
{
    /**
     * init
     */
    public function init()
    {
        $this->_helper->layout->disableLayout();
        
        // If request is ajax or not
        if ($this->_request->isXmlHttpRequest()) {
           $this->xmlHttpRequestErrorAction();
        } else {
           $this->errorAction();
        }        
    }
    
    /**
     *
     * @return type 
     */
    public function errorAction()
    {
        $errors = $this->_getParam('error_handler');

        if (!$errors) {
            $this->view->message = 'You have reached the error page';
            return;
        }

        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:

                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $this->view->message = 'Page not found';
                break;
            default:
                // application error
                $this->getResponse()->setHttpResponseCode(500);
                $this->view->message = 'Application error';
                break;
        }

        // Log exception, if logger available
        if ($log = $this->getLog()) {
            $log->crit($this->view->message, $errors->exception);
        }

        // conditionally display exceptions
        if ($this->getInvokeArg('displayExceptions') == true) {
            $this->view->exception = $errors->exception;
        }

        $this->view->request = $errors->request;

        Zend_Registry::get('logging')->log(
            $errors, Zend_Log::ERR
        );
    }
    
    /**
     *
     * @return json
     */
    public function xmlHttpRequestErrorAction()
    {
        if ($this->_request->isXmlHttpRequest()) {
            
            $responseData = array();
            $errors = $this->_getParam('error_handler');
            
            if (!$errors) {
                $responseData['message'] = 'You have reached the error page';
                return;
            }
            
            switch ($errors->type) {
                case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
                case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
                case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:

                    // 404 error -- controller or action not found
                    $this->getResponse()->setHttpResponseCode(404);
                    $responseData['message'] = 'Page not found';
                    break;
                default:
                    // application error
                    $this->getResponse()->setHttpResponseCode(500);
                    $responseData['message'] = 'Application error';
                    break;
            }
            
            // Log exception, if logger available
            if ($log = $this->getLog()) {
                $log->crit($this->view->message, $errors->exception);
            }
            
            // conditionally display exceptions
            if ($this->getInvokeArg('displayExceptions') == true) {
                $responseData['exception']['message']        = $errors->exception->getMessage();
                $responseData['exception']['traceAsSttring'] = $errors->exception->getTraceAsString();
                $responseData['request']['params']         = $errors->request->getParams();
            }
            
            $this->_helper->response()
                 ->addData($responseData)
                 ->getResponse();
        } else {
            throw new Zend_Exception('Request isn\'t ajax');
        }
    }
    
    /**
     *
     * @return Zend_Log
     */
    public function getLog()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
        if (!$bootstrap->hasResource('Log')) {
            return false;
        }
        $log = $bootstrap->getResource('Log');
        return $log;
    }

    /**
     * unauthorizedAction
     *
     * @return void
     */
    public function unauthorizedAction()
    {
        // If request is ajax or not
        if ($this->_request->isXmlHttpRequest()) {

           $responseData = array(
               'status' => 'unauthorized',
               'title' => 'Anauthorized Access',
               'message' => 'You are not authorized to access this area.'
           );

           $this->_helper->response()
                 ->addData($responseData)
                 ->getResponse();
           return;
        }
    }

}
