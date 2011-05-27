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
        return this.getLanguages('active').language;
    },

    /**
     * Get current languages or active language
     * @param which
     */
    this.getLanguages = function(which) {

        if(which == 'active') {

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