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

