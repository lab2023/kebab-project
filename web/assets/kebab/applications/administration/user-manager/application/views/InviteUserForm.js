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

    initComponent: function() {
        
        // form config
        var config = {
            url: Kebab.helper.url('kebab/user-invitation'),
            labelAlign: 'top',
            defaultType: 'textfield',
            bodyStyle: 'padding:5px 10px;',
            defaults: {
                anchor: '100%'
            },
            items:[{
                fieldLabel: 'Fullname',
                allowBlank:false,
                name: 'fullName'
            },{
                fieldLabel: 'Email',
                allowBlank:false,
                name: 'email',
                vtype:'email'
            }, {
                fieldLabel: 'Message',
                name: 'message',
                xtype: 'textarea',
                emptyText:'Hi',
                height:80
            }],
            buttons: [{
                text: 'Send',
                iconCls: 'icon-email',
                scope: this,
                handler : function() {
                    if (this.getForm().isValid()) {
                        this.getForm().submit({
                            url: this.url,
                            method: 'POST',
                            success : function() {
                                Kebab.helper.message(this.bootstrap.launcher.text, 'Success');
                                this.getForm().reset();
                            },

                            failure : function() {
                                Kebab.helper.message(this.bootstrap.launcher.text, 'Failure');
                            }, scope:this
                        });
                    }
                }
            },{
                text: 'Cancel',
                iconCls: 'icon-cancel',
                handler : function() {
                    this.bootstrap.layout.inviteUserWindow.hide();
                },
                scope:this
            }]
        };

        Ext.apply(this, config);

        KebabOS.applications.userManager.application.views.InviteUserForm.superclass.initComponent.apply(this, arguments);
    }
});