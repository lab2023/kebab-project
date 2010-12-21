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
 * @category   Kebab (kebab-reloaded)
 * @package    Developers
 * @subpackage Scripts
 * @author	   lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */

/**
 * Doctrine CLI Bootstrapping File
 *
 * @category   Kebab (kebab-reloaded)
 * @package    Developer
 * @subpackage Scripts
 * @author	   lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */

// Kebab System Settings
include '../../system/system.php';

//Setup Defines
defined('APPLICATION_ENV')  || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'doctrineCLI'));
defined('BASE_PATH')        || define('BASE_PATH', realpath(__DIR__ . '/../../') . DIRECTORY_SEPARATOR);
defined('SYSTEM_PATH')      || define('SYSTEM_PATH', BASE_PATH . $paths['sys']);
defined('APPLICATIONS_PATH')|| define('APPLICATIONS_PATH', BASE_PATH .$paths['app']);
defined('DEVELOPER_PATH')   || define('DEVELOPER_PATH', BASE_PATH .$paths['dev']);

// Ensure library/ is on include_paths
set_include_path(implode( PATH_SEPARATOR,
    array(
        realpath(BASE_PATH . $paths['lib']),   // 3rd party libraries
        realpath(SYSTEM_PATH . DIRECTORY_SEPARATOR . $paths['lib']), // system libraries
        get_include_path()
    ))
);

// Setup Config File Path
foreach($cfgs as $key => $value) {
    $configs[$key] = SYSTEM_PATH . DIRECTORY_SEPARATOR . 'configs' . DIRECTORY_SEPARATOR . $value;
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