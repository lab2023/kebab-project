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

// Begin Script Executing Time
$scriptTimeStart = microtime(true);

//Application Environments
$envs = array(
    'prod'  => 'production',
    'stage' => 'staging',
    'test'  => 'testing',
    'dev'   => 'development',
    'cli'   => 'doctrinecli'
);
$env = $envs['dev'];

//Application Folders & Paths
$path = array(
    'app'   => 'kernel', // zf like application
    'core'  => 'core',
    'pub'   => 'public', // zf like public
    'mod'   => 'applications', // zf like modules
    'lib'   => 'libraries', // zf like library
    'conf'  =>  array(
        'path'  => 'configs',
        'files' => array(
            'kernel'    => 'kernel.ini',
            'global'    => 'global.ini',
            'database'  => 'database.ini'
         )
    )
);