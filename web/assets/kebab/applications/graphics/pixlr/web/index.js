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
        'KebabOS.applications.pixlr',
        'KebabOS.applications.pixlr.application',
        'KebabOS.applications.pixlr.application.configs',
        'KebabOS.applications.pixlr.application.controllers',
        'KebabOS.applications.pixlr.application.languages',
        'KebabOS.applications.pixlr.application.layouts',
        'KebabOS.applications.pixlr.application.models',
        'KebabOS.applications.pixlr.application.views',
        'KebabOS.applications.pixlr.library'
        );

/**
 * Kebab Application Base Class
 *
 * @category    Kebab
 * @package     Applications
 * @namespace   KebabOS.applications.pixlr.application
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 */
KebabOS.applications.pixlr.application.Bootstrap = function() {

    Ext.apply(this, {

        // Application ID
        id: 'pixlr-application',

        // Application Launcher Settings
        launcher: {
            iconCls: 'pixlr-application-launcher-icon'
        }
    });

    KebabOS.applications.pixlr.application.Bootstrap.superclass.constructor.call(this);
}