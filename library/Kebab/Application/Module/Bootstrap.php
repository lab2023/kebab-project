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
 * @category   Kebab (kebab-reloaded)
 * @package    Kebab_Application
 * @subpackage Module
 * @author     Tayfun Öziş ERİKAN <tayfun.ozis.erikan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */

/**
 * Application Module Bootstrapping Abstract Class
 * This class contains common module bootstrapping operations
 *
 * @category   Kebab (kebab-reloaded)
 * @package    Kebab
 * @subpackage Application_Module_Bootstrap
 * @author     Tayfun Öziş ERİKAN <tayfun.ozis.erikan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */
abstract class Kebab_Application_Module_Bootstrap extends Zend_Application_Module_Bootstrap
{
    /**
     * Module name variable
     * @var string
     * @access protected
     */
    protected $_module = array();

    /**
     * Initialize class and log debug message
     * 
     * @return void
     * @access protected
     */
    protected function _initBootstrap()
    {
        // Fiter SeperatorToCamelCase Setup
        $filter = new Zend_Filter_Word_CamelCaseToSeparator('-');
        
        $this->_module['class'] = $this->getModuleName();
        $this->_module['folder']  = strtolower(
            $filter->filter($this->_module['class'])
        );
        
        // Info Log
        Zend_Registry::get('logging')->log(
            $this->_module['class'] . ' bootstrap initialized...',
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
            MODULES_PATH . '/' . $this->_module['folder'] . '/configs/module.ini',
            APPLICATION_ENV
        );

        $this->setOptions($config->toArray());
    }

    /**
     * Initialize module translations
     *
     * @return void
     */
    protected function _initTranslator()
    {
        $bootstrap = $this->getApplication()->bootstrap('translator');
        $translator = $bootstrap->getResource('translator');

        try {
            $locale = $translator->getLocale();
            $translations = MODULES_PATH . '/' . $this->_module['folder'] . '/languages/' . $locale . '.php';
            $translator->addTranslation(new Zend_Translate('array', $translations, $locale));
        } catch (Zend_Exception $e) {
            // Error Log
            Zend_Registry::get('logging')->log(
                $this->_module['class']  . ' module translations not loading...',
                Zend_Log::ERR
            );
            throw new Zend_Translate_Exception($this->_module['class']  . ' module translations not loading...');
        }
    }
}
