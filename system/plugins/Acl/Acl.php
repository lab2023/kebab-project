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
 * @package    Kebab
 * @author	   lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */

/**
 * Kebab ACL
 * This class setup and all acl resources
 *
 * Assertions
 * 1. Every controller has a module.
 * 2. Every action has a controller.
 *
 * @category   Kebab (kebab-reloaded)
 * @package    Kebab
 * @author     lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */
class System_Plugin_Acl_Acl extends Zend_Acl
{

    private 
        $_config,
        $_doctrineCaching;
    
    /**
     * Call all private methods this class
     *
     * @return void
     */
    public function __construct()
    {   
        Zend_Registry::get('logging')->log(
            __CLASS__ . ' initializated...',
            Zend_Log::INFO
        );

        $this->_config = Zend_Registry::get('config');
        $this->_doctrineCaching = $this->_config->database->doctrine->caching->enable ? true : false;
        
        self::addAllRoles();
        self::addAllResources();
        self::addAllPermissions();

    }

    /**
     * Add kebab roles
     * 
     * @return void
     */
    private function addAllRoles()
    {
        $query = Doctrine_Query::create()
                ->select('r.name, ir.name')
                ->from('System_Model_Role r')
                ->leftJoin('r.InheritRole ir')
                ->useQueryCache($this->_doctrineCaching);
        
        $roles = $query->execute();

        foreach ($roles as $role) {
            $inheritRoleName = is_null($role->inheritRole) ? NULL : $role->InheritRole->name;
            parent::addRole(
                    new Zend_Acl_Role($role->name),
                    $inheritRoleName
            );
        }
    }

    /**
     * Add kebab resources
     *
     * @return void
     */
    private function addAllResources()
    {
        $query = Doctrine_Query::create()
                ->select('c.name, m.name')
                ->from('System_Model_Controller c')
                ->leftJoin('c.Module m')
                ->useQueryCache($this->_doctrineCaching);
        $controllers = $query->execute();

        foreach ($controllers as $controller) {
            parent::add(
                    new Zend_Acl_Resource(
                        $controller->Module->name . '_' . $controller->name
                    )
            );
        }
    }

    /**
     * Add kebab acl allows
     * 
     * @return void
     */
    private function addAllPermissions()
    {
        //First off deny every resource for every roles
        parent::deny();

        $query = Doctrine_Query::create()
                ->select('r.name, p.rule, c.name, m.name, a.name')
                ->from('System_Model_Permission p')
                ->leftJoin('p.Role role')
                ->leftJoin('p.Controller c')
                ->leftJoin('c.Module m')
                ->leftJoin('p.Assertion a')
                ->useQueryCache($this->_doctrineCaching);
        $permissions = $query->execute();

        (string) $rule = 'deny';

        foreach ($permissions as $permission) {

            $rule = $permission->rule;
            $roleName = is_null($permission->Role) ? NULL : $permission->Role->name;
            $controller = is_null($permission->Controller) ? $permission->Action->Controller : $permission->Controller;
            $resourceName = is_null($controller->name) ? NULL : $controller->Module->name . '_' . $controller->name;
            $actionName = is_null($permission->Action) ? NULL : $permission->Action->name;
            $assertionName = is_null($permission->Assertion) ? NULL : $permission->Assertion->name;

            parent::$rule(
                    $roleName,
                    $resourceName,
                    $actionName,
                    $assertionName
            );
        }

        parent::allow();
    }

}