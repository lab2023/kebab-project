/* -----------------------------------------------------------------------------
 Kebab Project 1.5.x (Kebab Reloaded)
 http://kebab-project.com
 Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc.
 http://www.lab2023.com

 * LICENSE
 *
 * This source file is subject to the  Dual Licensing Model that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.kebab-project.com/cms/licensing
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@lab2023.com so we can send you a copy immediately.
 ----------------------------------------------------------------------------- */

Ext.namespace(
        'KebabOS.applications.snake',
        'KebabOS.applications.snake.application',
        'KebabOS.applications.snake.application.configs',
        'KebabOS.applications.snake.application.controllers',
        'KebabOS.applications.snake.application.languages',
        'KebabOS.applications.snake.application.layouts',
        'KebabOS.applications.snake.application.models',
        'KebabOS.applications.snake.application.views',
        'KebabOS.applications.snake.library'
        );

/**
 * Kebab Application Base Class
 *
 * @category    Kebab
 * @package     Applications
 * @namespace   KebabOS.applications.snake.application
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 */
KebabOS.applications.snake.application.Bootstrap = function() {

    Ext.apply(this, {

        // Application ID
        id: 'snake-application',

        // Application Launcher Settings
        launcher: {
            iconCls: 'snake-application-launcher-icon'
        }
    });

    KebabOS.applications.snake.application.Bootstrap.superclass.constructor.call(this);
}