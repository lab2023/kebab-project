<?php
/**
 * Kebab Project
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
 * @subpackage
 * @author       lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 -
 *             internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */

/**
 * Kebab Project Asset Bootstrapper
 *
 * On-Demad load and return all js files
 * @uses SPL - Standard PHP Library :: RecursiveIteratorIterator, RecursiveDirectoryIterator
 */
if (phpversion() < 5) exit('/* Kebab Project Asset Bootstrapper requires PHP5 or greater. */');

ob_start("ob_gzhandler");

header("Content-type: text/javascript; charset: UTF-8");

// Define root path
defined('BASE_PATH') || define('BASE_PATH', realpath(dirname(__FILE__)) . '/');

// Load index.js once !important
@readfile(BASE_PATH . 'web/index.js');
print PHP_EOL;

// Recursive scan directory and files
$scanner = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(BASE_PATH));

while ($scanner->valid()) {

    if (!$scanner->isDot() && $scanner->isFile()) {

        $pathInfo = pathinfo($scanner->key());

        if (@$pathInfo['extension'] == 'js' && $scanner->getFilename() !== 'index.js') {
            @readfile($scanner->key());
            print PHP_EOL;
        }
    }

    $scanner->next();
}


ob_end_flush();