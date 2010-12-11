<?php
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
$app->bootstrap()->run();

$endtime = microtime();
$endarray = explode(" ", $endtime);
$endtime = $endarray[1] + $endarray[0];
$totaltime = $endtime - $starttime;
$totaltime = round($totaltime,5);
echo "<em>Page rendered in $totaltime seconds</em>";