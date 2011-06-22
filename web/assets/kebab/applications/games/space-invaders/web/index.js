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
        'KebabOS.applications.spaceInvaders',
        'KebabOS.applications.spaceInvaders.application',
        'KebabOS.applications.spaceInvaders.application.configs',
        'KebabOS.applications.spaceInvaders.application.controllers',
        'KebabOS.applications.spaceInvaders.application.languages',
        'KebabOS.applications.spaceInvaders.application.layouts',
        'KebabOS.applications.spaceInvaders.application.models',
        'KebabOS.applications.spaceInvaders.application.views',
        'KebabOS.applications.spaceInvaders.library'
        );

/**
 * Kebab Application Base Class
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.spaceInvaders.application
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 */
KebabOS.applications.spaceInvaders.application.Bootstrap = function() {

    Ext.apply(this, {

        // Application ID
        id: 'spaceInvaders-application',

        // Application Launcher Settings
        launcher: {
            iconCls: 'spaceInvaders-application-launcher-icon'
        }
    });

    KebabOS.applications.spaceInvaders.application.Bootstrap.superclass.constructor.call(this);
}