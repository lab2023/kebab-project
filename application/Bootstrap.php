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
 * @package    System
 * @subpackage 
 * @author	   lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - 
 *             internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */

/**
 * Kebab Application Bootstrapping Class
 *
 * @category   Kebab (kebab-reloaded)
 * @package    System
 * @subpackage 
 * @author	   lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 -
 *             internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    /**
     * Configuration variable
     *
     * @var Zend_Config
     */
    private $_config = null;
    /**
     * Logging variable
     *
     * @var Zend_Log
     */
    private $_logging = null;

    /**
     * Config Initialization
     *
     * @return Zend_Config
     */
    protected function _initConfig()
    {
        $config = new Zend_Config($this->getOptions(), true);
        Zend_Registry::set('config', $this->_config = $config);

        return $config;
    }

    /**
     * Logging Initialization
     * KBBTODO:Move to application.ini
     * @return void
     */
    protected function _initLogging()
    {
        $this->_logging = new Zend_Log();

        if ($this->_config->kebab->logging->enable) {
            //Stream Writer
            if ($this->_config->kebab->logging->stream->enable) {

                $logFile = APPLICATION_PATH . '/variables/logs/application.log';
                $streamWriter = new Zend_Log_Writer_Stream($logFile);
                $this->_logging->addWriter($streamWriter);
            }
            //Firebug Writer
            if ($this->_config->kebab->logging->firebug->enable) {

                $firebugWriter = new Zend_Log_Writer_Firebug();
                //$firebugWriter->setFormatter(new Zend_Log_Formatter_Simple());               
                $this->_logging->addWriter($firebugWriter);
            }
        } else {
            //Empty Writer
            $nullWriter = new Zend_Log_Writer_Null();
            $this->_logging->addWriter($nullWriter);
        }

        Zend_Registry::set('logging', $this->_logging);

        // Info Log
        $this->_logging->log('Logging initialized...', Zend_Log::INFO);

        return $this->_logging;
    }

    /**
     * Plugin Initializer
     * 
     * @return string
     */
    public function _initPlugins()
    {
        // Info Log
        $this->_logging->log('Plugins initialized...', Zend_Log::INFO);

        $plugins = $this->_config->plugins ? $this->_config->plugins->toArray() : array();

        if (count($plugins) > 0) {
            $this->bootstrap('frontController');
            $front = $this->getResource('frontController');

            foreach ($plugins as $plugin) {
                $front->registerPlugin(new $plugin());
            }
        }

        return $plugins;
    }

    /**
     * Doctrine Initialization
     *
     * @return Doctrine_Manager
     */
    public function _initDoctrine()
    {
        // Info Log
        $this->_logging->log('Doctrine initialized...', Zend_Log::INFO);

        $this->getApplication()->getAutoloader()->pushAutoloader(array('Doctrine', 'autoload'));

        $manager = Doctrine_Manager::getInstance();
        $manager->setAttribute(Doctrine::ATTR_AUTO_ACCESSOR_OVERRIDE, true);
        $manager->setAttribute(Doctrine::ATTR_MODEL_LOADING, IS_CLI ? 1 : 2);
        $manager->setAttribute(Doctrine::ATTR_AUTOLOAD_TABLE_CLASSES, true);
        $manager->setAttribute(Doctrine_Core::ATTR_USE_DQL_CALLBACKS, true);

        $connection = $manager->connection(
                $this->_config->database->doctrine->connections->master->dsn,
                $this->_config->database->doctrine->connections->master->name
        );
        $connection->setAttribute(Doctrine::ATTR_USE_NATIVE_ENUM, true);

        if ($this->_config->database->doctrine->profiling->enable) {
            $connection->setListener(new Imind_Profiler_Doctrine_Firebug('Doctrine Profiler'));
        }

        if (!IS_CLI) {
            $connection->setCollate($this->_config->database->doctrine->connections->master->collate);
            $connection->setCharset($this->_config->database->doctrine->connections->master->charset);

            // Caching Enabled ?
            if ($this->_config->database->doctrine->caching->enable) {

                // Select cache driver
                $cacheDriver = $this->_config->database->doctrine->caching->driver == 'Apc' ?
                    new Doctrine_Cache_Apc(array('connection' => $connection)) :
                    new Doctrine_Cache_Memcache(array('connection' => $connection));

                // Use Query Caching
                if ($this->_config->database->doctrine->caching->query)
                    $connection->setAttribute(Doctrine::ATTR_QUERY_CACHE, $cacheDriver);
                // Use Result Caching
                if ($this->_config->database->doctrine->caching->result->enable)
                    $connection->setAttribute(Doctrine::ATTR_RESULT_CACHE, $cacheDriver);
            }
        }

        return $connection;
    }

    /**
     * Zend Translate Initialization
     * 
     * First we look the user identity if null, we look the default language from languages.ini
     * 
     * @return Zend_Translate 
     */
    public function _initTranslator()
    {      
        // Info Log
        $this->_logging->log('Translator initialized...', Zend_Log::INFO);

//        $defaultLanguage = Zend_Auth::getInstance()->hasIdentity()
//                           ? Zend_Auth::getInstance()->getIdentity()->language
//                           : $this->_config->languages->default;
        $defaultLanguage = 'en';

        $languagePath    = $this->_config->languages->languagePath . '/' . $defaultLanguage . '.php';
        $translator = new Zend_Translate('array', $languagePath, $defaultLanguage);
        
        Zend_Registry::set('translator', $translator);
        
        return $translator;
    }

    /**
     *
     */
    protected function _initRestRoute()
    {
        // Info Log
        $this->_logging->log('RestRoute initialized...', Zend_Log::INFO);

        $front       = Zend_Controller_Front::getInstance();
        $restRoute   = new Zend_Rest_Route($front, array(),
            array(
                 'access'=> array('user-role'),
                 'authentication'=> array('session', 'password', 'forgot-password'),
                 'feedback'=> array('feedback', 'feedback-manager'),
                 'user'=> array('profile', 'manager')
            )
        );
        $front->getRouter()->addRoute('rest', $restRoute);
    }

}
