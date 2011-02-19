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

Ext.namespace('Kebab.OS.Kernel');
Kebab.OS.Kernel = function(config){
    
    Ext.apply(this, config);
    
    this.addEvents({
        'booted' : true,
        'beforeunload' : true
    });
    
    Ext.onReady(this.boot, this);
};
Ext.extend(Kebab.OS.Kernel, Ext.util.Observable, {
    
    isBooted: false,
    
    serviceAPI: 'api',
    
    distribution: 'KebabOS',
    
    environment: null,
    
    user: null,
    
    applications: null,
    
    getApplications : Ext.emptyFn,
    
    init : function() {
        console.log('KebabOS initialized...'); 
    },

    boot : function(){
        
        Ext.QuickTips.init();
        
        // Initialize Desktop
        this.desktop = new Kebab.OS.Desktop(this);
        
        this.notification = new Kebab.OS.Notification();

		this.launcher = this.desktop.windowList.mainMenu;

		this.applications = this.applications || this.getApplications();
        
        if(this.applications) {
            this.initApplications();
        }

        this.init();

        Ext.EventManager.on(window, 'beforeunload', this.onUnload, this);
		this.fireEvent('booted', this);
        this.isBooted = true;
        
        if (this.isBooted) {
            console.log('Kebab.OS.Kernel booted...');
            this.notification.message(
                'Kebab OS', 
                this.bootMessage || "Welcome to Kebab Project. Have fun!"
            );
        }
    },
    
    getEnvironment : function(){
        return this.environment;
    },
    
    getUser : function(){
        if(this.user) {
            return Ext.util.JSON.decode(this.user);
        } else {
            return
        }
    },
    
    initApplications : function(){
        
        var appSC = this.applications;
        
		for(var i = 0, len = appSC.length; i < len; i++){
            var app = appSC[i];
            this.launcher.add(app.launcher);
            app.app = this;
        }
    },

    getApplication : function(name){
        
        console.log('Kebab.OS.Kernel getApplication call by ' +name+ ' parameter...');
        
    	var app = this.applications;
    	
        for(var i = 0, len = app.length; i < len; i++){
    		if(app[i].id == name || app[i].appType == name){
    			return app[i];
			}
        }
        return '';
    },

    onReady : function(fn, scope){
        if(!this.isBooted){
            this.on('booted', fn, scope);
        }else{
            fn.call(scope, this);
        }
    },

    getDesktop : function(){
        return this.desktop;
    },

    onUnload : function(e){
        if(this.fireEvent('beforeunload', this) === false){
            e.stopEvent();
        }
    }
});