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
 * http://www.kebab-project.com/licensing
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@lab2023.com so we can send you a copy immediately.
 ----------------------------------------------------------------------------- */

Ext.namespace(
        'KebabOS.applications.pacMan',
        'KebabOS.applications.pacMan.application',
        'KebabOS.applications.pacMan.application.controllers',
        'KebabOS.applications.pacMan.application.layouts',
        'KebabOS.applications.pacMan.application.models',
        'KebabOS.applications.pacMan.application.views',
        'KebabOS.applications.pacMan.library'
        );

/**
 * Kebab Application Base Class
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.pacMan.application
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/licensing
 */
KebabOS.applications.pacMan.application.Bootstrap = function() {

    Ext.apply(this, {

        // Application ID
        id: 'pacMan-application',

        // Application Launcher Settings
        launcher: {
            text: 'Pac-Man',
            iconCls: 'pacMan-application-launcher-icon'
        }
    });

    KebabOS.applications.pacMan.application.Bootstrap.superclass.constructor.call(this);
}