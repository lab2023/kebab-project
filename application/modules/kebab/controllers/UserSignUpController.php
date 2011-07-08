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
 * UserSignUp Controller
 *
 * @category   Kebab
 * @package    Kebab
 * @subpackage Controllers
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */
class Kebab_UserSignUpController extends Kebab_Rest_Controller
{
    public function postAction()
    {
        $params = $this->_helper->param();
        $response = $this->_helper->response();

        // Check email
        $emailValidation = new Zend_Validate_EmailAddress();
        $isValidEmail  = $emailValidation->isValid($params['email']);
        $isUnusedEmail = is_object(Doctrine_Core::getTable('Model_Entity_User')->findOneByemail($params['email']));

        if (!$isValidEmail) {
            throw new Kebab_Exception("Invalid email wanna to signup.");
        }

        if (!$isUnusedEmail) {
            $user   = Kebab_Model_User::signUp($params['fullName'], $params['email']);
            $this->sendSignUpMail($user);
            $response->setSuccess(true)->getResponse();
        } else {
            $response->addError('email', 'An other user uses this email');
        }
        
        $response->getResponse();
    }

    private function sendSignUpMail($user)
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

        $transport = new Zend_Mail_Transport_Smtp($smtpServer, $config);
        Zend_Mail::setDefaultTransport($transport);
        $mail = new Zend_Mail('UTF-8');
        $mail->setFrom($configParam->from, 'Kebab Project');
        $mail->addTo($user->email, $user->fullName);
        $mail->setSubject('Welcome to Kebab Project');
        $mail->setBodyHtml($view->render('sign-up.phtml'));
        $mail->send($transport);
    }
}