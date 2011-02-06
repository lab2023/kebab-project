<?php

if (!defined('BASE_PATH'))
    exit('No direct script access allowed');
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
 * @package    Core
 * @subpackage Plugins
 * @author	   lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */

/**
 * Plugin_KebabDeveloperTools
 *
 * @category   Kebab (kebab-reloaded)
 * @package    Application
 * @subpackage Plugins
 * @author	   lab2023 Dev Team
 */
class Plugin_KebabAuth extends Zend_Controller_Plugin_Abstract
{
    const VERSION = "1.0.0";
    
    //KBBTODO Move this variables to Kebab_Controller_Plugin_Abstract
    protected $_pluginPath,
              $_pluginConfig,
              $_data;
    
    //KBBTODO Move this mehtod to Kebab_Controller_Plugin_Abstract
    public function __construct() 
    {
        Zend_Registry::get('logging')->log(
            __CLASS__ . ' Initialized...',
            Zend_Log::INFO
        );
        
        $pluginPath = substr(__FILE__, 0, strpos(__FILE__, '.'));        
        if (is_dir($pluginPath)) {
            
            $this->_pluginPath = $pluginPath;
            
            if (file_exists($this->_pluginPath . '/config.ini')) {
                $this->_pluginConfig = new Zend_Config_Ini(
                    $this->_pluginPath . '/config.ini',
                    APPLICATION_ENV
                );
            } else {
                throw new Zend_Exception(__CLASS__ . ' config file not found!');
            }
        }
    }
    
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {   
//        $passedRequests = $this->_pluginConfig->passedRequests->toArray();                
//        $currentRequest = $this->getRequest()->getParams();        
//        
//        if ($currentRquest != $passeedRequest)
//            die('Not authorized...');//$auth = Zend_Auth::getInstance();
    }
}