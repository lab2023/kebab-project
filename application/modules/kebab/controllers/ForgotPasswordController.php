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
 * @package    Kebab
 * @subpackage Controllers
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */

/**
 * Send the user forgetpassword
 *
 * @category   Kebab (kebab-reloaded)
 * @package    Kebab
 * @subpackage Controllers
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */
class Kebab_ForgotPasswordController extends Kebab_Rest_Controller
{
    public function postAction()
    {
        $email = $this->_request->getParam('email');
        $validatorEmail = new Zend_Validate_EmailAddress();
        $response = $this->_helper->response();

        if ($validatorEmail->isValid($email)) {

            // Create user object
            $user = Doctrine_Core::getTable('User_Model_User')->findOneBy('email', $email);

            if ($user !== false) {
                $activationKey = md5(mt_rand(10000, 99999));
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
                $mail = new Zend_Mail('UTF-8');
                $mail->setFrom($configParam->from, 'Kebab Project');
                $mail->addTo($user->email, $user->firstName . $user->lastName);
                $mail->setSubject('Reset Password');
                $mail->setBodyHtml($view->render('forgot-password.phtml'));
                $mail->send($transport);
                $response->setSuccess(true)->getResponse();
            } else {
                $response->addNotification('ERR', 'There isn\'t user with this email')->getResponse();
            }
        } else {
            $response->addError('email', 'Invalid email format')->getResponse();
        }
    }
}