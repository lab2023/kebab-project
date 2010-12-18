<?php if ( ! defined('BASE_PATH')) exit('No direct script access allowed');
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
 * @category   KEBAB
 * @package    Core
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */

abstract class Kebab_Application_Module_Bootstrap
    extends Zend_Application_Module_Bootstrap
{
    protected $_moduleName = null;
    
    protected function _initBootstrap()
    {
        $this->_moduleName = $this->getModuleName();
        
        // Info Log
        Zend_Registry::get('logger')->log(
            $this->_moduleName . ' initialized...',
            Zend_Log::INFO
        );
    }

    protected function _initConfig()
    {
        
        // load ini file
        $config = new Zend_Config_Ini(
            APPLICATIONS_PATH .
            DIRECTORY_SEPARATOR . $this->_camelize($this->_moduleName) . DIRECTORY_SEPARATOR.
            'configs' . DIRECTORY_SEPARATOR . 'module.ini',
            APPLICATION_ENV
        );

        $this->setOptions($config->toArray());
        
        // Info Log
        Zend_Registry::get('logger')->log(
            $this->getOptions(),
            Zend_Log::INFO
        );
    }
    
    private function _camelize($str, $separator = '-')
    {
        $str[0] = strtolower($str[0]);
        $func = create_function('$c', 'return "' . $separator . '" . strtolower($c[1]);');
        return preg_replace_callback('/([A-Z])/', $func, $str);
    }

}
