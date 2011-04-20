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

                '<span class="icon-wrench" ext:qtip="Change {firstName} {lastName} roles "></span>',
                '<span class="icon-cancel" ext:qtip="Delete {firstName} {lastName}"></span>',

                '</div>',

                '</div>',
                '</tpl>'
                );

        this.addEvents('userRequest');
        this.addEvents('loadGrid');
        this.addEvents('showEmailWindow');
        this.addEvents('showUserRoleWindow');

        Ext.apply(this, config);

        this.on('click', function(dv, index, node, event) {

            var record = dv.getStore().getAt(index).data;

            if (event.getTarget("span.icon-accept")) {
                Ext.Msg.confirm('Are you sure?', 'Do you want to passive ' + record.firstName + ' ' + record.lastName, function(button) {
                    if (button == 'yes') {
                        this.fireEvent('userRequest', {from:this, method:'PUT', user:record, status:'passive', url:'/user/manager', store:this.bootstrap.layout.userManagerDataView.store});
                    }
                }, this);
            }

            if (event.getTarget("span.icon-delete")) {
                Ext.Msg.confirm('Are you sure?', 'Do you want to active ' + record.firstName + ' ' + record.lastName, function(button) {
                    if (button == 'yes') {
                        this.fireEvent('userRequest', {from:this, method:'PUT', user:record, status:'active', url:'/user/manager', store:this.bootstrap.layout.userManagerDataView.store});
                    }
                }, this);
            }

            if (event.getTarget("span.send-password")) {
                this.fireEvent('showEmailWindow', record);
            }

            if (event.getTarget("span.re-invite")) {
                this.fireEvent('showEmailWindow', record);
            }

            if (event.getTarget("span.icon-wrench")) {
                this.fireEvent('showUserRoleWindow', record);
            }

            if (event.getTarget("span.icon-cancel")) {
                Ext.Msg.confirm('Are you sure?', 'Do you want to delete ' + record.firstName + ' ' + record.lastName, function(button) {
                    if (button == 'yes') {
                        this.fireEvent('userRequest', {from:this, method:'DELETE', user:record, url:'/user/manager', store:this.bootstrap.layout.userManagerDataView.store});
                    }
                }, this);
            }
        }, this);

        KebabOS.applications.userManager.application.views.UserManagerDataView.superclass.initComponent.apply(this, arguments);
    },

    listeners: {
        afterRender:function() {
            this.store.load();
        }
    }

});