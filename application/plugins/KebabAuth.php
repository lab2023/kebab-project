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

/**
 * Plugin_KebabAuth
 *
 * @category   Kebab (kebab-reloaded)
 * @package    Application
 * @subpackage Plugins
 * @author	   lab2023 Dev Team
 */
class Plugin_KebabAuth extends Kebab_Controller_Plugin_Abstract
{

    /**
     * __construct
     * 
     * @param object Plugin_KebabAuth
     * @param file KebabAuth.php
     */
    public function __construct()
    {
        parent::__construct(__CLASS__, __FILE__);
    }

    /**
     * preDispatch 
     * 
     * @param Zend_Controller_Request_Abstract $request 
     * @throws Zend_Exception
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $filter = new Zend_Filter_Word_DashToCamelCase();
        $module = $filter->filter($request->getModuleName());
        $controller = $filter->filter($request->getControllerName());
        $resource = $module . '-' . $controller;
        
        if ($resource !== 'Default-Index'
            && $resource !== 'Default-Auth'
            && $resource !== 'Default-Error'
        ) {
            $auth = Zend_Auth::getInstance();
            if (!$auth->hasIdentity()) {
                $request->setModuleName('default');
                $request->setControllerName('auth');
                $request->setActionName('unauthorized');
            }
        }
    }

}