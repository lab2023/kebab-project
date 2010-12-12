<?php
//Application Environments
$envs = array(
    'prod'  => 'production',
    'stage' => 'staging',
    'test'  => 'testing',
    'dev'   => 'development'
);
$env = $envs['dev'];

//Application Folders & Paths
$path = array(
    'app'   => 'application',
    'core'  => 'core',
    'pub'   => 'public',
    'mod'   => 'modules',
    'lib'   => 'libraries',
    'conf'  =>  array(
        'folder' => 'configs',
        'files' => array(
            'application' => 'application.ini',
            'database' => 'database.ini',
            'default' => 'kernel.ini'
         )
    )
);

defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : $env));
defined('BASE_PATH')
    || define('BASE_PATH', realpath(dirname(__FILE__) . '/../../') . '/');
defined('CORE_PATH')
    || define('CORE_PATH', BASE_PATH . $path['core']);
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', BASE_PATH . $path['app']);
defined('MODULES_PATH')
    || define('MODULES_PATH', APPLICATION_PATH . '/' .$path['mod']);

// Ensure library/ is on include_paths
set_include_path(
    implode(
        PATH_SEPARATOR,
        array(
            realpath(CORE_PATH . '/' . $path['lib']),
            realpath(APPLICATION_PATH . '/' . $path['lib']),
            get_include_path()
        )
    )
);

// Setup Config File Path
foreach($path['conf']['files'] as $key => $value) {
    $configs[$key] = APPLICATION_PATH
        . '/' . $path['conf']['folder']
        . '/' . $value;
}

// Zend_Application
require_once 'Zend/Application.php';
$app = new Zend_Application(
    APPLICATION_ENV,
    array('config' => $configs)
);
$app->getBootstrap()->bootstrap();

$dbConfig = $app->getOption('database');
$config = $dbConfig['doctrine'];

$cli = new Doctrine_Cli($config);
$cli->run($_SERVER['argv']);