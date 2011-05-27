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
    this.initLanguages();

    // Call constructor
    Kebab.OS.Application.superclass.constructor.call(this);

    this.resources.logger.log(this.id + ' application initialized...');
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
                translator: Kebab.getOS().getTranslator(),
                logger: Kebab.getOS().getLogger(),
                notification: Kebab.getOS().getNotification()
            };
        } catch(e) {
            Kebab.getOS().getLogger().log(this.id + ' resources not loaded...', 'ERR');
        }
    },

    /**
     * Init application languages
     */
    initLanguages: function() {
        try { // Application translations

            // Application name replacement
            var appName = this.id.replace('-application', '').trim();
            var _replaceNameSpace = function() {
                return "KebabOS.applications.APP_NAME.application.languages".replace('APP_NAME', appName).trim();
            };

            var languages = eval(_replaceNameSpace());
            this.resources.translator.addTranslations(languages[this.resources.translator.getLocale()]);

        } catch(e) {
            this.resources.logger.log(this.id + ' translations not loaded...', 'ERR');
        }
    }
});