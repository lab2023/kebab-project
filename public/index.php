<?php
$starttime = microtime();
$startarray = explode(" ", $starttime);
$starttime = $startarray[1] + $startarray[0];

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
    'app'   => 'app',
    'core'  => 'core',
    'pub'   => 'public',
    'mod'   => 'modules',
    'lib'   => 'libraries',
    'conf'  =>  array(
        'folder'    => 'configs',
        'file'      => 'application.ini'
    )
);

//Setup Defines
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : $env));
defined('ENV_PROD')
    || define('ENV_PROD', $envs['prod']);
defined('ENV_STAGE')
    || define('ENV_STAGE', $envs['stage']);
defined('ENV_TEST')
    || define('ENV_TEST', $envs['test']);
defined('ENV_DEV')
    || define('ENV_DEV', $envs['dev']);
defined('BASE_PATH')
    || define('BASE_PATH', str_replace($path['pub'], '', realpath(dirname(__FILE__))));
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

// Zend_Application
require_once 'Zend/Application.php';
$app = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/' . $path['conf']['folder'] . '/' . $path['conf']['file']
);
$app->bootstrap()->run();

$endtime = microtime();
$endarray = explode(" ", $endtime);
$endtime = $endarray[1] + $endarray[0];
$totaltime = $endtime - $starttime;
$totaltime = round($totaltime,5);
echo "<em>Page rendered in $totaltime seconds</em>";
