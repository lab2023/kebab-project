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
    'KebabOS.applications.aboutMe',
    'KebabOS.applications.aboutMe.application',
    'KebabOS.applications.aboutMe.application.controllers',
    'KebabOS.applications.aboutMe.application.models',
    'KebabOS.applications.aboutMe.application.layouts',
    'KebabOS.applications.aboutMe.application.views',
    'KebabOS.applications.aboutMe.library'
);

/**
 * Producer Application Base Class
 * @namespace KebabOS.applications.producer
 */
KebabOS.applications.aboutMe.application.Bootstrap = function() {
    
    Ext.apply(this, {
        
        // Application ID
        id : 'aboutMe-application',
        
        // Application Launcher Settings
        launcher: {
            text: 'About Me',
            iconCls: 'aboutMe-application-launcher-icon',
            autoStart:true
        }
    });
    
    KebabOS.applications.aboutMe.application.Bootstrap.superclass.constructor.call(this);
}