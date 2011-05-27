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
            message: function(title, message, keep) {
                os.getNotification().message(title, message, keep);
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