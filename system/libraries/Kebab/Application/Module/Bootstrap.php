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
 * @category   Kebab (kebab-reloaded)
 * @package    Kebab_Application
 * @subpackage Module
 * @author	   lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */

/**
 * Application Module Bootstrapping Abstract Class
 * This class contains common module bootstrapping operations
 *
 * @category   Kebab (kebab-reloaded)
 * @package    Kebab
 * @subpackage Application_Module_Bootstrap
 * @author	   lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */
abstract class Kebab_Application_Module_Bootstrap
    extends Zend_Application_Module_Bootstrap
{
    /**
     * Module name variable
     * @var string
     * @access protected
     */
    protected $_moduleName = null;

    /**
     * Initialize class and log debug message
     * 
     * @return void
     * @access protected
     */
    protected function _initBootstrap()
    {
        $this->_moduleName = $this->getModuleName();
        
        // Info Log
        Zend_Registry::get('logging')->log(
            $this->_moduleName . ' initialized...',
            Zend_Log::INFO
        );
    }

    /**
     * Initialize module config options
     * 
     * @return void
     */
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
        Zend_Registry::get('logging')->log(
            $this->getOptions(),
            Zend_Log::INFO
        );
    }

    /**
     * Convert camelize string to sluggable
     * (etc. blahBlah -> blah-blah)
     *
     * @param array $str
     * @param string $separator
     * @return string
     */
    private function _camelize($str, $separator = '-')
    {
        $str[0] = strtolower($str[0]);
        $func = create_function('$c', 'return "' . $separator . '" . strtolower($c[1]);');
        return preg_replace_callback('/([A-Z])/', $func, $str);
    }

}
