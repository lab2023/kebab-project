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
 * @category   Kebab
 * @package    Kebab
 * @subpackage library
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */
class Kebab_Access_Acl extends Zend_Acl
{
    /**
     * @var mixed
     */
    private $_config;

    /**
     * @var bool
     */
    private $_doctrineCaching;


    /**
     *
     */
    public function __construct()
    {
        $this->addAllRoles();
        $this->addAllResources();
        $this->addAllPermissions();

        $this->_config = Zend_Registry::get('config');
        $this->_doctrineCaching = $this->_config->database->doctrine->caching->enable ? true : false;
    }

    /**
     * @return void
     */
    public function addAllRoles()
    {
        $query = Doctrine_Query::create()
                    ->select('r.id')
                    ->from('Model_Entity_Role r')
                    ->useQueryCache(Kebab_Cache_Query::isEnable());

        $roles = $query->execute();

        foreach ($roles as $role) {
            parent::addRole(new Zend_Acl_Role($role->id));
        }
    }

    /**
     * @return void
     */
    public function addAllResources()
    {
        $query = Doctrine_Query::create()
                ->select('c.name, m.name')
                ->from('Model_Entity_Controller c')
                ->leftJoin('c.Module m')
                ->useQueryCache(Kebab_Cache_Query::isEnable());
        $resources = $query->execute();

        foreach ($resources as $resource) {
            parent::add(new Zend_Acl_Resource($resource->Module->name . '_' . $resource->name));
        }
    }

    /**
     * @return void
     */
    public function addAllPermissions()
    {
        // First of all deny everything.
        parent::deny();

        $query = Doctrine_Query::create()
                ->select('module.name, acontroller.name, controller.name, action.name,
                    service.id, role.id, story.id, permission.*, story.name')
                ->from('Model_Entity_Service service')
                ->leftJoin('service.Resource controller')
                ->leftJoin('controller.Module module')
                ->leftJoin('service.Action action')
                ->leftJoin('action.Controller acontroller')
                ->leftJoin('service.Story story')
                ->leftJoin('story.Permission permission')
                ->leftJoin('permission.Role role')
                ->useQueryCache(Kebab_Cache_Query::isEnable());
        $services = $query->execute();

        if (count($services->toArray()) > 0 ) {

            foreach ($services as $service) {
                $action   = !isset($service->Action->name) ? null : $service->Action->name;
                $resource = (isset($service->Resource))
                          ? $service->Resource->Module->name .'_'. $service->Resource->name
                          : null;
                $resource = is_null($resource) && isset($service->Action->Controller)
                          ? $service->Action->Controller->Module->name .'_'. $service->Action->Controller->name
                          : $resource;

                if (isset($service->Story)) {
                    foreach ($service->Story->Permission->toArray() as $permission) {
                        if (count($permission) > 0) {
                            Zend_Registry::get('logging')->log(
                                $permission['Role']['id'] . '-' .
                                $resource . '-' .
                                $action, Zend_Log::DEBUG
                            );
                            parent::allow($permission['Role']['id'], $resource, $action);
                        }
                    }
                }
            }
        }
    }
}
