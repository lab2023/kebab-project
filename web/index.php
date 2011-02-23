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
 * @package    Web
 * @subpackage Bootstrap
 * @author	   lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */

/**
 * System Bootstrapping File
 *
 * @category   Kebab (kebab-reloaded)
 * @package    Web
 * @subpackage Bootstrap
 * @author	   lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */

/*
 * Kebab System Settings
 */
include '../application/configs/system.php';
// -----------------------------------------------------------------------------

/*
 * Setup Defines
 */
defined('BASE_PATH')        || define('BASE_PATH', realpath(dirname(__FILE__) . '/../') . '/');
defined('APPLICATION_ENV')  || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : $env));
defined('APPLICATION_PATH') || define('APPLICATION_PATH', BASE_PATH . $paths['app']);
defined('MODULES_PATH')     || define('MODULES_PATH', APPLICATION_PATH . '/' . $paths['mod']);
defined('LIBRARY_PATH')     || define('LIBRARY_PATH', BASE_PATH . '/' . $paths['lib']);
defined('DEVELOPER_PATH')   || define('DEVELOPER_PATH', BASE_PATH . '/' . $paths['dev']);
defined('WEB_PATH')         || define('WEB_PATH', BASE_PATH . $paths['web']);
defined('BASE_URL')         || define('BASE_URL', $baseUrl);
defined('IS_CLI')           || define('IS_CLI', false);
// -----------------------------------------------------------------------------

/*
 * Ensure library is on include_paths
 */
set_include_path(implode(PATH_SEPARATOR,
    array(
        realpath(BASE_PATH . $paths['lib']),
        get_include_path()
    ))
);
// -----------------------------------------------------------------------------

/*
 * Setup Config File Path
 */
foreach($cfgs as $key => $value) {
    $configs[$key] = APPLICATION_PATH . '/configs/' . $value;
}
// -----------------------------------------------------------------------------

/*
 * Zend_Application
 */
require_once 'Zend/Application.php';
$app = new Zend_Application(
    APPLICATION_ENV,
    array('config' => $configs)
);
$app->bootstrap()->run();
// -----------------------------------------------------------------------------
