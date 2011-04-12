/**
 * userManager Application UserManagerDataView
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.userManager.application.models
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
                '<td class="userManager-application-user-buttons"><span class="icon-accept" style="background-repeat:no-repeat; width:16px;height:16px; padding-left:20px; font-size:8pt;">{status}</span></td>',
                '</tr>',
                '</tpl>',
                '</table>'
                );

        Ext.apply(this, config);

        KebabOS.applications.userManager.application.views.UserManagerDataView.superclass.initComponent.apply(this, arguments);
    },

    listeners: {
        afterRender:function() {
            this.store.load();

        }
    }

});