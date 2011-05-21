<?php

/**
 * Kebab Framework
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
 * @category   Kebab (kebab-reloaded)
 * @package    Kebab
 * @subpackage Controllers
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */


/**
 * Kebab_ActivationController
 *
 * @category   Kebab (kebab-reloaded)
 * @package    Kebab
 * @subpackage Controllers
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */
class Kebab_UserActivationController extends Kebab_Rest_Controller
{

    public function getAction()
    {
        // Getting parameters
        $params = $this->_helper->param();
        $response = $this->_helper->response(true, 200);

        $user = Doctrine_Core::getTable('Model_Entity_User')->findOneByactivationKey($params['key']);
        if (is_object($user)) {
            $responseData = $user->toArray();
            $response->addData($responseData);
        } else {
            $response->addNotification('ERR', 'Invalid activation key');
        }

        $response->getResponse();
    }
    
    /**
     * User can invited or sign-up
     *
     * @return void
     */
    public function postAction()
    {
        // Getting parameters
        $params = $this->_helper->param();
        $response = $this->_helper->response(true);

        // Check username dublicate
        $user = Doctrine_Core::getTable('Model_Entity_User')->findByuserNameOremail($params['userName'], $params['email']);
        if (is_object($user)) {
            $response->addNotification('ERR', 'This username or email already at system.')->getResponse();
        }

        $id = Doctrine_Core::getTable('Model_Entity_User')->findOneByactivationKey($params['key'])->id;
        $user = new Model_Entity_User();
        $user->assignIdentifier($id);
        $user->userName = $params['userName'];
        $user->password = md5($params['password']);
        $user->activationKey = NULL;
        $user->status = 'approved';
        $user->active = 1;
        $user->save();

        $userRole = new Model_Entity_UserRole();
        $userRole->role_id = 1;
        $userRole->user_id = $user->id;
        $userRole->save();

        Kebab_Authentication::signIn($user->userName, $params['userName']);
        
        $response->getResponse();
    }
}