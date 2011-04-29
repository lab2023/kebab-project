/**
 * userManager Application indexController Class
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.userManager.application.controllers
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/licensing
 */
KebabOS.applications.userManager.application.controllers.Index = Ext.extend(Ext.util.Observable, {

    // Application bootstrap
    bootstrap: null,

    constructor: function(config) {

        // Base Config
        Ext.apply(this, config || {});

        // Call Superclass initComponent() method
        KebabOS.applications.userManager.application.controllers.Index.superclass.constructor.apply(this, arguments);

        this.init();
    },

    // Initialize and define routing settings
    init: function() {
        this.bootstrap.layout.emailWindow.emailForm.on('hideWindow', this.hideWindowAction, this);
        this.bootstrap.layout.inviteUserWindow.inviteUserForm.on('hideWindow', this.hideWindowAction, this);
        this.bootstrap.layout.userRolesWindow.rolesGrid.on('hideWindow', this.hideWindowAction, this);
        this.bootstrap.layout.userRolesWindow.rolesGrid.on('userRequest', this.roleUserRequestAction, this);
        this.bootstrap.layout.userManagerDataView.on('userRequest', this.userRequestAction, this);
        this.bootstrap.layout.emailWindow.emailForm.on('emailFormOnSave', this.formOnSaveAction, this);
        this.bootstrap.layout.inviteUserWindow.inviteUserForm.on('inviteUserFormOnSave', this.formOnSaveAction, this);
        this.bootstrap.layout.userManagerDataView.on('showEmailWindow', this.showEmailWindowAction, this);
        this.bootstrap.layout.userManagerDataView.on('showUserRoleWindow', this.showUserRoleWindowAction, this);
        this.bootstrap.layout.on('showInviteUserWindow', this.showWindowAction, this);
        this.bootstrap.layout.userManagerDataView.on('loadGrid', this.loadGridAction, this);
        this.bootstrap.layout.userRolesWindow.rolesGrid.on('loadGrid', this.loadGridAction, this);
        this.bootstrap.layout.inviteUserWindow.inviteUserForm.on('loadGrid', this.loadGridAction, this);
        this.bootstrap.layout.userRolesWindow.rolesGrid.on('hideUserRolesWindow', this.hideWindowAction, this);
    },

    // Actions -----------------------------------------------------------------

    hideWindowAction: function(component) {
        component.hide();
    },

    loadGridAction: function(component) {
        component.load();
    },

    userRequestAction: function(data) {
        var notification = new Kebab.OS.Notification();
        Ext.Ajax.request({
            url: BASE_URL + data.url,
            method: data.method,
            params: {

                id: data.user.id,
                status: data.status
            },
            success : function() {
                notification.message(this.bootstrap.launcher.text, 'Success');
                if (data.store) {
                    data.from.fireEvent('loadGrid', data.store);
                }
            },

            failure : function() {
                notification.message(this.bootstrap.launcher.text, 'Failure');
            }, scope:this
        });
    },

    roleUserRequestAction: function(data) {
        var notification = new Kebab.OS.Notification();
        Ext.Ajax.request({
            url: BASE_URL + data.url,
            method: data.method,
            params: {

                id: data.user,
                roles: data.roles
            },
            success : function() {
                notification.message(this.bootstrap.launcher.text, 'Success');
                if (data.store) {
                    data.from.fireEvent('loadGrid', data.store);
                }
            },

            failure : function() {
                notification.message(this.bootstrap.launcher.text, 'Failure');
            }, scope:this
        });
    },


    formOnSaveAction: function(data) {

        if (data.from.getForm().isValid()) {
            var notification = new Kebab.OS.Notification();

            data.from.getForm().submit({
                url: data.url,
                method: data.method,

                success : function() {
                    notification.message(this.bootstrap.launcher.text, 'Success');
                    data.from.getForm().reset();
                    if (data.fromWindow) {
                        data.from.fireEvent('hideWindow', data.fromWindow);
                    }
                    if (data.store) {
                        data.from.fireEvent('loadGrid', data.store);
                    }
                },

                failure : function() {
                    notification.message(this.bootstrap.launcher.text, 'Failure');
                }, scope:this
            });
        }
    },

    showUserRoleWindowAction: function(data) {
        this.bootstrap.layout.userRolesWindow.setTitle(data.firstName + " " + data.lastName + " 's Roles");
        this.bootstrap.layout.userRolesWindow.rolesGrid.userId = data.id;
        this.bootstrap.layout.userRolesWindow.rolesGrid.Roles = data.Roles;

        this.bootstrap.layout.userRolesWindow.show();
        this.bootstrap.layout.userRolesWindow.rolesGrid.store.load({params: {userId: data.id}});
    },

    showEmailWindowAction: function(data) {
        if (data.status == 'active') {
            var title = ' Reset password';
            var requestUrl = '/authentication/forgot-password';
            var method = 'POST';
        }
        if (data.status == 'passive') {
            var title = ' Re-invite';
            var requestUrl = '/user/invite';
            var method = 'PUT';
        }
        this.bootstrap.layout.emailWindow.setTitle(data.firstName + " " + data.lastName + title);
        this.bootstrap.layout.emailWindow.emailForm.url = BASE_URL + requestUrl;
        this.bootstrap.layout.emailWindow.emailForm.method = method;
        this.bootstrap.layout.emailWindow.emailForm.getForm().findField('email').setValue(data.email);
        this.bootstrap.layout.emailWindow.show();
    },

    showWindowAction: function(component) {
        component.show();
    }

});
