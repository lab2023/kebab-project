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
 * Kebab Controller Auth Plugin

 * @category   Kebab (kebab-reloaded)
 * @package    Kebab_Controller
 * @subpackage Kebab_Controller_Plugin
 * @author     lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */

class System_Plugin_KebabAuth extends Zend_Controller_Plugin_Abstract
{

    public function __construct() 
    {
        Zend_Registry::get('logging')->log(
            __CLASS__ . 'initilizated...',
            Zend_Log::INFO
        );
    }
    
    
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {      
        
    }
    
    private function _isExclude($module, $controller, $action) 
    {
        $retVal = false;
        $config = new Zend_Config_Ini(
            SYSTEM_PATH . '/plugins/Auth/config.ini',
            APPLICATION_ENV
        );
        
        (array) $configArray = $config->toArray();
        (array) $excludeArray = $configArray['exclude'];
        
        if (in_array($module, $excludeArray)) {
            if (in_array($controller, $excludeArray[$module])) {
                if (in_array($action, $excludeArray[$module][$controller])) {
                    $retVal = true;
                }
            }
        }
        
        return $retVal;        
    }    
}