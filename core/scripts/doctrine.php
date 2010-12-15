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

include '../core.php';

$env = $envs['cli'];

//Setup Defines
defined('APPLICATION_ENV')  || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : $env));
defined('BASE_PATH')        || define('BASE_PATH', realpath(dirname(__FILE__) . '/../../') . DIRECTORY_SEPARATOR);
defined('CORE_PATH')        || define('CORE_PATH', BASE_PATH . $path['core']);
defined('KERNEL_PATH')      || define('KERNEL_PATH', BASE_PATH . $path['app']);
defined('APPLICATIONS_PATH')|| define('APPLICATIONS_PATH', BASE_PATH .$path['mod']);

// Ensure library/ is on include_paths
set_include_path(
    implode(
        PATH_SEPARATOR,
        array(
            realpath(CORE_PATH . DIRECTORY_SEPARATOR . $path['lib']),
            realpath(KERNEL_PATH . DIRECTORY_SEPARATOR . $path['lib']),
            get_include_path()
        )
    )
);

// Setup Config File Path
foreach($path['conf']['files'] as $key => $value) {
    $configs[$key] = KERNEL_PATH . DIRECTORY_SEPARATOR . $path['conf']['path'] . DIRECTORY_SEPARATOR . $value;
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