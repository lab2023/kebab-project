/**
 * roleManager Application RoleManagerStoryWindow class
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.roleManager.application.view
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/licensing
 */
KebabOS.applications.roleManager.application.views.RoleManagerStoryWindow = Ext.extend(Ext.Window, {

    // Application Bootstrap
    bootstrap: null,
    layout:'fit',
    initComponent: function() {

        this.roleManagerStoryGrid = new KebabOS.applications.userManager.application.views.RoleManagerStoryGrid({
            bootstrap: this.bootstrap
        });

        var config = {
            width:300,
            height:140,
            title:'Story Select',
            iconCls: 'userManager-application-gui-icon',
            border:false,
            resizable: false,
            maximizable: false,
            closeAction: 'hide',
            manager: this.bootstrap.app.getDesktop().getManager(),
            modal:true,
            items:[this.roleManagerStoryGrid]
        }

        Ext.apply(this, config);

        KebabOS.applications.roleManager.application.views.RoleManagerStoryWindow.superclass.initComponent.apply(this, arguments);
    },

    showWindow: function() {
        this.show();
    }
});