/**
 * Kebab Application Bootstrap Class
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.pixlr.application
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/licensing
 */
Ext.extend(KebabOS.applications.pixlr.application.Bootstrap, Kebab.OS.Application, {

    createApplication : function() {

        var desktop = this.app.getDesktop();
        var app = desktop.getApplication(this.id);

        if (!app) {

            // desktop width and heigth
            var winWidth = parseInt(desktop.getWinWidth() / 1);
            var winHeight = parseInt(desktop.getWinHeight() / 1);

            // Default layout
            this.layout = new KebabOS.applications.pixlr.application.layouts.Layout({
                bootstrap: this
            });

            // Default controlller
            this.defaultController = new KebabOS.applications.pixlr.application.controllers.Index({
                bootstrap: this
            });

            // create window
            app = desktop.createApplication({
                id: this.id,
                title: this.launcher.text,
                iconCls: 'pixlr-application-gui-icon',
                width: winWidth,
                height: winHeight,
                border:false,
                items: this.layout
            });

        }
        app.show();
    }
});