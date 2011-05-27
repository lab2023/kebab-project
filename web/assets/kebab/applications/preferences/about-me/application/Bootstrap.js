/**
 * Kebab AboutMe Application Bootstrap Class
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.aboutMe.application
 * @author      Tayfun Öziş ERİKAN <tayfun.ozis.erikan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 */
Ext.extend(KebabOS.applications.aboutMe.application.Bootstrap, Kebab.OS.Application, {

    constructor: function() {
        KebabOS.applications.aboutMe.application.Bootstrap.superclass.constructor.call(this);
    },

    createApplication : function(){

        var desktop = this.app.getDesktop();
        var app = desktop.getApplication(this.id);

        if(!app){

            var winWidth = 600;
            var winHeight = 400;

            // Default layout
            this.layout = new KebabOS.applications.aboutMe.application.layouts.Layout({
                bootstrap: this
            });

            // Default controller
            this.defaultController = new KebabOS.applications.aboutMe.application.controllers.Index({
                bootstrap: this
            });

            app = desktop.createApplication({
                id: this.id,
                title: this.title.text,
                description: this.title.description,
                iconCls: 'aboutMe-application-gui-icon',
                width: winWidth,
                height: winHeight,
                resizable: false,
                maximizable: false,
                border: false,
                items: this.layout
            });

        }
        app.show();
    }
});





