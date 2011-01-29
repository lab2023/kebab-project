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

        //Empty Writer
        $this->_logging->addWriter(
            new Zend_Log_Writer_Null()
        );

        if ($this->_config->kebab->logging->enable) {
            //Stream Writer
            if ($this->_config->kebab->logging->stream->enable) {
                $this->_logging->addWriter(
                    new Zend_Log_Writer_Stream(
                        SYSTEM_PATH . '/variables/logs/system.log'
                    )
                );
            }
            //Firebug Writer
            if ($this->_config->kebab->logging->firebug->enable) {
                $this->_logging->addWriter(
                    new Zend_Log_Writer_Firebug()
                );
            }
        }

        Zend_Registry::set('logging', $this->_logging);

        // Info Log
        $this->_logging->log(
            'Logging initialized...',
            Zend_Log::INFO
        );

        return $this->_logging;
    }
    
    /**
     * Subdomain Initialization
     *
     * @return string
     */
    protected function _initSubdomain()
    {
        $subdomain = array();

        if ($this->_config->kebab->subdomain->enable) {

            // Info Log
            $this->_logging->log(
                'Subdomain initialized...',
                Zend_Log::INFO
            );

            $host = explode('.', @$_SERVER['HTTP_HOST'], 2);
            $subdomain['name'] = $host[0];

            $subdomain['path'] = SUBDOMAINS_PATH . '/' . $subdomain['name'];
            $subdomain['config']['file'] = SUBDOMAINS_PATH . '/' . $subdomain['name'] . '/configs.ini';

            if (file_exists($subdomain['config']['file'])) {
                
                $subdomain['config']['options'] = new Zend_Config_Ini(
                    $subdomain['config']['file'], APPLICATION_ENV
                );
                
                // Adding
                $this->setOptions(array('subdomain' => $subdomain));
                // Merging
                $this->setOptions($subdomain['config']['options']->toArray());

                // ReInit Config
                $this->_initConfig();

                // Info Log
                $this->_logging->log(
                    $subdomain . ' Subdomain  binded...',
                    Zend_Log::INFO
                );
            }
            /*
             * KBBTODO: Check reserved subdomains and
             * show subdomains not found error page
             * Throw exception "Subdomain settings not loading. Subdomain config file not found!"
             * Merge two option set method
             */
        }

        return $subdomain;
    }

    /**
     * Doctrine Initialization
     *
     * @return Doctrine_Manager
     */
    public function _initDoctrine()
    {
        $this->getApplication()->getAutoloader()
                                ->pushAutoloader(array('Doctrine', 'autoload'));

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

        // Info Log
        $this->_logging->log(
            'Doctrine initialized...',
            Zend_Log::INFO
        );

        return $connection;
    }

    /**
     * Translation Initialization
     * @return Zend_Translate
     */
    protected function _initTranslation()
    {
        $this->bootstrap('translate');
        $translate = $this->getResource('translate');
        $translate->setOptions(
            array(
                'log' => Zend_Registry::get('logging'),
                //KBBTODO getLocale from session
                'locale' => 'auto'
            )
        );
        Zend_Registry::set('translate', $translate);

        return $translate;
    }
}
