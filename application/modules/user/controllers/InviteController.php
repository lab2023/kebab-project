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
 * @author     lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
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
 * @license    http://www.kebab-project.com/licensing
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
            $user->save();

            $userId = $user->id;
            unset($user);

            $inviteUser = new Model_Entity_Invitation();
            $inviteUser->message = $message;
            $inviteUser->user_id = $userId;
            $inviteUser->save();
            unset($inviteUser);

            Doctrine_Manager::connection()->commit();

            // Response
            $this->getResponse()->setHttpResponseCode(200)->appendBody(
                $this->_helper->response()->setSuccess(true)->getResponse()
            );
        } catch (Doctrine_Exception $e) {
            Doctrine_Manager::connection()->rollback();
            throw $e;
        }
    }

    public function putAction()
    {

    }

    public function deleteAction()
    {

    }
}