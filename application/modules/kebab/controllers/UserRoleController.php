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
 * User_RoleManager
 *
 * @category   Kebab (kebab-reloaded)
 * @package    Kebab
 * @subpackage Controllers
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */
class Kebab_UserRoleController extends Kebab_Rest_Controller
{
    public function indexAction()
    {
        $roles = Role_Model_Role::getAllRoles()->execute();
        $responseData = is_object($roles) ? $roles->toArray() : array();
        $this->_helper->response(true)->addData($responseData)->getResponse();
    }

    public function putAction()
    {
        // Getting parameters
        $params = $this->_helper->param();
        $userId = $params['id'];
        $rolesId = $params['roles'];

        //KBBTODO move doctrine to models
        Doctrine_Manager::connection()->beginTransaction();
        try {
            // Doctrine
            Doctrine_Query::create()
                    ->delete('Model_Entity_UserRole userRole')
                    ->where('userRole.user_id = ?', $userId)
                    ->execute();

            foreach ($rolesId as $role) {
                $userRole = new Model_Entity_UserRole();
                $userRole->user_id = $userId;
                $userRole->role_id = $role;
                $userRole->save();
            }
            Doctrine_Manager::connection()->commit();
            unset($userRole);
        } catch (Zend_Exception $e) {
            Doctrine_Manager::connection()->rollback();
            throw $e;
        } catch (Doctrine_Exception $e) {
            Doctrine_Manager::connection()->rollback();
            throw $e;
        }
    }
}