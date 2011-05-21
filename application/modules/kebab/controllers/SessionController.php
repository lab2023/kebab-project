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
 * @package    Kebab
 * @subpackage Controllers
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */

/**
 * Session controller manage to login, logout and other operation over session
 *
 * @category   Kebab (kebab-reloaded)
 * @package    Kebab
 * @subpackage Controllers
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */
class Kebab_SessionController extends Kebab_Rest_Controller
{

    /**
     * Login
     *
     * @return void
     */
    public function postAction()
    {
        // Get params
        $userName   = $this->_request->getParam('userName');
        $password   = $this->_request->getParam('password');
        $rememberMe = $this->_request->getParam('remember_me');

        // Check rememberMe checkbox
        if (is_null($rememberMe)) {
            Zend_Session::forgetMe();
        }

        //Filter for SQL Injection
        $validatorUserName = new Zend_Validate();
        $validatorUserName->addValidator(new Zend_Validate_StringLength(4, 16))->addValidator(new Zend_Validate_Alnum());
        $validatorPassword = new Zend_Validate();
        $validatorPassword->addValidator(new Zend_Validate_NotEmpty());

        if ($this->_request->isPost()
            && $validatorPassword->isValid($password)
            && $validatorUserName->isValid($userName)
        ) {
            // set ZendX_Doctrine_Auth_Adapter
            $auth = Zend_Auth::getInstance();
            $authAdapter = new ZendX_Doctrine_Auth_Adapter(Doctrine::getConnectionByTableName('Model_Entity_User'));

            $authAdapter->setTableName('Model_Entity_User u')
                    ->setIdentityColumn('userName')
                    ->setCredentialColumn('password')
                    ->setCredentialTreatment('MD5(?) AND active = 1')
                    ->setIdentity($userName)
                    ->setCredential($password);

            // set Zend_Auth
            $result = $auth->authenticate($authAdapter);

            // Check Auth Validation
            if ($result->isValid()) {

                // Remove some fields which are secure!
                $omitColumns = array('password', 'activationKey', 'created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by');
                $identity = $authAdapter->getResultRowObject(null, $omitColumns);
                $identity->roles = Kebab_Model_User::getUserRoles($identity->id);
                $identity->acl = new Kebab_Access();

                $auth->getStorage()->write($identity);

                if (!is_null($rememberMe)) {
                    Zend_Session::rememberMe(604800);
                }
                $this->_helper->response(true)->getResponse();

            } else {
                $this->_helper->response()->addNotification('ERR', 'Please check your user name and password!')->getResponse();
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
        $this->_helper->response(true);
    }
}