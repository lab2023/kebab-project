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
class SysAdministration_UserController
    extends Kebab_Controller_Action
{
    public function indexAction()
    {
        
    }

    public function sendInvitationAction()
    {
        // Get params
        $firstName = $this->_request->getParam('first_name');
        $surname = $this->_request->getParam('surname');
        $email = $this->_request->getParam('email');
        $message = $this->_request->getParam('message');

        //Filter for SQL Injection
        // KBBTODO: Add validator here

        if ($this->_request->isPost()) {

            $user = Doctrine_Core::getTable('System_Model_User')
                                    ->findOneBy('email', $email);
            if (!$user) {

                $conn = Doctrine_Manager::connection();

                $conn->beginTransaction();

                try {
                    $auth = Zend_Auth::getInstance();

                    $invitation = new System_Model_Invitation();
                    $invitation->message = $message;
                    $invitation->activationKey = md5(uniqid());

                    $user = $invitation->User;
                    $user->firstName = $firstName;
                    $user->surname = $surname;
                    $user->email = $email;

                    $invitation->invitedBy = $auth->getIdentity()->id;

                    $invitation->save();

                    // KBBTODO: Prepare and Send invitation email here
                    $conn->commit();

                } catch (Zend_Exception $e) {
                    $conn->rollback();
                }
                
            } else {

                //KBBTODO use translate
                $notification = new Kebab_Notification();
                $notification->addNotification(Kebab_Notification::ERR, "This email already exists. ");
                $this->_redirect('sys-administration/user/send-invitation/fail');
            }
        }
    }
}
