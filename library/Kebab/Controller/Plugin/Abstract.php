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
 * @package    Kebab_Controller
 * @subpackage Kebab_Controller_Plugin
 * @author     lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */
abstract class Kebab_Controller_Plugin_Abstract extends Zend_Controller_Plugin_Abstract
{
    protected $_pluginPath;
    protected $_pluginConfig;

    public function __construct($class, $file)
    {
        Zend_Registry::get('logging')->log(
            $class . ' Initialized...', Zend_Log::INFO
        );

        $pluginPath = substr($file, 0, strpos($file, '.'));
        if (is_dir($pluginPath)) {

            $this->_pluginPath = $pluginPath;

            if (file_exists($this->_pluginPath . '/config.ini')) {
                $this->_pluginConfig = new Zend_Config_Ini(
                        $this->_pluginPath . '/config.ini',
                        APPLICATION_ENV
                );
            } else {
                throw new Zend_Exception($class . ' config file not found!');
            }
        }
    }

}


