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
 * @author	   lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */

/**
 * Kebab Application Auth Controller
 *
 * @category   Kebab (kebab-reloaded)
 * @package    System
 * @subpackage Controllers
 * @author	   lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */
class AuthController extends Kebab_Controller_Action
{

    /**
     * Check user name and password and authorize user
     * 
     * @return void
     */
    public function indexAction()
    {

    }

    /**
     * Check user name and password and authorize user
     * 
     * @return void
     */
    public function loginAction()
    {
        // getParams
        $userName = $this->_request->getParam('username');
        $password = $this->_request->getParam('password');
        $rememberMe = $this->_request->getParam('rememberMe');

        //Filter for SQL Injection
        $validatorUserName = new Zend_Validate();
        $validatorUserName->addValidator(new Zend_Validate_StringLength(4, 16))
            ->addValidator(new Zend_Validate_Alnum());

        $validatorPassword = new Zend_Validate();
        $validatorPassword->addValidator(new Zend_Validate_NotEmpty());

        if ($this->_request->isPost()
            && $validatorPassword->isValid($password)
            && $validatorUserName->isValid($userName)) {

            // set ZendX_Doctrine_Auth_Adapter
            $auth = Zend_Auth::getInstance();
            $authAdapter = new ZendX_Doctrine_Auth_Adapter(
                    Doctrine::getConnectionByTableName('System_Model_User')
            );

            $authAdapter->setTableName('System_Model_User u')
                ->setIdentityColumn('username')
                ->setCredentialColumn('password')
                ->setCredentialTreatment('MD5(?)')
                ->setIdentity($userName)
                ->setCredential($password);

            // set Zend_Auth
            $result = $auth->authenticate($authAdapter);

            // Check Auth Validation
            if ($result->isValid()) {
                $identity = $authAdapter->getResultRowObject(null, 'password');
                //KBBTODO Set role and ACL object
                $query = Doctrine_Query::create()
                        ->select('r.roleName')
                        ->from('System_Model_Role r')
                        ->leftJoin('r.Users u')
                        ->where('u.userName = ?', $identity->userName);
                $roles = $query->execute();

                foreach ($roles as $role) {
                    $userRoles[] = $role->roleName;
                }

                $identity->roles = $userRoles;
                $identity->acl = new Kebab_Acl();
                $auth->getStorage()->write($identity);
                //KBBTODO Set session time and check from getParams
                Zend_Session::rememberMe();

                //KBBTODO Set a message not valid user
                //die(Zend_Debug::dump($identity->acl));
                $this->_redirect('main');
            }
        }

        $this->_redirect('auth/index');
    }

    /**
     * Logout, clear authorized user identity and redirect login page
     * 
     * @return void
     */
    public function logoutAction()
    {
        $authAdapter = Zend_Auth::getInstance();
        $authAdapter->clearIdentity();
        Zend_Session::forgetMe();
        $this->_redirect('auth/index');
    }

}