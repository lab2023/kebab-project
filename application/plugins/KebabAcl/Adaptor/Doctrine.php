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
                ->select('module.name, acontroller.name, controller.name, action.name, 
                    service.id, role.id, story.id, permission.rule, role.name, story.name')
                ->from('Model_Service service')
                ->leftJoin('service.Resource controller')
                ->leftJoin('controller.Module module')
                ->leftJoin('service.Action action')
                ->leftJoin('action.Controller acontroller')
                ->leftJoin('service.Story story')
                ->leftJoin('story.Permission permission')
                ->leftJoin('permission.Role role');
        $services = $query->execute();
        
        if (count($services->toArray()) > 0 ) {
            
            foreach ($services as $service) {
                $action = !isset($service->Action->name) ? null : $service->Action->name;
                $resource = (isset($service->Resource)) 
                          ? $service->Resource->Module->name .'-'. $service->Resource->name 
                          : null;
                $resource = is_null($resource) && isset($service->Action->Controller)
                          ? $service->Action->Controller->Module->name .'-'. $service->Action->Controller->name 
                          : $resource;
                
                if (isset($service->Story)) {
                    foreach ($service->Story->Permission->toArray() as $permission) {
                        if (count($permission) > 0) {
                            Zend_Registry::get('logging')->log(
                                $permission['rule'] . '-' . 
                                $permission['Role']['name'] . '-' .
                                $resource . '-' . 
                                $action, Zend_Log::DEBUG
                            );
                            parent::$permission['rule'](
                                $permission['Role']['name'],  
                                $resource,  
                                $action
                            );
                        }
                    }
                }
            }
        } 
                            
                        
    }

}
