<?php if ( ! defined('BASE_PATH')) exit('No direct script access allowed');

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    private $_config;
    private $_logger;
    private $_database;

    /*
     * Config Initialization
     */
    protected function _initConfig()
	{
        $config = new stdClass();
        $config->application = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
        $config->default = new Zend_Config_Ini(APPLICATION_PATH . '/configs/default.ini', APPLICATION_ENV);
        $config->database = new Zend_Config_Ini(APPLICATION_PATH . '/configs/database.ini', APPLICATION_ENV);
        
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

        if($this->_config->default->logger->use) {
            //Stream Writer
            if($this->_config->default->logger->stream->use) {
                $logger->addWriter(
                    new Zend_Log_Writer_Stream(
                        CORE_PATH . '/' .
                        $this->_config->default->logger->folder. '/' .
                        $this->_config->default->logger->file
                    )
                );
            }
            //Firebug Writer
            if($this->_config->default->logger->firebug->use) {
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

    /*
     * Database Initialization
     */
    protected function _initDatabase()
	{
        if($this->_config->database->connection->init) {
            
            $group = "default";

            $params = array(
                "dbname"	=> $this->_config->database->connection->$group->dbname,
                "username"	=> $this->_config->database->connection->$group->username,
                "password"	=> $this->_config->database->connection->$group->password,
                "host"		=> $this->_config->database->connection->$group->host,
                "charset"	=> $this->_config->database->connection->$group->charset
            );
            $database = Zend_Db::factory(
                $this->_config->database->connection->default->adapter,
                $params
            );

            if($this->_config->database->profiler->firebug->enable) {
                $profiler = new Zend_Db_Profiler_Firebug('All Queries');
                $profiler->setEnabled(true);
                $database->setProfiler($profiler);
            }
            Zend_Registry::set('database', $this->_database = $database);

            // Info Log
            $this->_logger->log(
                'Database initialized...',
                Zend_Log::INFO
            );
        }
	}
}
