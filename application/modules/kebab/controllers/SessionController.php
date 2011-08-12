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
 * Session controller manage to login, logout and other operation over session
 *
 * @category   Kebab
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
        $userName = $this->_request->getParam('userName');
        $password = $this->_request->getParam('password');
        $rememberMe = $this->_request->getParam('remember_me');

        // Check rememberMe checkbox
        if (is_null($rememberMe)) {
            Zend_Session::forgetMe();
        }

        if ($this->getRequest()->isPost()
            && Kebab_Validation_UserName::isValid($userName)
            && Kebab_Validation_Password::isValid($password)
        ) {
            $hasIdentity = Kebab_Authentication::signIn($userName, $password, !is_null($rememberMe));
            if ($hasIdentity) {
                $this->_helper->response(true, 200)->getResponse();
            } else {
                $this->_helper->response()
                        ->addNotification(Kebab_Notification::ERR, 'Please check your user name and password!')
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
        Kebab_Authentication::signOut();
        $this->_helper->response(true, 204)->getResponse();
    }
}