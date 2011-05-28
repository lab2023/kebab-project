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

Ext.namespace('Kebab.OS.Kernel');

/**
 * Kebab.OS.Kernel
 *
 * @namespace   Kebab.OS
 * @author      Tayfun Öziş ERİKAN <tayfun.ozis.erikan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */
Kebab.OS.Kernel = function(config){

    // Initial Configs
    this.isBooted = false;
    this.serviceAPI = 'api';
    this.distribution = 'KebabOS';
    this.assets = null;
    this.settings = null;
    this.user = null;
    this.apps = null;
    this.stories = null;
    this.applications = null;
    this.stateProvider = new Ext.state.CookieProvider();

    Ext.apply(this, config);

    this.addEvents({
        'booted' : true,
        'beforeunload' : true
    });

    Ext.onReady(this.boot, this);
};
Ext.extend(Kebab.OS.Kernel, Ext.util.Observable, {

    init : function() {

        Ext.state.Manager.setProvider(this.stateProvider);

        Kebab.helper.log('Kebab.OS.Kernel initialized...');
    },

    boot : function(){

        this.init();

        // Initialize Desktop
        this.desktop = new Kebab.OS.Desktop(this);

        this.windowList = this.desktop.windowList;

        this.applications = this.applications || this.loadApplications();

        if(this.applications) {
            this.initApplications();
        }

        Ext.EventManager.on(window, 'beforeunload', this.onUnload, this);
        this.fireEvent('booted', this);
        this.isBooted = true;

        if (this.isBooted) {
            Kebab.helper.log('Kebab.OS.Kernel booted...');
            Kebab.helper.message('Kebab Project', 'Welcome to Kebab Project. Have fun!');
        }
    },

    loadApplications: function() {

        var i = 0,
            applicationInstances = new Array();

        Ext.each(this.apps, function(application) {
            
            try {
                applicationInstances[i++] = Ext.applyIf(
                    eval('new ' + application.className + '()'),
                    application
                );
            } catch(e) {
                Kebab.helper.log(application.className + ' not loaded...', 'ERR');
            }
        });

        Kebab.helper.log('Kebab applications loaded...');
        
        return applicationInstances;
    },

    initApplications : function(){

        var appSC = this.applications;

        for(var i = 0, len = appSC.length; i < len; i++){
            var app = appSC[i];

            var launcher = Ext.apply(app.launcher, app.title);

            this.windowList.addLauncherMenuItem(launcher, app.type);
            app.app = this;

            // Check launcher auto start flag and call createApplication()
            if (app.launcher.autoStart) {
                app.createApplication();
            }
        }
    },

    getApplication : function(id) {

        var app = this.applications;

        for (var i = 0, len = app.length; i < len; i++) {
            if (app[i].id == id || app[i].appType == id) {
                return app[i];
            }
        }
        return null;
    },

    getApplications : function() {
        return this.applications;
    },

    callApplication : function(id, cb, scope){
        var app = this.getApplication(id);
        if(app){
            cb.call(scope, app)
        }
    },

    getUser : function(){
        return this.user;
    },

    getAssets : function(){
        return this.assets;
    },

    getSettings : function(){
        return this.settings;
    },

    getDesktop : function(){
        return this.desktop;
    },

    onReady : function(fn, scope){
        if(!this.isBooted){
            this.on('booted', fn, scope);
        }else{
            fn.call(scope, this);
        }
    },

    onUnload : function(e){
        if(this.fireEvent('beforeunload', this) === false){
            e.stopEvent();
        }
    }
});
