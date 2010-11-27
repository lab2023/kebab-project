<?php if ( ! defined('BASE_PATH')) exit('No direct script access allowed');

class Default_Bootstrap extends Zend_Application_Module_Bootstrap
{
    public function  __construct($application) {
        // Info Log
        Zend_Registry::get('logger')->log(
            'Default_Bootstrap initialized...',
            Zend_Log::INFO
        );
    }
    
    protected function _initConfig()
    {
        $config = new Zend_Config_Ini(
            dirname(__FILE__) . '/configs/module.ini', 
            APPLICATION_ENV
        );
        return $config;
    }
    
    protected function _initAutoload() {
        $moduleLoader = new Zend_Application_Module_Autoloader(
            array(
              'namespace' => 'App',
              'basePath' => __DIR__
            )
        );
    }
}

