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
        'KebabOS.applications.feedbackManager',
        'KebabOS.applications.feedbackManager.application',
        'KebabOS.applications.feedbackManager.application.controllers',
        'KebabOS.applications.feedbackManager.application.layouts',
        'KebabOS.applications.feedbackManager.application.models',
        'KebabOS.applications.feedbackManager.application.views',
        'KebabOS.applications.feedbackManager.library'
        );

/**
 * Kebab Application Base Class
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.feedbackManager.application
 * @author      Yunus ÖZCAN <yuns.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 */
KebabOS.applications.feedbackManager.application.Bootstrap = function() {

    Ext.apply(this, {

        // Application ID
        id: 'feedbackManager-application',

        // Application Launcher Settings
        launcher: {
            text: 'Feedback Manager',
            iconCls: 'feedbackManager-application-launcher-icon'
        }
    });

    KebabOS.applications.feedbackManager.application.Bootstrap.superclass.constructor.call(this);
}
