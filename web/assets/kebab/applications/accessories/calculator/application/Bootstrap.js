/**
 * Kebab Application Bootstrap Class
 * 
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.calculator.application
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 */
Ext.extend(KebabOS.applications.calculator.application.Bootstrap, Kebab.OS.Application, {
    
    createApplication : function(){
        
        var desktop = this.app.getDesktop();
        var app = desktop.getApplication(this.id);
        
        if(!app){
            
            // Default layout
            this.layout = new KebabOS.applications.calculator.application.layouts.Layout({
                bootstrap: this
            });
            
            // Default controlller
            this.defaultController = new KebabOS.applications.calculator.application.controllers.Index({
                bootstrap: this
            });

            // create window
            app = desktop.createApplication({
                id: this.id,
                title: this.launcher.text,                
                iconCls: 'calculator-application-gui-icon',
                width: 350,
                height: 320,
                border:false,
                items: this.layout,
                maximizable: false
            });
            
        }
        app.show();
    }
});