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
        'KebabOS.applications.radio',
        'KebabOS.applications.radio.application',
        'KebabOS.applications.radio.application.controllers',
        'KebabOS.applications.radio.application.layouts',
        'KebabOS.applications.radio.application.models',
        'KebabOS.applications.radio.application.views',
        'KebabOS.applications.radio.library'
        );

/**
 * Kebab Application Base Class
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.radio.application
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/licensing
 */
KebabOS.applications.radio.application.Bootstrap = function() {

    Ext.apply(this, {

        // Application ID
        id: 'radio-application',

        // Application Launcher Settings
        launcher: {
            text: 'Radio Oddu',
            iconCls: 'radio-application-launcher-icon'
        }
    });

    KebabOS.applications.radio.application.Bootstrap.superclass.constructor.call(this);
}