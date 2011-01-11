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
 * @author	   lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */
class Kebab_Acl extends Zend_Acl
{

    /**
     * Call all private methods this class
     *
     * @return void
     */
    public function __construct()
    {
        self::addAllRoles();
        self::addAllResources();
        self::addAllAllow();
    }

    /**
     * Add kebab roles
     * 
     * @return void
     */
    private function addAllRoles()
    {
        $query = Doctrine_Query::create()
                ->select('r.roleName, ir.roleName')
                ->from('System_Model_Role r')
                ->leftJoin('r.InheritRole ir');
        $roles = $query->execute();

        foreach ($roles as $role) {
            $inheritRole = is_null($role->inheritRole) ? NULL : $role->InheritRole->roleName;
            parent::addRole(
                    new Zend_Acl_Role($role->roleName),
                    $inheritRole
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
                ->select('r.controller, m.moduleName')
                ->from('System_Model_Resource r')
                ->leftJoin('r.Module m');
        $resources = $query->execute();

        foreach ($resources as $resource) {
            parent::add(
                    new Zend_Acl_Resource(
                        $resource->Module->moduleName . '_' . $resource->controller
                    )
            );
        }
    }

    /**
     * Add kebab acl allows
     * 
     * @return void
     */
    private function addAllAllow()
    {
        //First off deny every resource for every roles
        parent::deny();

        $query = Doctrine_Query::create()
                ->select('ro.roleName, ra.allow, re.controller, mo.moduleName, as.assertionName')
                ->from('System_Model_RoleAccess ra')
                ->leftJoin('ra.Role ro')
                ->leftJoin('ra.Resource re')
                ->leftJoin('re.Module mo')
                ->leftJoin('ra.Assertion as');
        $rules = $query->execute();

        (string) $ruleType = 'deny';

        foreach ($rules as $rule) {

            $ruleType = $rule->allow;
            $roleName = is_null($rule->Role) ? NULL : $rule->Role->roleName;
            $resource = is_null($rule->Resource) ? $rule->Action->Resource : $rule->Resource;
            $resourceName = is_null($resource->controller) ? NULL : $resource->Module->moduleName . '_' . $resource->controller;
            $actionName = is_null($rule->Action) ? NULL : $rule->Action->action;
            $assertionName = is_null($rule->Assertion) ? NULL : $rule->Assertion->assertionName;

            parent::$ruleType(
                    $roleName,
                    $resourceName,
                    $actionName,
                    $assertionName
            );
        }

        parent::allow();
    }

}