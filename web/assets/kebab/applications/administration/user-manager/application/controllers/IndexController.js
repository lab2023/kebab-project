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
        this.bootstrap.layout.emailWindow.emailForm.on('hideEmailWindow', this.hideEmailWindowAction, this);
        this.bootstrap.layout.userManagerDataView.on('userRequest', this.userRequestAction, this);
        this.bootstrap.layout.userManagerDataView.on('showEmailWindow', this.showEmailWindowAction, this);
        this.bootstrap.layout.emailWindow.emailForm.on('emailFormOnSave', this.FormOnSaveAction, this);
        this.bootstrap.layout.inviteUserWindow.inviteUserForm.on('inviteUserFormOnSave', this.FormOnSaveAction, this);
        this.bootstrap.layout.userManagerDataView.on('showUserRoleWindow', this.showUserRoleWindowAction, this);
        this.bootstrap.layout.on('showInviteUserWindow', this.showWindowAction, this);
    },

    // Actions -----------------------------------------------------------------

    /*
       *
       *
       *
       * */
    hideEmailWindowAction: function() {
        this.bootstrap.layout.emailWindow.hide();
    },

    /*
       *
       *
       *
       * */
    userRequestAction: function(data) {
        var notification = new Kebab.OS.Notification();
        Ext.Ajax.request({
            url: BASE_URL + '/user/manager',
            method: data.method,
            params: {
                id: data.user.id,
                status: data.status
            },
            success : function() {
                notification.message(this.bootstrap.launcher.text, 'Success');
                this.bootstrap.layout.userManagerDataView.store.load();
            },

            failure : function() {
                notification.message(this.bootstrap.launcher.text, 'Failure');
            }, scope:this
        });
    },

    /*
       *
       *
       *
       * */
    showEmailWindowAction: function(data) {
        if (data.user.status == 'active') {
            var title = ' Reset password';
            var requestUrl = '/authentication/forgot-password';
        }
        if (data.user.status == 'passive') {
            var title = ' Re invite';
            var requestUrl = '/authentication/forgot-password';
        }
        this.bootstrap.layout.emailWindow.setTitle(data.user.firstName + " " + data.user.lastName + title);
        this.bootstrap.layout.emailWindow.emailForm.url = BASE_URL + requestUrl;
        this.bootstrap.layout.emailWindow.emailForm.getForm().findField('email').setValue(data.user.email);
        this.bootstrap.layout.emailWindow.show();
    },

    /*
       *
       *
       *
       * */
    FormOnSaveAction: function(data) {

        if (data.from.getForm().isValid()) {
            var notification = new Kebab.OS.Notification();

            data.from.getForm().submit({
                url: data.from.url,
                method: 'POST',

                success : function() {
                    notification.message(this.bootstrap.launcher.text, 'Success');
                    data.from.getForm().reset();
                    data.from.fireEvent('hideEmailWindow');
                },

                failure : function() {
                    notification.message(this.bootstrap.launcher.text, 'Failure');
                }, scope:this
            });

        }
    },

    /*
       *
       *
       *
       * */
    showUserRoleWindowAction: function(data) {
        this.bootstrap.layout.userRolesWindow.setTitle(data.user.firstName + " " + data.user.lastName + " 's Roles");
        this.bootstrap.layout.userRolesWindow.rolesGrid.userId = data.user.id;

        this.bootstrap.layout.userRolesWindow.show();
        this.bootstrap.layout.userRolesWindow.rolesGrid.store.load({params: {userId: data.user.id}});
    },

    /*
       *
       *
       *
       * */
    showWindowAction: function(data) {
        data.from.show();
    }
});
