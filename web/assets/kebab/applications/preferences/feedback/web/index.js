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
        'KebabOS.applications.feedback',
        'KebabOS.applications.feedback.application',
        'KebabOS.applications.feedback.application.configs',
        'KebabOS.applications.feedback.application.controllers',
        'KebabOS.applications.feedback.application.languages',
        'KebabOS.applications.feedback.application.layouts',
        'KebabOS.applications.feedback.application.models',
        'KebabOS.applications.feedback.application.views',
        'KebabOS.applications.feedback.library'
        );

/**
 * Kebab Application Base Class
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.feedback.application
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 */
KebabOS.applications.feedback.application.Bootstrap = function() {

    Ext.apply(this, {

        // Application ID
        id: 'feedback-application',

        // Application Launcher Settings
        launcher: {
            iconCls: 'feedback-application-launcher-icon'
        }
    });

    KebabOS.applications.feedback.application.Bootstrap.superclass.constructor.call(this);
}