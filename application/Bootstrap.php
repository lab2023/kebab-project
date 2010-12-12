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

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    private $_config;
    private $_logger;

    /*
     * Config Initialization
     */
    protected function _initConfig()
	{
        $config = new Zend_Config($this->getOptions(), true);
        Zend_Registry::set('config', $this->_config = $config);
    }

    /*
     * Logger Initialization
     */
    protected function _initLogging()
	{
	    $logger = new Zend_Log();

	    //Empty Writer
        $logger->addWriter(
            new Zend_Log_Writer_Null()
        );
        
        if($this->_config->kernel->logger->use) {
            //Stream Writer
            if($this->_config->kernel->logger->stream->use) {
                $logger->addWriter(
                    new Zend_Log_Writer_Stream(
                        CORE_PATH . '/' .
                        $this->_config->kernel->logger->folder. '/' .
                        $this->_config->kernel->logger->file
                    )
                );
            }
            //Firebug Writer
            if($this->_config->kernel->logger->firebug->use) {
                $logger->addWriter(
                    new Zend_Log_Writer_Firebug()
                );
            }
        }

        Zend_Registry::set('logger', $this->_logger = $logger);

        // Info Log
        $this->_logger->log(
            'Logging initialized...',
            Zend_Log::INFO
        );
	}

    public function _initDoctrine()
    {
        $this->getApplication()->getAutoloader()
                               ->pushAutoloader(array('Doctrine', 'autoload'));
        spl_autoload_register(array('Doctrine', 'modelsAutoload'));
        
        $manager = Doctrine_Manager::getInstance();
        $manager->setAttribute(Doctrine::ATTR_AUTO_ACCESSOR_OVERRIDE, true);
        $manager->setAttribute(
            Doctrine::ATTR_MODEL_LOADING,
            Doctrine::MODEL_LOADING_CONSERVATIVE
        );
        $manager->setAttribute(Doctrine::ATTR_AUTOLOAD_TABLE_CLASSES, true);

        Doctrine::loadModel($this->_config->database->doctrine->models_path);

        $conn = Doctrine_Manager::connection($this->_config->database->doctrine->dsn, 'doctrine');
        $conn->setAttribute(Doctrine::ATTR_USE_NATIVE_ENUM, true);
        
        return $conn;
    }
}
