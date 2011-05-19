<?php

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
 * @author     lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */


/**
 * User_Manager
 *
 * @category   Kebab (kebab-reloaded)
 * @package    Administration
 * @subpackage Controllers
 * @author     lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */
class User_InviteController extends Kebab_Rest_Controller
{
    public function postAction()
    {
        // params
        $params = $this->_helper->param();
        $firstName = $params['firstName'];
        $lastName = $params['lastName'];
        $email = $params['email'];
        $message = $params['message'];

        // doctrine
        Doctrine_Manager::connection()->beginTransaction();
        try {
            $user = new Model_Entity_User();
            $user->firstName = $firstName;
            $user->lastName = $lastName;
            $user->email = $email;
            $user->username = $email;
            $user->active = 0;
            $user->save();

            $userId = $user->id;
            unset($user);

            $activationKey = md5(mt_rand(10000, 99999));
            $invitation = new Model_Entity_Invitation();
            $invitation->message = $message;
            $invitation->user_id = $userId;
            $invitation->activationKey = $activationKey;
            $invitation->save();
            unset($invitation);

            Doctrine_Manager::connection()->commit();

            // Prepare and Send Invitation e-mail here
            $configParam = Zend_Registry::get('config')->kebab->mail;
            $smtpServer = $configParam->smtpServer;
            $config = $configParam->config->toArray();

            // Mail phtml
            $view = new Zend_View;
            $view->setScriptPath(APPLICATION_PATH . '/views/mails/');

            $identity = Zend_Auth::getInstance()->getIdentity();
            //KBBTODO use language file
            $view->assign('activationKey', $activationKey);
            $view->assign('firstName', $firstName);
            $view->assign('fullName', $identity->firstName . ' ' . $identity->lastName);
            $view->assign('email', $identity->email);
            $view->assign('message', $message);

            $transport = new Zend_Mail_Transport_Smtp($smtpServer, $config);
            $mail = new Zend_Mail('UTF-8');
            $mail->setFrom($configParam->from, 'Kebab Project');
            $mail->addTo($email, $firstName . ' ' . $lastName);
            $mail->setSubject('You\'re invited to join ..... ');
            $mail->setBodyHtml($view->render('send-invitation.phtml'));
            $mail->send($transport);

            // Response
            $this->getResponse()->setHttpResponseCode(200)->appendBody(
                $this->_helper->response()->setSuccess(true)->getResponse()
            );
        } catch (Zend_Exception $e) {
            Doctrine_Manager::connection()->rollback();
            throw $e;
        } catch (Doctrine_Exception $e) {
            Doctrine_Manager::connection()->rollback();
            throw $e;
        }
    }

    public function putAction()
    {
        // params
        $params = $this->_helper->param();
        $email = $params['email'];
        $id = $params['id'];

        Doctrine_Manager::connection()->beginTransaction();
        try {
            $user = new User_Model_User();
            $user->assignIdentifier($id);
            $user->email = $email;
            $user->username = $email;
            $user->save();

            $firstName = $user->firstName;
            $lastName = $user->lastName;
            unset($user);

            $invitation = Doctrine_Core::getTable('Model_Entity_Invitation')->findOneBy('user_id', $id);
            $invitation->save();

            $activationKey = $invitation->activationKey;
            $message = $invitation->message;

            unset($invitation);

            Doctrine_Manager::connection()->commit();

            // Prepare and Send Invitation e-mail here
            $configParam = $params = Zend_Registry::get('config')->kebab->mail;
            $smtpServer = $configParam->smtpServer;
            $config = $configParam->config->toArray();

            // Mail phtml
            $view = new Zend_View;
            $view->setScriptPath(APPLICATION_PATH . '/views/mails/');

            $identity = Zend_Auth::getInstance()->getIdentity();
            //KBBTODO use language file
            $view->assign('activationKey', $activationKey);
            $view->assign('firstName', $firstName);
            $view->assign('fullName', $identity->firstName . ' ' . $identity->lastName);
            $view->assign('email', $identity->email);
            $view->assign('message', $message);

            $transport = new Zend_Mail_Transport_Smtp($smtpServer, $config);
            $mail = new Zend_Mail('UTF-8');
            $mail->setFrom($configParam->from, 'Kebab Project');
            $mail->addTo($email, $firstName . ' ' . $lastName);
            $mail->setSubject('You\'re invited to join ..... ');
            $mail->setBodyHtml($view->render('send-invitation.phtml'));
            $mail->send($transport);

            $this->getResponse()->setHttpResponseCode(200)->appendBody(
                $this->_helper->response()->setSuccess(true)->getResponse()
            );
        } catch (Zend_Exception $e) {
            Doctrine_Manager::connection()->rollback();
            throw $e;
        } catch (Doctrine_Exception $e) {
            Doctrine_Manager::connection()->rollback();
            throw $e;
        }
    }

    public function getAction()
    {

    }
}