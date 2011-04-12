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
            autoScroll:true
        }

        // Template
        this.tpl = new Ext.XTemplate(
                '<table cellpadding="0" cellspacing="0" width="100%">',
                '<tpl for=".">',
                '<tr class="userManager-application-user-information">',
                '<td class="userManager-application-user-photo"></td>',
                '<td class="userManager-application-user-info-text" ><span style="font-size:18pt;">{firstName} {lastName}</span><br /><span>{username}</span>, <span>{email}</span><br /><span>{Roles}</span></td>',
                '<td class="userManager-application-user-buttons">',
                '<tpl if="status ==\'active\' ">',
                '<div class="icon-accept" style="background-repeat:no-repeat; width:16px; height:16px; margin-right:10px; float:left;" ext:qtip="{status}"></div>',
                '</tpl>',
                '<tpl if="status ==\'passive\' ">',
                '<div class="icon-cancel" style="background-repeat:no-repeat; width:16px; height:16px; margin-right:10px; float:left;" ext:qtip="{status}"></div>',
                '</tpl>',

                '<tpl if="status ==\'active\' ">',
                '<div class="icon-email" style="background-repeat:no-repeat; width:16px; height:16px; margin-right:10px; float:left;" ext:qtip="Send Password"></div>',
                '</tpl>',
                '<tpl if="status ==\'passive\' ">',
                '<div class="icon-email" style="background-repeat:no-repeat; width:16px; height:16px; margin-right:10px; float:left;" ext:qtip="Reinvite {username}"></div>',
                '</tpl>',

                '<div class="icon-wrench" style="background-repeat:no-repeat; width:16px; height:16px; margin-right:10px; float:left;" ext:qtip="Chance {username} roles "></div>',
                '<div class="icon-cancel" style="background-repeat:no-repeat; width:16px; height:16px; margin-right:10px; float:left;" ext:qtip="Delete {username}"></div>',
                '</td>',
                '</tr>',
                '</tpl>',
                '</table>'
                );

        this.bbar = this.buildBbar();

        Ext.apply(this, config);

        KebabOS.applications.userManager.application.views.UserManagerDataView.superclass.initComponent.apply(this, arguments);
    },

    buildBbar: function() {
        return  new Kebab.library.ext.ExtendedPagingToolbar({
            store: this.store
        });
    },

    listeners: {
        afterRender:function() {
            this.store.load();

        }
    }

});