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
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */

/**
 * Plugin_KebabAuth
 *
 * @category   Kebab (kebab-reloaded)
 * @package    Application
 * @subpackage Plugins
 * @author	   Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 */
class Plugin_KebabAuth extends Kebab_Controller_Plugin_Abstract
{
    
    public function __construct()
    {
        parent::__construct(__CLASS__, __FILE__);
    }

    /**
     * preDispatch
     * 
     * @param Zend_Controller_Request_Abstract $request
     * @return void
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        // Check Auth
        if($this->_isCheckAuth($request)) {
            if (!Zend_Auth::getInstance()->hasIdentity()) {
                //KBBTODO get these form config file
                $request->setModuleName('default');
                $request->setControllerName('error');
                $request->setActionName('unauthorized');
            }
        }
    }

    /**
     * Check the request will check from auth plugin
     * 
     * @param Zend_Controller_Request_Abstract $request
     * @return bool
     */
    private function _isCheckAuth(Zend_Controller_Request_Abstract $request)
    {
        // Default Return Value
        $isCheckAuth = true;

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
            $isCheckAuth = false;
        }

        return $isCheckAuth;
    }

}