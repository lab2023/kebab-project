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
     * indexAction
     *
     * <p>Login form</p>
     *
     * @return void
     */
    public function indexAction()
    {
        
    }

    /**
     * loginAction
     *
     * <p>Check user name and password and authorize user</p>
     *
     * @return void
     */
    public function loginAction()
    {

        // Get params
        $username = $this->_request->getParam('username');
        $password = $this->_request->getParam('password');
        $rememberMe = $this->_request->getParam('remember_me');

        if (is_null($rememberMe)) {
            Zend_Session::forgetMe();
        }

        //Filter for SQL Injection
        $validatorUsername = new Zend_Validate();
        $validatorUsername->addValidator(new Zend_Validate_StringLength(4, 16))
            ->addValidator(new Zend_Validate_Alnum());

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
                $omitColumns = array('password', 'activationKey', 'created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by');
                $identity = $authAdapter->getResultRowObject( null, $omitColumns);

                // Check Acl Plugin is on and write acl and role
                if (Zend_Registry::get('config')->plugins->kebabAcl) {
                    $roles                       = User_Model_User::getUserRoles($identity->id);
                    $identity->roles             = $roles['roles'];
                    $identity->rolesWithAncestor = $roles['rolesWithAncestor'];
                    $identity->acl               = new Kebab_Acl();              
                }
                $auth->getStorage()->write($identity);
                
                //KBBTODO Set session time and check from getParams
                if (!is_null($rememberMe)) {
                    Zend_Session::rememberMe(604800);
                }
                //KBBTODO Language notification
                $this->_redirect('main');
            } else {
                //KBBTODO Language notification
                $this->_redirect('auth/index');
            }
        } else {
            //KBBTODO Language notification
            $this->_redirect('auth/index');
        }
    }

    /**
     * logoutAction
     *
     * <p>Logout, clear authorized user identity and redirect login page</p>
     *
     * @return void
     */
    public function logoutAction()
    {
    }

    /**
     * forgotPassword
     *
     * Check email, send reset password link mail
     *
     * @return void
     */
    public function forgotPasswordAction()
    {
        $email = $this->_request->getParam('email');
        $validatorEmail = new Zend_Validate_EmailAddress();
        $response = $this->_helper->response();

        if ($this->_request->isPost()) {
            if ($validatorEmail->isValid($email)) {

                // Create user object
                $user = Doctrine_Core::getTable('Model_User')
                        ->findOneBy('email', $email);

                if ($user !== false) {
                    //KBBTODO We need a secure key for application
                    $activationKey = sha1(mt_rand(10000, 99999) . time() . $email);
                    $user->activationKey = $activationKey;
                    $user->save();

                    //KBBTODO move these settings to config file                    
                    $configParam = $params = Zend_Registry::get('config')->kebab->mail;
                    $smtpServer = $configParam->smtpServer;
                    $config = $configParam->config->toArray();

                    // Mail phtml
                    $view = new Zend_View;
                    $view->setScriptPath(APPLICATION_PATH . '/views/mails/');

                    //KBBTODO use language file
                    $view->assign('activationKey', $activationKey);

                    $transport = new Zend_Mail_Transport_Smtp($smtpServer, $config);
                    $mail = new Zend_Mail();
                    $mail->setFrom('noreply@kebab-project.com', 'Kebab Project');
                    $mail->addTo($user->email, $user->firstName . $user->lastname);
                    $mail->setSubject('Reset your password');
                    $mail->setBodyHtml($view->render('forgot-password.phtml'));
                    $mail->send($transport);

                    $this->_helper->redirector('login');
                } else {
                    $response->addNotification('ERR', 'There isn\'t user with this email')->getResponse();
                }
            } else {
                $response->addError('email', 'Invalid email format')->getResponse();
            }
        }
    }

    /**
     *  resetPassword
     *
     *  <p>Check the activationKey from url, show the resetPassword view, reset the password</p>
     *
     *  @return void
     */
    public function resetPasswordAction()
    {
        // Set variables from request
        $activationKey = $this->_request->getParam('activation-key');
        $password = $this->_request->getParam('password');
        $passwordConfirm = $this->_request->getParam('password-confirm');

        // Create a user object from doctrine
        if (!is_null($activationKey)) {
            $user = Doctrine::getTable('Model_User')
                    ->findOneByactivationKey($activationKey);
        }

        // Null activation key
        if (!$user) {
            $this->_helper->redirector('reset-password-expired');
        }

        // Reset password and activationKey
        if ($this->_request->isPost()
            && !is_null($password)
            && !is_null($passwordConfirm)
            && ($password === $passwordConfirm)
            && is_object($user)
        ) {
            $user->activationKey = NULL;
            //KBBTODO we need more secure!!!
            $user->password = md5($password);
            $user->save();

            //KBBTODO Use Translate
            $this->_helper->redirector('login');
        }

        $this->view->activationKey = $activationKey;
    }

    /**
     * resetPasswordExpired
     *
     * <p>If the activationKey is expired, show the view</p>
     *
     * @return void
     */
    public function resetPasswordExpiredAction()
    {
        
    }

    /**
     * Accept Invitation
     *
     * <p> User invited accepts invitation</p>
     *
     * @return void
     */
    public function acceptInvitationAction()
    {
        $activationKey = $this->getRequest()->getParam('check');

        $invitationValid = false;

        if ($activationKey) {

            $invitation = Doctrine_Core::getTable('Model_Invitation')
                    ->findOneBy('activationKey', $activationKey);

            if ($invitation) {

                $invitationValid = true;
                $this->view->activationKey = $activationKey;

                if ($this->_request->isPost()) {

                    // Get params
                    $username = $this->_request->getParam('username');
                    $password = $this->_request->getParam('password');
                    $rePassword = $this->_request->getParam('re_password');

                    // Filter for SQL Injection
                    // KBBTODO: Add validator here
                    $user = Doctrine_Core::getTable('Model_User')
                            ->findOneBy('username', $username);

                    if (!$user && $password == $rePassword) {

                        // Get invited user
                        $user = $invitation->User;
                        $user->username = $username;
                        $user->password = md5($password);

                        // KBBTODO: Get Default Role(s) from configuration
                        $defaultRole = Doctrine_Core::getTable('Model_Role')
                                ->findOneBy('name', 'guest');

                        $user->Roles[] = $defaultRole;
                        $user->save();

                        // Delete old invitation
                        $invitation->delete();

                        $this->_redirect('auth');
                    }
                }
            }
        } //eof if

        if (!$invitationValid) { // KBBTODO: No invitation error
            $this->view->message = "No invitation or invitation has expired";
            $this->renderScript('auth/accept-invitation-error.phtml');
        }
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
