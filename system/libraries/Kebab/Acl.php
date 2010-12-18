<?php

class Kebab_Acl extends Zend_Acl
{

    function __construct()
    {
        self::addAllRoles();
        self::addAllResources();
        self::addAllAllow();
    }

    private function addAllRoles()
    {
        //Add Role
        parent::addRole(new Zend_Acl_Role('guest'));
        parent::addRole(new Zend_Acl_Role('member'));
        parent::addRole(new Zend_Acl_Role('admin'));
        parent::addRole(new Zend_Acl_Role('owner'));
    }

    private function addAllResources()
    {
        //Add Resource
        parent::add(new Zend_Acl_Resource('Default_Index'));
        parent::add(new Zend_Acl_Resource('Default_Auth'));
        parent::add(new Zend_Acl_Resource('Default_Error'));
        parent::add(new Zend_Acl_Resource('Default_Main'));
    }

    private function addAllAllow()
    {
        // Rules
        parent::allow('guest', 'Default_Auth', array('login', 'logout'));
        parent::allow('guest', 'Default_Error');
        parent::allow('member', 'Default_Main', array('member', 'index'));
        parent::allow('admin', 'Default_Main', array('admin'));
        parent::allow('owner', 'Default_Main', array('owner'));
    }

}
