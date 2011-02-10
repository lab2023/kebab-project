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
 * @package    PACKAGE
 * @subpackage SUB_PACKAGE
 * @author     lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */
class Plugin_KebabAcl_Adaptor_Doctrine extends Zend_Acl
{

    private $_config;
    private $_doctrineCaching;

    public function __construct()
    {
        $this->addAllRoles();
        $this->addAllResources();
        $this->addAllPermissions();

        $this->_config = Zend_Registry::get('config');
        $this->_doctrineCaching = $this->_config->database->doctrine->caching->enable ? true : false;
    }

    public function addAllRoles()
    {
        $query = Doctrine_Query::create()
                ->select('r.name, ir.name')
                ->from('Model_Role r')
                ->leftJoin('r.InheritRole ir')
                ->useQueryCache($this->_doctrineCaching);

        $roles = $query->execute();

        foreach ($roles as $role) {
            $inheritRoleName = is_null($role->inheritRole) ? NULL : $role->InheritRole->name;
            parent::addRole(
                    new Zend_Acl_Role($role->name), $inheritRoleName
            );
        }
    }

    public function addAllResources()
    {
        $query = Doctrine_Query::create()
                ->select('c.name, m.name')
                ->from('Model_Controller c')
                ->leftJoin('c.Module m')
                ->useQueryCache($this->_doctrineCaching);
        $resources = $query->execute();

        foreach ($resources as $resource) {
            parent::add(
                    new Zend_Acl_Resource(
                        $resource->Module->name . '-' . $resource->name
                    )
            );
        }
    }

    public function addAllPermissions()
    {
        // First of all deny everything.
        parent::deny();

        $query = Doctrine_Query::create()
                ->select('resource.id, role.id, stories.id, permission.rule, role.name, stories.name, 
                    controller.name, module.name, action.name')
                ->from('Model_Resource resource')
                ->leftJoin('resource.Controller controller')
                ->leftJoin('controller.Module module')
                ->leftJoin('resource.Action action')
                ->leftJoin('resource.Stories stories')
                ->leftJoin('stories.Permission permission')
                ->leftJoin('permission.Role role')
                ->useQueryCache($this->_doctrineCaching);
        $resources = $query->execute();

        foreach ($resources as $resource) {
            if (count($resource->Stories) > 0) {
                $moduleName = $resource->Controller->Module->name;
                $controllerName = !isset($resource->Controller->name) ? null : $resource->Controller->name;
                $actionName = !isset($resource->Action->name) ? null : $resource->Action->name;

                foreach ($resource->Stories as $story) {
                    if (count($story->Permission) > 0) {
                        foreach ($story->Permission as $permission) {
                            $rule = $permission->rule;
                            $roleName = $permission->Role->name;
                            Zend_Registry::get('logging')->log($rule . '-' . $roleName . '-' .
                                $moduleName . '-' . $controllerName . '-' . $actionName, Zend_Log::ERR);
                            parent::$rule($roleName, $moduleName . '-' . $controllerName, $actionName);
                        }
                    }
                }
            }
        }
    }

}
