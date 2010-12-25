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
 * @package    System
 * @subpackage Bootstrap
 * @author	   lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */

/**
 * System Bootstrapping Configurations File
 *
 * @category   Kebab (kebab-reloaded)
 * @package    System
 * @subpackage Bootstrap
 * @author	   lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */

/*
 * Set Script Executing Start Time
 */
$scriptTimeStart = microtime(true);

/*
 * Application Environments
 */
$envs = array(
    'prod'  => 'production',
    'stage' => 'staging',
    'test'  => 'testing',
    'dev'   => 'development',
    'cli'   => 'doctrinecli'
);
$env = $envs['dev'];

/*
 * Application Folders & Paths
 */
$paths = array(
    'sys'   => 'system',        // system folder name
    'app'   => 'applications',  // modules folder name
    'dev'   => 'developer',     // modules folder name
    'lib'   => 'libraries',     // libraries folder name
    'pub'   => 'web'            // public folder name
);

/*
 * Application Configs
 */
$cfgs = array(
    'application'   => 'application.ini',
    'database'      => 'database.ini',
    'global'        => 'global.ini',
);