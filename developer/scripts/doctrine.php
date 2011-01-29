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

/*
 *  Kebab System Settings
 */
include '../../system/configs/system.php';
// -----------------------------------------------------------------------------

/*
 * Setup Defines
 */
defined('APPLICATION_ENV')  || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : $env));
defined('BASE_PATH')        || define('BASE_PATH', realpath(__DIR__ . '/../../') . '/');
defined('SYSTEM_PATH')      || define('SYSTEM_PATH', BASE_PATH . $paths['sys']);
defined('APPLICATIONS_PATH')|| define('APPLICATIONS_PATH', BASE_PATH . $paths['app']);
defined('DEVELOPER_PATH')   || define('DEVELOPER_PATH', BASE_PATH . $paths['dev']);
defined('SUBDOMAINS_PATH')  || define('SUBDOMAINS_PATH', BASE_PATH . $paths['dns']);
defined('WEB_PATH')         || define('WEB_PATH', BASE_PATH . $paths['pub']);
defined('BASE_URL')         || define('BASE_URL', $baseUrl);
defined('IS_CLI')           || define('IS_CLI', true);
// -----------------------------------------------------------------------------

/*
 *  Ensure library/ is on include_paths
 */
set_include_path(implode( PATH_SEPARATOR,
    array(
        realpath(BASE_PATH . $paths['lib']),   // 3rd party libraries
        realpath(SYSTEM_PATH . '/' . $paths['lib']), // system libraries
        get_include_path()
    ))
);
// -----------------------------------------------------------------------------

/*
 *  Setup Config File Path
 */
foreach($cfgs as $key => $value) {
    $configs[$key] = SYSTEM_PATH . '/configs/' . $value;
}
// -----------------------------------------------------------------------------

/*
 *  Zend_Application
 */
require_once 'Zend/Application.php';
$app = new Zend_Application(
    APPLICATION_ENV,
    array('config' => $configs)
);
$app->bootstrap();
// -----------------------------------------------------------------------------

/*
 *  Default Cli Settings
 */
$cfg = new Zend_Config_Ini(
        DEVELOPER_PATH . '/scripts/configs.ini' ,
        'doctrineCli'
);
$doctrineCliCfgs = $cfg->database->doctrine->toArray();
// -----------------------------------------------------------------------------

/*
 *  Cli Arguments
 */
$argv = $_SERVER["argv"];
// -----------------------------------------------------------------------------

/*
 *  Module Parameters and checkings
 *  KBBTODO: Move this operations Kebab_Tool
 *  Usage:
 *  ./doctrine original-doctrine-command -module path-to-your-module
 */
// Module name argument
$moduleNameIndex = array_search('-module', $argv);

if ($moduleNameIndex !== false) {   // Module argument is set

    // Check & Access Module Name
    $moduleName = isset($argv[$moduleNameIndex + 1]) ? $argv[$moduleNameIndex + 1] : null;

    if (!empty($moduleName)) { // Module name is not empty and already exist

        if (!is_dir(APPLICATIONS_PATH . "/" . $moduleName)) {
            die("** Module path not found! **" . PHP_EOL);
        }
        
        // Fiter SeperatorToCamelCase Setup
        $filter = new Zend_Filter_Word_UnderscoreToCamelCase();
        $camelCaseModuleName = $filter->setSeparator('-')->filter($moduleName);

        // Replacement Mapping, String Replace and Apply Filter
        $mapping = array(
            'find'      => array(   // Original
                SYSTEM_PATH,    // models path
                DEVELOPER_PATH, // data_fixtures, sql, migrations, yaml_schema paths
                "System_Model_" // generate_models_options.classPrefix
            ),
            'replace'   => array(   // Replaced
                APPLICATIONS_PATH . "/" . $moduleName,
                APPLICATIONS_PATH . "/" . $moduleName . "/" . $paths['dev'],
                $camelCaseModuleName . "_Model_"
            )
        );
        
        // Apply New Cli Settings
        $doctrineCliCfgs = str_replace(
            $mapping['find'],
            $mapping['replace'],
            array_merge( // Simple recursive replacing and merging
                $doctrineCliCfgs,
                array('generate_models_options' => str_replace(
                    $mapping['find'],
                    $mapping['replace'],
                    $doctrineCliCfgs['generate_models_options']
                ))
            )
        );

        echo "** '" . $moduleName ."' module is being processed... **" . PHP_EOL;
        
    } else {
        //KBBTODO: Add console code color
        die("** Please type your module path! **"
            . PHP_EOL
            . "~$>./doctrine original-doctrine-command -module path-to-your-module"
            . PHP_EOL);
    }
}
// -----------------------------------------------------------------------------

// Cli Initialize
$cli = new Doctrine_Cli($doctrineCliCfgs);
$cli->run($argv);
// -----------------------------------------------------------------------------