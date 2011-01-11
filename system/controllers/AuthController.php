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
     * index()
     *
     * <p>Check user name and password and authorize user</p>
     * 
     * @return void
     */
    public function indexAction()
    {
        
    }

    /**
     * loginAction()
     * 
     * <p>Check user name and password and authorize user</p>
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
        //KBBTODO Has identity!!!
        $authAdapter = Zend_Auth::getInstance();
        $authAdapter->clearIdentity();
        Zend_Session::forgetMe();
        $this->_redirect('auth/index');
    }

    /**
     * forgotPassword()
     * 
     * Check email, send reset password link mail
     *
     * @return void
     */
    public function forgotPasswordAction()
    {
        $email = $this->_request->getParam('email');
        $validatorEmail = new Zend_Validate_EmailAddress();

        // Create user object
        $user = Doctrine::getTable('System_Model_User')
                ->findOneByemail($email);

        //Filter for SQL Injection
        if ($this->_request->isPost()
            && $validatorEmail->isValid($email)
            && ($user !== FALSE)
        ) {
            //KBBTODO We need a secure key for application
            $activationKey = sha1(mt_rand(10000, 99999) . time() . $email);

            $user->activationKey = $activationKey;
            $user->save();

            //KBBTODO move these settings to config file
            $smtpServer = 'smtp.gmail.com';
            $config = array(
                'ssl' => 'tls',
                'auth' => 'login',
                'username' => 'noreply@kebab-project.com',
                'password' => 'xxxxx'
            );

            // Mail PHTML
            $view = new Zend_View;
            $view->setScriptPath(SYSTEM_PATH . '/views/mails/');

            //KBBTODO assign other things like username
            //KBBTODO use language file
            $view->assign('activationKey', $activationKey);

            $transport = new Zend_Mail_Transport_Smtp($smtpServer, $config);
            $mail = new Zend_Mail();
            $mail->setFrom('noreply@kebab-project.com', 'Kebab Project');
            $mail->addTo($user->email, $user->firstName . $user->surname);
            $mail->setSubject('Reset your password');
            $mail->setBodyHtml($view->render('forgot-password.phtml'));
            $mail->send($transport);

            $notification = new Kebab_Notification();
            $notification->addNotification(Kebab_Notification::INFO, 'Please look your email.');
            $this->_helper->redirector('login');
        } else {
            $notification = new Kebab_Notification();
            $notification->addNotification(Kebab_Notification::ALERT, 'Please write your email correct!');
        }
    }

    /**
     *  resetPassword()
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
            $user = Doctrine::getTable('System_Model_User')
                ->findOneByactivationKey($activationKey);
        }


        // NULL or expired activation key
        if (!$this->_request->isGet()
            && is_null($activationKey)
            && is_null($user->activationKey)
        ) {
            $this->_helper->redirector('auth/reset-password-expired');
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
            $notification = new Kebab_Notification();
            $notification->addNotification(Kebab_Notification::INFO, 'Your password is changed.');
            $this->_helper->redirector('login');
        }

        $this->view->activationKey = $activationKey;
    }

    /**
     * resetPasswordExpired()
     *
     * <p>If the activationKey is expired, show the view</p>
     *
     * @return void
     */
    public function resetPasswordExpiredAction()
    {
        
    }

}