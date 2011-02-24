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

Ext.namespace('KebabOS.applications.AboutMeApplication');

/**
 * AboutMe Application
 * @namespace KebabOS.applications.AboutMe
 */
KebabOS.applications.AboutMeApplication = Ext.extend(Kebab.OS.Application, {
    
    id : 'aboutMe-application',
    
    init : function(){
                
        this.launcher = {
            text: 'About Me',
            iconCls: 'aboutMe-application-launcher-icon',
            handler : this.createApplication,
            scope: this
        }
    },

    createApplication : function(){
        
        var desktop = this.app.getDesktop();
        var app = desktop.getApplication(this.id);
        if(!app){
        
            app = desktop.createApplication({
                id: this.id,
                title: this.launcher.text,                
                iconCls: 'aboutMe-application-gui-icon',
                width: 500,
                height: 400,
                resizable:false,
                maximizable:false,
                border:false,
                items: new KebabOS.applications.AboutMeApplication.views.Layout({
                    application: this
                })
            });
            
        }
        app.show();
    }
});
