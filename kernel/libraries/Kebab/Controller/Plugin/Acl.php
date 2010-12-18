<?php

class Kebab_Controller_Plugin_Acl extends Zend_Controller_Plugin_Abstract
{

    private $_acl;
    private $_roles = array();

    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $identity = $auth->getIdentity();
            $this->_roles = $identity->roles;
            $this->_acl = $identity->acl;
        } else {
            //KBBTODO Don't like it' Hardcoding is bad!
            $this->_roles = array('guest');
            $this->_acl = new Kebab_Acl();
        }

        // Create mvcResource
        $module = ucfirst($request->getModuleName());
        $controller = ucfirst($request->getControllerName());
        $mvcResource = $module . '_' . $controller;
        $action = $request->getActionName();

        //Check Rules
        $isAllowed = FALSE;
        while (!$isAllowed && list(, $role) = each($this->_roles)) {
            $isAllowed = $this->_acl->isAllowed($role, $mvcResource, $action);
        }

        if (!$isAllowed) {
            $request->setModuleName('default');
            $request->setControllerName('auth');
            $request->setActionName('login');
        }
    }

}