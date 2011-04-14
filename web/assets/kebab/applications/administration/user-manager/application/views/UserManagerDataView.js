/**
 * userManager Application UserManagerDataView class
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.userManager.application.views
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/licensing
 */
KebabOS.applications.userManager.application.views.UserManagerDataView = Ext.extend(Ext.DataView, {

    // Application Bootstrap
    bootstrap: null,

    initComponent: function() {

        // create the data store
        this.store = new KebabOS.applications.userManager.application.models.UserDataStore({
            bootstrap:this.bootstrap
        });

        var config = {
            autoScroll:true,
            loadMask:true,
            overClass:'userManager-application-users-over',
            itemSelector:'div.userManager-application-users'
        }

        // Template
        this.tpl = new Ext.XTemplate(
                '<tpl for=".">',

                '<div class="userManager-application-users">',
                '<span style="font-size:18pt;">{firstName} {lastName}</span><br />',
                '<span>{username}</span>, <span>{email}</span><br />',
                '<tpl for="Roles">',
                '<span>[{name}]</span>   ',
                '</tpl>',
                '<div class="buttons">',

                '<tpl if="status ==\'active\' ">',
                '<span class="icon-accept" ext:qtip="{status}"></span>',
                '</tpl>',
                '<tpl if="status ==\'passive\' ">',
                '<span class="icon-delete" ext:qtip="{status}"></span>',
                '</tpl>',

                '<tpl if="status ==\'active\' ">',
                '<span class="icon-email send-password" ext:qtip="Send Password"></span>',
                '</tpl>',
                '<tpl if="status ==\'passive\' ">',
                '<span class="icon-email re-invite" ext:qtip="Reinvite {firstName} {lastName}"></span>',
                '</tpl>',

                '<span class="icon-wrench" ext:qtip="Chance {firstName} {lastName} roles "></span>',
                '<span class="icon-cancel" ext:qtip="Delete {firstName} {lastName}"></span>',

                '</div>',

                '</div>',
                '</tpl>'
                );

        Ext.apply(this, config);

        this.on('click', function(dv, index, node, event) {

            var record = dv.getStore().getAt(index).data;

            if (event.getTarget("span.icon-accept")) {
                Ext.Msg.confirm('Are you sure?', 'Do you want to passive ' + record.firstName + ' ' + record.lastName, function(button) {
                    if (button == 'yes') {
                        this.userRequest('PUT', record, 'passive');
                    }
                }, this);
            }

            if (event.getTarget("span.icon-delete")) {
                Ext.Msg.confirm('Are you sure?', 'Do you want to active ' + record.firstName + ' ' + record.lastName, function(button) {
                    if (button == 'yes') {
                        this.userRequest('PUT', record, 'active');
                    }
                }, this);
            }

            if (event.getTarget("span.send-password")) {
                this.bootstrap.layout.emailWindow.show(record.status);
            }

            if (event.getTarget("span.re-invite")) {
                this.bootstrap.layout.emailWindow.show(record.status);
            }

            if (event.getTarget("span.icon-wrench")) {
                this.bootstrap.layout.userRolesWindow.showWindow(record);
            }

            if (event.getTarget("span.icon-cancel")) {
                Ext.Msg.confirm('Are you sure?', 'Do you want to delete ' + record.firstName + ' ' + record.lastName, function(button) {
                    if (button == 'yes') {
                        this.userRequest('DELETE', record);
                    }
                }, this);
            }
        }, this);

        KebabOS.applications.userManager.application.views.UserManagerDataView.superclass.initComponent.apply(this, arguments);
    },

    userRequest: function(method, user, status) {
        Ext.Ajax.request({
            url: BASE_URL + '/user/manager',
            method: method,
            params: {
                id: user.id,
                status: status
            }
        });
        this.store.load();
    },

    listeners: {
        afterRender:function() {
            this.store.load();
        }
    }

});