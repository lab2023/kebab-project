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
                    '<span>{name}</span>   ',
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

        this.on('click', function(dv,index,node,event) {

            if (event.getTarget("span.icon-accept")) {

            }

            if (event.getTarget("span.icon-delete")) {

            }

            if (event.getTarget("span.send-password")) {

            }

            if (event.getTarget("span.re-invite")) {

            }

            if (event.getTarget("span.icon-wrench")) {

            }

            if (event.getTarget("span.icon-cancel")) {

            }

            var recordId = dv.getStore().getAt(index);

        }, this);

        KebabOS.applications.userManager.application.views.UserManagerDataView.superclass.initComponent.apply(this, arguments);
    },

    listeners: {
        afterRender:function() {
            this.store.load();

        }
    }

});