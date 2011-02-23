Kebab.OS.Translator = function(locale) {
    
    this.locale = locale;
    
    this.translate = function(key) {
        return key + " Translated...";
    }
    
};