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
     * Subdomain Initialization
     * @return string
     */
    protected function _initSubdomain()
    {
        $subdomain = null;
        $config = $this->getOption('kebab');

        if ($config['subdomain']['use']) {

            $host = explode('.', @$_SERVER['HTTP_HOST'], 2);
            $subdomainName = $host[0];

            $subdomainDir = glob(SUBDOMAINS_PATH . '/' . $subdomainName . '/*.ini');

            if (count($subdomainDir) > 0) {
                $subdomainCfg = new Zend_Config_Ini($subdomainDir[0], APPLICATION_ENV);
                $this->setOptions($subdomainCfg->toArray());
            }
            /*
             * KBBTODO: Check reserved subdomains and
             * show subdomains not found error page
             */
        }

        return $subdomain;
    }
    
    /*
     * Config Initialization
     * @return Zend_Config
     */
    protected function _initConfig()
	{
        $config = new Zend_Config($this->getOptions(), true);
        Zend_Registry::set('config', $this->_config = $config);

        return $config;
    }

    /*
     * Logging Initialization
     * @return void
     */
    protected function _initLogging()
	{
	    $this->_logging = new Zend_Log();

	    //Empty Writer
        $this->_logging->addWriter(
            new Zend_Log_Writer_Null()
        );
        
        if ($this->_config->kebab->logging->use) {
            //Stream Writer
            if($this->_config->kebab->logging->stream->use) {
                $this->_logging->addWriter(
                    new Zend_Log_Writer_Stream(
                        SYSTEM_PATH . '/variables/logs/kernel.log'
                    )
                );
            }
            //Firebug Writer
            if ($this->_config->kebab->logging->firebug->use) {
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
	}

    /*
     * Doctrine Initialization
     * @return Doctrine_Manager
     */
    public function _initDoctrine()
    {
        $this->getApplication()->getAutoloader()
                               ->pushAutoloader(array('Doctrine', 'autoload'));
        spl_autoload_register(array('Doctrine', 'modelsAutoload'));
        
        $manager = Doctrine_Manager::getInstance();
        $manager->setAttribute(Doctrine::ATTR_AUTO_ACCESSOR_OVERRIDE, true);
        $manager->setAttribute(
            Doctrine::ATTR_MODEL_LOADING,
            $this->_config->database->doctrine->model_autoloading
        );
        $manager->setAttribute(Doctrine::ATTR_AUTOLOAD_TABLE_CLASSES, true);

        Doctrine::loadModel($this->_config->database->doctrine->models_path);

        $connection = Doctrine_Manager::connection(
            $this->_config->database->doctrine->connections->master->dsn,
            $this->_config->database->doctrine->connections->master->name
        );
        $connection->setAttribute(Doctrine::ATTR_USE_NATIVE_ENUM, true);
        
        return $connection;
    }

    /*
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
