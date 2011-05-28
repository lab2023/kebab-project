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

Ext.namespace('Kebab.OS.Application');

/**
 * Kebab.OS.Application
 *
 * @namespace   Kebab.OS
 * @author      Tayfun Öziş ERİKAN <tayfun.ozis.erikan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */
Kebab.OS.Application = function(){

    // Application translations
    this.translations = null;

    // Base Application Launcher Settings
    this.launcher = Ext.applyIf(this.launcher, {
        text: 'Default Application' ,
        iconCls: 'default-application-launcher-icon',
        handler: this.createApplication,
        scope: this
    });

    // Run init methods
    this.init();
    this.initResources();
    this.initTranslations();

    // Call constructor
    Kebab.OS.Application.superclass.constructor.call(this);

    Kebab.helper.log(this.id + ' application initialized...');
};

Ext.extend(Kebab.OS.Application, Ext.util.Observable, {
    init : Ext.emptyFn,
    createApplication : Ext.emptyFn,

    /**
     * Init application resources
     */
    initResources: function() {
        try { // Kebab OS resources
            this.resources = {
                getTranslator: function() {
                    return Kebab.getOS().getTranslator();
                },
                getLogger: function() {
                    return Kebab.getOS().getLogger();
                },
                getNotification: function() {
                    return Kebab.getOS().getNotification();
                }
            };
        } catch(e) {
            Kebab.getOS().getLogger().log(this.id + ' resources not loaded...', 'ERR');
        }
    },

    /**
     * Init application languages
     */
    initTranslations: function() {

        // Application name replacement
        var appName = this.id.replace('-application', '').trim();
        var _replaceNameSpace = function() {
            return "KebabOS.applications.APP_NAME.application.languages".replace('APP_NAME', appName).trim();
        };

        // Load translations
        var translations = eval(_replaceNameSpace());

        // Add translations to translator object
        this.getResources().getTranslator().addTranslations(translations[this.getResources().getTranslator().getLocale()]);

        // Set the translations
        this.setTranslations(translations);
    },

    /**
     * Set application translations
     */
    setTranslations: function(translations) {
        this.translations = translations;
    },

    /**
     * Get application's active or all translations
     * @param active boolean
     */
    getTranslations: function(active) {
        return active ? this.translations[this.getResources().getTranslator().getLocale()]
                      : this.translations;
    },

    /**
     * Get the application resources
     */
    getResources: function() {
        return this.resources;
    }
});