/**
* Kebab.OS.Translator
* @namespace Kebab.OS
*/
Kebab.OS.Translator = function(locale) {
    
    /**
     * Locale
     * @type object
     */
    this.locale = locale;
    
    /**
     * Translae
     * @return string
     */
    this.translate = function(key) {
        return key + " Translated...";
    }
    
};