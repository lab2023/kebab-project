<?php if ( ! defined('BASE_PATH')) exit('No direct script access allowed');
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
 * @package    Applications
 * @subpackage SysAdministration_Controllers
 * @author	   lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */

/**
 * Kebab SysAdministration Module User Controller
 *
 * @category   Kebab (kebab-reloaded)
 * @package    Applications
 * @subpackage SysAdministration_Controllers
 * @author	   lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */
class Administration_UserController extends Kebab_Controller_Action
{
    public function indexAction()
    {
        
    }        

    public function sendInvitationAction()
    {
        if ($this->_request->isPost()) {

            // Get params
            $firstName = $this->_request->getParam('first_name');
            $surname = $this->_request->getParam('surname');
            $email = $this->_request->getParam('email');
            $message = $this->_request->getParam('message');

            //Filter for SQL Injection
            // KBBTODO: Add validator here
            $user = Doctrine_Core::getTable('Model_User')
                                    ->findOneBy('email', $email);
            if (!$user) {

                $auth = Zend_Auth::getInstance();

                $invitation = new Model_Invitation();
                $invitation->message = $message;
                $invitation->activationKey = md5(uniqid());

                $user = $invitation->User;
                $user->firstName = $firstName;
                $user->surname = $surname;
                $user->email = $email;

                $invitation->invitedBy = $auth->getIdentity()->id;

                try {
                    $invitation->save();

                    //KBBTODO move these settings to config file
                    $smtpServer = 'smtp.gmail.com';
                    $config = array(
                        'ssl'      => 'tls',
                        'auth'     => 'login',
                        'username' => 'noreply@kebab-project.com',
                        'password' => 'xxxxx'
                    );
                    // Mail PHTML
                    $view = new Zend_View;
                    $view->setScriptPath(APPLICATION_PATH . '/views/mails');

                    //KBBTODO use language file
                    $view->assign('activationKey', $invitation->activationKey);
                    $view->assign('firstName', $user->firstName);
                    $view->assign('fullName', $auth->getIdentity()->firstName . ' ' . $auth->getIdentity()->surname);
                    $view->assign('email', $auth->getIdentity()->email);
                    $view->assign('message', $message);

                    $transport = new Zend_Mail_Transport_Smtp($smtpServer, $config);
                    $mail = new Zend_Mail();
                    $mail->setFrom('noreply@kebab-project.com', $auth->getIdentity()->firstName . ' ' . $auth->getIdentity()->surname);
                    $mail->addTo($user->email, $user->firstName . $user->surname);
                    $mail->setSubject('You\'re invited to join ' . '......');
                    $mail->setBodyHtml($view->render('send-invitation.phtml'));
                    $mail->send($transport);

                    // KBBTODO : Use response
//                    $notification = new Kebab_Notification();
//                    $notification->addNotification(Kebab_Notification::INFO, 'Invitation send.');
                } catch (Exception $exc) {
                   // KBBTODO: Error logging here
                }



            } else {
                // KBBTODO : Use response
                // KBBTODO : use translate
//                $notification = new Kebab_Notification();
//                $notification->addNotification(Kebab_Notification::ERR, "This email already exists. ");
                $this->_redirect('administration/user/send-invitation/fail');
            }
        }
    }
}
