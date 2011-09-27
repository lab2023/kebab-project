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

Ext.namespace('Kebab');

/**
 * Kebab
 *
 * @namespace   Kebab
 * @author      Tayfun Öziş ERİKAN <tayfun.ozis.erikan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */

Kebab = function() {

    /**
     * OS variable
     * @type Kebab.OS
     */
    var os = null;

    return {

        /**
         * Application environment
         */
        applicationEnvironment: APPLICATION_ENV,

        /**
         * System base url
         */
        baseUrl: BASE_URL,

        /**
         * OS Internationalization object
         * @type Object
         */
        i18n: {
            languages: LANGUAGES
        },

        /**
         * Initialize Kebab & Kebab OS
         */
        init: function() {
            // Initialize the OS and set 'os' variable
            os = new Kebab.OS();
            os.init(this);
        },

        /**
         * Return the OS object
         */
        getOS: function() {
            return os;
        },

        /**
         * Helpers
         */
        helper: {

            /**
             * Pre-loader helper
             * @param show
             * @param d
             */
            loader: function(show, d) {
                if (show) {
                    os.showPreLoader(d);
                } else {
                    os.hidePreLoader();
                }
            },

            /**
             * Url helper
             * @param url
             */
            url: function(url) {
                return os.generateUrl(url);
            },

            /**
             * Redirect helper
             * @param url
             */
            redirect: function(url) {
                os.redirect(url);
            },

            /**
             * Translate helper
             * @param key
             */
            translate: function(key) {
                return os.getTranslator().translate(key);
            },

            /**
             * Logger helper
             * @param msg
             */
            log: function(msg, type) {
                os.getLogger().log(msg, type)
            },

            /**
             * Notification helper
             * @param title
             * @param message
             */
            message: function(title, message, keep, type) {
                os.getNotification().message(title, message, keep, type);
            },

            /**
             * Dialog helper
             * @param title
             * @param message
             */
            dialog: function(title, message, type) {
                os.getNotification().dialog(title, message, type);
            }
        }
    }
}();

Ext.onReady(Kebab.init, Kebab);

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
                this.getNotification().message('Argh! %(', 'Operation failure....', true);
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
 * Kebab.Logger
 *
 * @namespace   Kebab
 * @author      Tayfun Öziş ERİKAN <tayfun.ozis.erikan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */
Kebab.Logger = function(env) {

    var logEnv = "production";

    /**
     * General Logs
     * @return void
     */
    this.log = function(message, type) {

        if (env != logEnv) {
            switch(type) {
                default:
                    console.log(message);
                    break;
                case 'INFO':
                    this.info(message);
                    break;
                case 'WARN':
                    this.warn(message);
                    break;
                case 'ERR':
                    this.error(message);
                    break;
            }
        }
    };

    /**
     * Info Logs
     * @return void
     * @return void
     */
    this.info = function(message) {
        if (env != logEnv)  {
            console.info(message);
        }
    };

    /**
     * Warning Logs
     * @return void
     */
    this.warn = function(message) {
        if (env != logEnv)  {
            console.warn(message);
        }
    };

    /**
     * Error Logs
     * @return void
     */
    this.error = function(message) {
        if (env != logEnv)  {
            console.error(message);
        }
    };

};

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

Ext.namespace('Kebab.Notification');

/**
 * Kebab.Notification
 *
 * @namespace   Kebab.OS
 * @author      Tayfun Öziş ERİKAN <tayfun.ozis.erikan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */
Kebab.Notification = Ext.extend(Ext.util.Observable, {

    /**
     * Message container element
     */
    messageContainer: null,

    /**
     * Object constructor
     * @param config
     */
    constructor: function(config) {

        Ext.apply(this, config);

        Kebab.Notification.superclass.constructor.call(this, arguments);
    },

    /**
     * Show messages
     *
     * @param title
     * @param message
     * @param keep
     */
    message : function(title, message, keep, type){

        var qtipMsg = Kebab.helper.translate('Click to hide');

        var messageBody = function(t, s){
            return ['<div ext:qtip="'+qtipMsg+'">',
            '<p><h3>', t, '</h3>', s, '</p>',
            '</div>'].join('');
        };

        this.messageContainer = this.messageContainer || Ext.DomHelper.insertFirst(document.body, {
            cls:'kebab-notifications-body',
            id: 'kebab-notifications'
        }, true).alignTo(document.body, 'tr-tr', [-15, 50]);

        var messageEl = Ext.DomHelper.append(this.messageContainer, {
            cls: 'kebab-notifications kebab-shadow-std kebab-rounded-corners ' + this.getColor(type),
            html: messageBody(Kebab.helper.translate(title), Kebab.helper.translate(message)),
            alignTo: [0, 33]
        }, true);

        if (!keep) {
            messageEl.slideIn('t', {easing: 'backOut', duration: .0}).pause(2).ghost("b", {
                remove:true,
                easing: 'easeIn',
                duration: .15,
                callback: function() {
                    messageEl.remove();
                },
                scope:this
            });
        } else {
            messageEl.slideIn('t', {easing: 'backOut', duration: .0});
            messageEl.on('click', function() {
                messageEl.puff({
                    remove: true,
                    easing: 'easeIn',
                    duration: .15,
                    callback: function() {
                        messageEl.remove();
                    },
                    scope:this
                });
            });
        }

        // KBBTODO Messages z-index problem solution is -> this.messageContainer.setStyle('z-index', 0);
    },

    /**
     * Show dialog
     *
     * @param title
     * @param message
     * @param type
     */
    dialog: function(title, message, type) {

        var icon = this.getIcon(type);

        Ext.Msg.show({
            modal:true,
            title: Kebab.helper.translate(title),
            msg: Kebab.helper.translate(message),
            icon: icon,
            buttons: Ext.Msg.OK
        });
    },

    /**
     * Get dialog or message icons
     * @param type
     */
    getIcon: function(type) {

        var icon = null;

        switch (type) {
            case 'WARN':
                icon = Ext.MessageBox.WARNING
                break;
            case 'ERR':
                icon = Ext.MessageBox.ERROR
                break;
            default:
                icon = Ext.MessageBox.INFO
                break;
        }

        return icon;
    },

    getColor: function(type) {

        var color = null;

        switch (type) {
            case 'ALERT':
                color = 'notify-alert';
                break;
            case 'CRIT':
                color = 'notify-critic';
                break;
            case 'ERR':
                color = 'notify-error';
                break;
            case 'WARN':
                color = 'notify-warning';
                break;
            case 'NOTICE':
                color = 'notify-notice';
                break;
            default:
                color = 'notify-info';
                break;
        }

        return color;
    }
});

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

Ext.namespace('Kebab.Translator');
/**
 * Kebab.Translator
 *
 * @namespace   Kebab
 * @author      Tayfun Öziş ERİKAN <tayfun.ozis.erikan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */
Kebab.Translator = function(i18n) {

    /**
     * OS instance
     * @type Kebab.OS
     */
    this.i18n = i18n;

    /**
     * Translate
     * @return string
     */
    this.translate = function() {

        var translations;
        // Translations is app or kebab ?
        try {
            translations = this.getI18n().translations[arguments[0]];
        } catch(e) {
            Kebab.getOS().getLogger().log("'" + arguments[0] + '" Translation is not found...', 'ERR');

            translations = "NOT FOUND (" + arguments[0] + ")";
        }

        // Get key or return self
        var key =  translations || arguments[0];
        var args = Ext.toArray(arguments, 1);

        var _replace = function(key, args) {
            return key.replace(/\{(\d+)\}/g, function(m, i) {
                return args[i];
            });
        };

        return _replace(key, args);
    };

    /**
     * Get current locale
     */
    this.getLocale = function() {
        return this.getLanguages(true).language;
    },

    /**
     * Get current languages or active language
     * @param active
     */
    this.getLanguages = function(active) {

        if(active) {

            var currentLanguage = null;

            Ext.each(this.getI18n().languages, function(language) {
                if (language.active) {
                    currentLanguage = language;
                    return null;
                }
            });

            return currentLanguage;

        } else {
            return this.getI18n().languages;
        }
    },

    /**
     * Get translate object
     */
    this.getTranslations = function() {
        return this.getI18n().translations
    },

    /**
     * Get i18n object
     */
    this.getI18n = function() {
        return this.i18n;
    },

    /**
     * Add i18n object
     */
    this.addTranslations = function(translations) {
        Ext.applyIf(this.i18n.translations, translations);
    }
};

