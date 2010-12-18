<?php

class Kebab_Controller_Plugin_Acl extends Zend_Controller_Plugin_Abstract
{
    private $_acl;

    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $identity = $auth->getIdentity();
            $role = strtolower($identity->role);
        } else {
            //KBBTODO Don't like it' Hardcoding is bad!
            $role = 'guest';
        }

        // Create mvcResource
        $module = ucfirst($request->getModuleName());
        $controller = ucfirst($request->getControllerName());
        $mvcResource = $module . '_' . $controller;
        $action = $request->getActionName();

        self::_setAcl();
        
        if (!$this->_acl->isAllowed($role, $mvcResource, $action)) {
            $request->setModuleName('default');
            $request->setControllerName('auth');
            $request->setActionName('login');
        }
    }

    private function _setAcl()
    {
        $acl = new Zend_Acl();

        //Add Role
        $acl->addRole(new Zend_Acl_Role('guest'));
        $acl->addRole(new Zend_Acl_Role('member'), 'guest');
        $acl->addRole(new Zend_Acl_Role('admin'), 'member');
        $acl->addRole(new Zend_Acl_Role('owner'), 'admin');

        //Add Resource
        $acl->add(new Zend_Acl_Resource('Default_Index'));
        $acl->add(new Zend_Acl_Resource('Default_Auth'));
        $acl->add(new Zend_Acl_Resource('Default_Error'));
        $acl->add(new Zend_Acl_Resource('Default_Main'));
        $acl->add(new Zend_Acl_Resource('Super-duper_Index'));

        // Rules
        $acl->allow('guest', 'Default_Auth', array('login', 'logout'));
        $acl->allow('guest', 'Default_Error');
        $acl->allow('member', 'Default_Main');
        $acl->allow('member', 'Super-duper_Index');

        $this->_acl = $acl;
    }

}