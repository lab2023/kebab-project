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
/**
 * Frontend Application Singleton Object
 */
Application = function(){

    var preLoader = null;
    var preLoaderHidden = false;
    
    return {
        
        init: function(config){

            // Qtips setup
            Ext.QuickTips.init();
            Ext.apply(Ext.QuickTips.getQuickTip(), {trackMouse: true});

            Ext.applyIf(this, config);

            this.preLoader = Ext.get('kebab-loading-mask');
            
            this.preLoader.on('click', function() {
                this.hidePreLoader(.5);
            }, this);

            new Ext.util.DelayedTask(function(){
                this.hidePreLoader(.5);
            }, this).delay(400);
        },

        /**
         * Hide the preloader
         * @param d
         */
        hidePreLoader: function(d){
            if (!this.preLoaderHidden)  {
                this.preLoader.slideOut('t', {remove: false, duration:d});
                this.preLoaderHidden = true;
            }
        },

        /**
         * Show the page preloader
         * @param d
         */
        showPreLoader: function(d) {
            this.preLoader.slideIn('t', {duration:d});
            this.preLoaderHidden = false;
        },

        redirect: function(url) {

            Application.showPreLoader(0);
            
            new Ext.util.DelayedTask(function(){
                window.location.href = this.generateUrl(url);
            }, this).delay(300);
        },

        generateUrl: function(url) {
            return this.getBaseUrl() + '/' + url;
        },

        getBaseUrl: function() {
            return BASE_URL;
        },

        getEnvironment: function() {
            return APPLICATION_ENV;
        }
    };
}();

Ext.onReady(Application.init, Application);