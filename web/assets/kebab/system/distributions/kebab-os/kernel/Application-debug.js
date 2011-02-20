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

Ext.namespace('Kebab.OS.Application');
Kebab.OS.Application = function(config){    
    Ext.apply(this, config);
    Kebab.OS.Application.superclass.constructor.call(this);
    this.init();
}

Ext.extend(Kebab.OS.Application, Ext.util.Observable, {
    init : Ext.emptyFn,
    createApplication : Ext.emptyFn
});