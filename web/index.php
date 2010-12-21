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

// Kebab System Settings
include '../system/system.php';

//Setup Defines
defined('APPLICATION_ENV')  || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : $env));
defined('BASE_PATH')        || define('BASE_PATH', realpath(__DIR__ . '/../') . DIRECTORY_SEPARATOR);
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
$app->bootstrap()->run();

if(APPLICATION_ENV != 'production')
{
    $scriptTime = number_format((microtime(true) - $scriptTimeStart), 5,'.','.');
    $mgpu = number_format(memory_get_peak_usage(true));
    echo "<p title='Kebab Reloaded Debug &amp; Profiling Area' 
        style='position:absolute; border-top:1px solid silver;
        text-align:center; font-style:italic; background:#eee; margin:0;
        padding:3px 0; bottom:0; left:0%; width:100%'>
        <strong>Page rendered time :</strong> " . $scriptTime . " seconds
        | <strong>Memory peak usage :</strong> ".$mgpu." bytes
        | <strong>Application environment: </strong> {".APPLICATION_ENV."}</p>";
}