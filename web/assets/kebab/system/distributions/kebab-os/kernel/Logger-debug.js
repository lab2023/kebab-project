/**
* Kebab.OS.Logger
* @namespace Kebab.OS
*/
Kebab.OS.Logger = function() {
    
    /**
     * Log only this environment
     * @type string
     */
    var environment = "development";
    
    return {
        
        /**
         * General Logs
         */
        log: function(data) {
            if (APPLICATION_ENV == environment)
                console.log(data);
        },

        /**
         * Info Logs
         */
        info: function(data) {
            if (APPLICATION_ENV == environment)
                console.info(data);
        },

        /**
         * Warning Logs
         */
        warn: function(data) {
            if (APPLICATION_ENV == environment)
                console.warn(data);
        },
        
        /**
         * Error Logs
         */
        error: function(data) {
            if (APPLICATION_ENV == environment)
                console.error(data);
        }
    }
    
}();

