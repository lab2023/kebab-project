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

Ext.namespace('Kebab.OS');

/**
 * Kebab.OS
 *
 * @namespace   Kebab.OS
 * @author      Tayfun Öziş ERİKAN <tayfun.ozis.erikan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */
Kebab.OS = function(kebab){
    
    /**
     * Base variables
     */
    var preLoader = null,
        preLoaderHidden = false,
        delay = 300;

    /**
     * Kebab object
     * @type Kebab
     */
    this.kebab = null;

    /**
     * Logger function
     * @type Kebab.Logger
     */
    this.logger = Ext.emptyFn;

    /**
     * Translator function
     * @type Kebab.Translator
     */
    this.translator =  Ext.emptyFn;

    /**
     * Kebab OS initializer
     * @return object
     */
    this.init = function(kebab){

        // Bind kebab object
        this.kebab = kebab;

        // Quick tips setup
        Ext.QuickTips.init();
        Ext.apply(Ext.QuickTips.getQuickTip(), {trackMouse: true});

        // Builders
        try {
            this.buildBaseObjects();
            this.buildProjectInfo();
        } catch(e) {
            Kebab.helper.log('Kebab.OS builders not loaded...', 'ERR');
        }

        // Get pre-loader element
        preLoader = Ext.get('kebab-loading-mask');
        preLoader.on('click', function() {
            this.hidePreLoader(.5);
        }, this);

        // Delay and hide
        new Ext.util.DelayedTask(function(){
            this.hidePreLoader(.5);
        }, this).delay(delay);
        
        Kebab.helper.log('Kebab.OS initialized...');

        return this;
    };

    // BUILDERS ----------------------------------------------------------------------------------------------------

    /**
     * Build the base objects
     * @return void
     */
    this.buildBaseObjects = function() {

        // Set translator object
        this.translator = new Kebab.Translator(this.getKebab().i18n);
        
        // Set logger object
        this.logger = new Kebab.Logger(this.getKebab().applicationEnvironment);

        // Set notification object
        this.notification = new Kebab.Notification();
    };

    /**
     * Build project info
     * @return void
     */
    this.buildProjectInfo = function() {
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
    };

    // HELPERS ----------------------------------------------------------------------------------------------------

    /**
     * Hide the pre-loader
     * @param d
     * @return void
     */
    this.hidePreLoader = function(d){
        if (!preLoaderHidden)  {
            preLoader.fadeOut({remove: false, duration:d});
            preLoaderHidden = true;
        }
    };

    /**
     * Show the page pre-loader
     * @param d
     * @return void
     */
    this.showPreLoader = function(d) {
        preLoader.fadeIn({duration:d});
        preLoaderHidden = false;
    };

    /**
     * Redirect to url
     * @param url
     * @return void
     */
    this.redirect = function(url) {

        this.showPreLoader(0);

        new Ext.util.DelayedTask(function(){
            window.location.href = this.generateUrl(url);
        }, this).delay(delay);
    };

    /**
     * Generate full url
     * @param url
     * @return string
     */
    this.generateUrl = function(url) {
        return this.getBaseUrl() + '/' + url;
    },

    // GETTERS -----------------------------------------------------------------------------------------------------

    /**
     * Get the kebab object
     * @return object
     */
    this.getKebab = function() {
        return this.kebab;
    };

    /**
     * Get the translator object
     * @return object
     */
    this.getTranslator = function() {
        return this.translator;
    };

    /**
     * Get the logger object
     * @return object
     */
    this.getLogger = function() {
        return this.logger;
    };

    /**
     * Get the notification object
     * @return object
     */
    this.getNotification = function() {
        return this.notification;
    };

    /**
     * Get site base url
     * @return string
     */
    this.getBaseUrl = function() {
        return this.getKebab().baseUrl;
    };

    /**
     * Get Application Environment
     * @return string
     */
    this.getEnvironment = function() {
        return this.getKebab().applicationEnvironment;
    };

    // ACTIONS -----------------------------------------------------------------------------------------------------

    /**
     * Logout action
     * @return void
     */
    this.logoutAction = function(userId, redirect) {
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
                this.getNotification().message('Argh! %(', 'Operation failure....');
                return false;
            },
            scope: this
        }, this)
    };

    /**
     * Shutdown and exit (close browser window)
     * @return void
     */
    this.shutDownAction = function(userId) {
        if(this.logoutAction(userId, false)) {
            window.close();
        }
    };

    /**
     * Reboot system (reload)
     * @return void
     */
    this.rebootAction = function() {
        this.redirect('backend/desktop');
    }
};