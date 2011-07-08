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
 * @package    Application
 * @subpackage Plugins
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */

/**
 * Plugin_KebabAccess
 *
 * @category   Kebab
 * @package    Application
 * @subpackage Plugins
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */
class Plugin_KebabAccess extends Kebab_Controller_Plugin_Abstract
{
    public function __construct()
    {
        parent::__construct(__CLASS__, __FILE__);
    }

    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        // Requests
        $resource = $request->getModuleName() . '_' . $request->getControllerName();
        $action = $request->getActionName();
        
        // Check Acl
        if ($this->_isCheckAccess($request)) {
            if (Zend_Auth::getInstance()->hasIdentity()) {
                $acl = Zend_Auth::getInstance()->getIdentity()->acl;
                $roles = Zend_Auth::getInstance()->getIdentity()->roles;

                $isAllowed = false;
                while (!$isAllowed && list(, $role) = each($roles)) {
                    $isAllowed = $acl->isAllowed($role, $resource, $action);
                }

                if (!$isAllowed) {
                    //KBBTODO get setting from config file
                    $request->setModuleName('default');
                    $request->setControllerName('error');
                    $request->setActionName('unauthorized');
                }
            } else {
                //KBBTODO get setting from config file
                $request->setModuleName('default');
                $request->setControllerName('error');
                $request->setActionName('unauthorized');
            }
        }
    }

    private function _isCheckAccess(Zend_Controller_Request_Abstract $request)
    {
        // Default Return Value
        $isCheckAccess = true;

        // Requests
        $module = $request->getModuleName();
        $controller = $request->getControllerName();
        $action = $request->getActionName();

        // Resources
        $moduleController = $module . '_' . $controller;
        $moduleControllerAction = $module . '_' . $controller . '_' . $action;
        $isOmittedConfig = Zend_Registry::get('config')->kebab->access->omitted->toArray();

        // Check that isOmitted
        if (in_array($moduleController, $isOmittedConfig)
            || in_array($moduleControllerAction, $isOmittedConfig)
        ) {
            $isCheckAccess = false;
        }

        return $isCheckAccess;
    }

}