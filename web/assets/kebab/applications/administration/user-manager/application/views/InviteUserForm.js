/**
 * userManager Application InviteUserForm class
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.userManager.application.views
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 */
KebabOS.applications.userManager.application.views.InviteUserForm = Ext.extend(Ext.form.FormPanel, {

    // Application bootstrap
    bootstrap: null,
    //POST url
    url : '/user/invite',

    bodyStyle: 'padding:5px 10px;',

    initComponent: function() {

        // form config
        var config = {
            labelAlign: 'top',
            defaultType: 'textfield',
            defaults: {
                anchor: '100%'
            },
            items:[
                {
                    fieldLabel: 'Fullname',
                    allowBlank:false,
                    name: 'fullName'
                },
                {
                    fieldLabel: 'Email',
                    allowBlank:false,
                    name: 'email',
                    vtype:'email'
                },
                {
                    fieldLabel: 'Message',
                    name: 'message',
                    xtype: 'textarea',
                    height:80
                }
            ],
            buttons: [
                {
                    text: 'Send',
                    iconCls: 'icon-email',
                    scope: this,
                    handler : this.onSubmit
                },
                {
                    text: 'Cancel',
                    iconCls: 'icon-cancel',
                    handler : function() {
                        this.fireEvent('hideWindow', this.bootstrap.layout.inviteUserWindow);
                    },
                    scope:this
                }
            ]
        };

        this.addEvents('inviteUserFormOnSave');
        this.addEvents('hideWindow');
        this.addEvents('loadGrid');

        Ext.apply(this, Ext.apply(this.initialConfig, config));

        KebabOS.applications.userManager.application.views.InviteUserForm.superclass.initComponent.apply(this, arguments);
    },

    onSubmit: function() {
        this.fireEvent('inviteUserFormOnSave', {from:this, fromWindow:this.bootstrap.layout.inviteUserWindow, url:this.url, store:this.bootstrap.layout.userManagerDataView.store});
    }
});
