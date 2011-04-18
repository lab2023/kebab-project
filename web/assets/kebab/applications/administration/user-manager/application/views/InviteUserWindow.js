/**
 * userManager Application InviteUserWindow class
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.userManager.application.view
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/licensing
 */
KebabOS.applications.userManager.application.views.InviteUserWindow = Ext.extend(Ext.Window, {

    // Application Bootstrap
    bootstrap: null,

    initComponent: function() {

        this.inviteUserForm = new KebabOS.applications.userManager.application.views.InviteUserForm({
            bootstrap: this.bootstrap
        });

        var config = {
            layout:'fit',
            width:350,
            height:350,
            title:'Invite User',
            iconCls: 'userManager-application-gui-icon',
            border:false,
            resizable: false,
            maximizable: false,
            closeAction: 'hide',
            manager: this.bootstrap.app.getDesktop().getManager(),
            modal:true,
            items:[this.inviteUserForm]
        }

        Ext.apply(this, config);


        KebabOS.applications.userManager.application.views.InviteUserWindow.superclass.initComponent.apply(this, arguments);
    },

    showWindow: function() {
        this.show();
    }

});