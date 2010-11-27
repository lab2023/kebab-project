<?php if ( ! defined('BASE_PATH')) exit('No direct script access allowed');

class Super_Bootstrap extends Zend_Application_Module_Bootstrap
{
    public function  __construct($application) {
        // Info Log
        Zend_Registry::get('logger')->log(
            'Super_Bootstrap initialized...',
            Zend_Log::INFO
        );
    }
    
    protected function _initAutoload() {
        $moduleLoader = new Zend_Application_Module_Autoloader(
            array(
              'namespace' => '',
              'basePath' => __DIR__
            )
        );
    }
}