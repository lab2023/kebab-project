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
/**
 * Kebab OS Singleton Object
 */
Ext.namespace('Kebab', 'Kebab.OS');
Kebab.OS = function(){

    /**
     * Base variables and constants accessors
     */
    var applicationEnvironment = APPLICATION_ENV,
        baseUrl = BASE_URL,
        languages = LANGUAGES,
        preLoader = null,
        preLoaderHidden = false,
        delay = 300;

    return {

        /**
         * Kebab OS initializer
         */
        init: function(){

            // Quick tips setup
            Ext.QuickTips.init();
            Ext.apply(Ext.QuickTips.getQuickTip(), {trackMouse: true});
            
            preLoader = Ext.get('kebab-loading-mask');

            this.buildProjectInfo();
            
            preLoader.on('click', function() {
                this.hidePreLoader(.5);
            }, this);

            new Ext.util.DelayedTask(function(){
                this.hidePreLoader(.5);
            }, this).delay(delay);
        },

        // BUILDERS ----------------------------------------------------------------------------------------------------

        /**
         * Build project info
         */
        buildProjectInfo: function() {
            Ext.fly('project-info').on('mouseenter', function(a, b, c) {
                Ext.fly('project-info').scale([150],[Ext.isChrome ? 95 : 85], {
                    easing: 'elasticOut', duration: .6,
                    callback: function() {
                        new Ext.util.DelayedTask(function(){
                             Ext.fly('project-info').scale([120],[18], {
                                easing: 'easeIn', duration: .1
                            });
                        }).delay(3000);
                    }
                });
            });
        },

        // HELPERS ----------------------------------------------------------------------------------------------------

        /**
         * Hide the pre-loader
         * @param d
         */
        hidePreLoader: function(d){
            if (!preLoaderHidden)  {
                preLoader.fadeOut({remove: false, duration:d});
                preLoaderHidden = true;
            }
        },

        /**
         * Show the page pre-loader
         * @param d
         */
        showPreLoader: function(d) {
            preLoader.fadeIn({duration:d});
            preLoaderHidden = false;
        },

        /**
         * Redirect to url
         * @param url
         */
        redirect: function(url) {

            Kebab.OS.showPreLoader(0);
            
            new Ext.util.DelayedTask(function(){
                window.location.href = this.generateUrl(url);
            }, this).delay(delay);
        },

        /**
         * Generate full url
         * @param url
         */
        generateUrl: function(url) {
            return this.getBaseUrl() + '/' + url;
        },

        // GETTERS -----------------------------------------------------------------------------------------------------

        /**
         * Get current languages
         * @param which
         */
        getLanguages: function(which) {

            if(which == 'current') {

                var currentLanguage = null;

                Ext.each(languages, function(language) {
                    if (language.active) {
                        currentLanguage = language;
                        return null;
                    }
                });

                return currentLanguage;

            } else {
                return languages;
            }
        },
        
        /**
         * Get site base url
         */
        getBaseUrl: function() {
            return baseUrl;
        },

        /**
         * Get Application Environment
         */
        getEnvironment: function() {
            return applicationEnvironment;
        },

        // ACTIONS -----------------------------------------------------------------------------------------------------


        /**
         * Logout action
         */
        logoutAction: function(userId, redirect) {
            Ext.Ajax.request({
                url: this.generateUrl('kebab/session/' + userId),
                method: 'DELETE',
                success: function() {
                    if (redirect) {
                        this.redirect('backend');
                    } else {
                        return true;
                    }
                },
                failure: function() {
                    var notification = new Kebab.OS.Notification();
                    notification.message('Argh! %(', 'Operation failure....');
                    return false;
                },
                scope: this
            }, this)
        },

        /**
         * Shutdown and exit (close browser window)
         */
        shutDownAction: function(userId) {
            if(this.logoutAction(userId, false)) {
                window.close();
            }
        },

        /**
         * Reboot system (reload)
         */
        rebootAction: function() {
            this.redirect('backend/desktop');
        }
    };
}();
Ext.onReady(Kebab.OS.init, Kebab.OS);