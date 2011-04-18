/**
 * userManager Application UserRolesWindow class
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.userManager.application.views
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/licensing
 */
KebabOS.applications.userManager.application.views.UserRolesWindow = Ext.extend(Ext.Window, {

    // Application Bootstrap
    bootstrap: null,

    initComponent: function() {

        this.rolesGrid = new KebabOS.applications.userManager.application.views.RolesGrid({
            bootstrap: this.bootstrap
        });

        var config = {
            layout:'fit',
            width:300,
            height:300,
            title:'User Roles',
            iconCls: 'userManager-application-gui-icon',
            border:false,
            resizable: false,
            maximizable: false,
            closeAction: 'hide',
            manager: this.bootstrap.app.getDesktop().getManager(),
            modal:true,
            items:[this.rolesGrid]
        }

        Ext.apply(this, config);

        KebabOS.applications.userManager.application.views.UserRolesWindow.superclass.initComponent.apply(this, arguments);
    },

    showWindow: function(user) {
        this.setTitle(user.firstName + " " + user.lastName + " 's Roles");
        this.rolesGrid.selectedUserId(user.id);
        this.show();
        this.rolesGrid.store.load({params: {userId: user.id}});
    }

});