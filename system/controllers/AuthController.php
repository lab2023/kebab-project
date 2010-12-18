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
 * @package    Core
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */
class AuthController extends Kebab_Controller_Action
{

    public function loginAction()
    {
        //KBBTODO don't forget Zend_Filter for SQL_INJECTION
        $username = $this->_request->getParam('username');
        $password = $this->_request->getParam('password');

        // Check isPost
        //KBBTODO Check if username and password is null
        if ($this->_request->isPost()
            && $username !== ''
            && $password !== '') {

            // set ZendX_Doctrine_Auth_Adapter
            $auth = Zend_Auth::getInstance();
            $authAdapter = new ZendX_Doctrine_Auth_Adapter(
                    Doctrine::getConnectionByTableName('System_Model_User')
            );

            $authAdapter->setTableName('System_Model_User u')
                ->setIdentityColumn('username')
                ->setCredentialColumn('password')
                ->setCredentialTreatment('MD5(?)')
                ->setIdentity($username)
                ->setCredential($password);
            
            // set Zend_Auth
            $result = $auth->authenticate($authAdapter);

            // Check Auth Validation
            if ($result->isValid()) {
                $identity = $authAdapter->getResultRowObject(null, 'password');
                //KBBTODO Set role and ACL object
                $identity->role = 'member';
                $auth->getStorage()->write($identity);
                //KBBTODO Set a message not valid user
                $this->_redirect('main');
            } else {
                //KBBTODO Set a message not valid user
            }
        }
    }

    public function logoutAction()
    {
        $authAdapter = Zend_Auth::getInstance();
        $authAdapter->clearIdentity();
        $this->_redirect('');
    }

}
