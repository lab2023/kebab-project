<?php
/**
 * Kebab Project
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
 * @category   Kebab
 * @package    Kebab
 * @subpackage Controllers
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */


/**
 * Kebab_USerInvitationController
 *
 * @category   Kebab
 * @package    Kebab
 * @subpackage Controllers
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */
class Kebab_UserInvitationController extends Kebab_Rest_Controller
{
    public function postAction()
    {
        // Params
        $params = $this->_helper->param();
        $response = $this->_helper->response();
        $valid = true;

        // Validatation

        // Exist user
        $count = Doctrine_Core::getTable('Model_Entity_User')->findByemail($params['email'])->count();
        if ($count > 0) {
            $response->addNotification(Kebab_Notification::ERR, 'There is a user with this email');
            $valid = false;
        }

        // Invalid email address
        $validator = new Zend_Validate_EmailAddress();
        if (!$validator->isValid($params['email'])) {
            $response->addNotification(Kebab_Notification::ERR, 'Invalid email address');
            $valid = false;
        }

        // Check Fullname only Alpha chracter enable
        $validator = new Zend_Validate_Alpha(array('allowWhiteSpace' => true));
        if (!$validator->isValid($params['fullName'])) {
            $response->addNotification(Kebab_Notification::ERR, 'Only alpha character enable.');
            $valid = false;
        }

        // Model
        if ($valid) {
            $user = Kebab_Model_User::invite($params['fullName'], $params['email']);
        }

        // Response
        if ($valid && is_object($user)) {
            $this->sendInviteMail($user, $params['message']);
            $response->setSuccess(true);
        }

        $response->getResponse();
    }

    private function sendInviteMail($user, $message)
    {
        $configParam = Zend_Registry::get('config')->kebab->mail;
        $smtpServer = $configParam->smtpServer;
        $config = $configParam->config->toArray();

        // Mail phtml
        $view = new Zend_View;
        $view->setScriptPath(APPLICATION_PATH . '/views/mails/');

        //KBBTODO use language file
        $view->assign('id', $user->id);
        $view->assign('fullName', $user->fullName);
        $view->assign('activationKey', $user->activationKey);
        $view->assign('message', $message);

        $transport = new Zend_Mail_Transport_Smtp($smtpServer, $config);
        Zend_Mail::setDefaultTransport($transport);
        $mail = new Zend_Mail('UTF-8');
        $mail->setFrom($configParam->from, 'Kebab Project');
        $mail->addTo($user->email, $user->fullName);
        $mail->setSubject('Welcome to Kebab Project');
        $mail->setBodyHtml($view->render('invite.phtml'));
        $mail->send($transport);
    }
}