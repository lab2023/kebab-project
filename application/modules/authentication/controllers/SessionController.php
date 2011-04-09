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
 * @package    Authentication
 * @subpackage Controllers
 * @author     lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */

/**
 * Session controller manage to login, logout and other operation over session
 *
 * @category   Kebab (kebab-reloaded)
 * @package    Session
 * @subpackage Controllers
 * @author     lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */
class Authentication_SessionController extends Kebab_Rest_Controller
{
    /**
     * login Action
     *
     * @return json
     */
    public function postAction()
    {
        // Get params
        $username = $this->_request->getParam('username');
        $password = $this->_request->getParam('password');
        $rememberMe = $this->_request->getParam('remember_me');

        // Check rememberMe checkbox
        if (is_null($rememberMe)) {
            Zend_Session::forgetMe();
        }

        //Filter for SQL Injection
        $validatorUsername = new Zend_Validate();
        $validatorUsername->addValidator(new Zend_Validate_StringLength(4, 16))->addValidator(new Zend_Validate_Alnum());
        $validatorPassword = new Zend_Validate();
        $validatorPassword->addValidator(new Zend_Validate_NotEmpty());

        if ($this->_request->isPost()
            && $validatorPassword->isValid($password)
            && $validatorUsername->isValid($username)
        ) {
            // set ZendX_Doctrine_Auth_Adapter
            $auth = Zend_Auth::getInstance();
            $authAdapter = new ZendX_Doctrine_Auth_Adapter(Doctrine::getConnectionByTableName('User_Model_User'));

            $authAdapter->setTableName('User_Model_User u')
                    ->setIdentityColumn('username')
                    ->setCredentialColumn('password')
                    ->setCredentialTreatment('MD5(?)')
                    ->setIdentity($username)
                    ->setCredential($password);

            // set Zend_Auth
            $result = $auth->authenticate($authAdapter);

            // Check Auth Validation
            if ($result->isValid()) {

                // Remove some fields which are secure!
                $omitColumns = array('password', 'activationKey', 'created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by');
                $identity = $authAdapter->getResultRowObject(null, $omitColumns);

                // Check Acl Plugin is on and write acl and role
                if (Zend_Registry::get('config')->plugins->kebabAcl) {
                    $roles = User_Model_User::getUserRoles($identity->id);
                    $identity->roles = $roles['roles'];
                    $identity->rolesWithAncestor = $roles['rolesWithAncestor'];
                    $identity->acl = new Kebab_Acl();
                }
                $auth->getStorage()->write($identity);

                if (!is_null($rememberMe)) {
                    Zend_Session::rememberMe(604800);
                }
                $this->_helper->response()
                        ->setSuccess(true)
                        ->getResponse();
            } else {
                $this->_helper->response()
                        ->addNotification('ERR', 'Please check your user name and password!')
                        ->getResponse();
            }
        } else {
            $this->_helper->response()->getResponse();
        }
    }

    /**
     * Logout action
     *
     * @return void
     */
    public function deleteAction()
    {
        Zend_Auth::getInstance()->clearIdentity();
        Zend_Session::forgetMe();
        $this->_helper->response()->setSuccess(true);
    }
}