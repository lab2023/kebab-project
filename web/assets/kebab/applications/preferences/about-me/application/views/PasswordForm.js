/**
 * Kebab Application PasswordForm
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.aboutMe.application.views
 * @author      Tayfun Öziş ERİKAN <tayfun.ozis.erikan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/licensing
 */
KebabOS.applications.aboutMe.application.views.PasswordForm = Ext.extend(Ext.form.FormPanel, {

    // Application bootstrap
    bootstrap: null,

    title:' Change Password',
    iconCls: 'icon-key',
    url: BASE_URL + '/user/password',
    defaultType: 'textfield',
    border:false,
    bodyStyle: 'padding:5px 10px;',
    defaults: {
        labelAlign: 'top',
        anchor: '100%',
        inputType: 'password',
        allowBlank: false
    },

    initComponent: function() {

        Ext.apply(Ext.form.VTypes, {
            password : function(val, field) {
                if (field.initialPassField) {
                    var pwd = Ext.getCmp(field.initialPassField);
                    return (val == pwd.getValue());
                }
                return true;
            },
            passwordText : 'Passwords do not match!'
        });

        var config = {
            items: [{
                xtype:'panel',
                layout: 'form',
                border:false,
                defaults: {
                    labelAlign: 'top',
                    anchor: '100%',
                    inputType: 'password',
                    allowBlank: false
                },
                autoHeight:true,
                defaultType: 'textfield',
                items: [{
                    fieldLabel: 'Your current password',
                    name: 'oldPassword'
                },{
                    fieldLabel: 'New password',
                    name: 'newPassword',
                    id: 'aboutMe-application-newPasword'
                },{
                    fieldLabel: 'Confirm new password',
                    name: 'newPasswordConfirm',
                    vtype: 'password',
                    initialPassField: 'aboutMe-application-newPasword'
                }]
            }],
            buttons: [{
                text: 'Cancel',
                iconCls: 'icon-cancel',
                handler: function() {
                    this.fireEvent('showHidePasswordForm');
                },
                scope: this
            }, {
                text: 'Save',
                iconCls: 'icon-disk',
                handler: this.onUpdate,
                scope: this,
                handler: this.onSave
            }]
        }

        this.addEvents('showHidePasswordForm');
        
        Ext.apply(this, Ext.apply(this.initialConfig, config));
        
        KebabOS.applications.aboutMe.application.views.PasswordForm.superclass.initComponent.apply(this, arguments);
    },
    
    onSave: function() {
        
        if (this.getForm().isValid()) {

            var notification = new Kebab.OS.Notification();

            this.getForm().submit({

                url: this.url,

                method: 'PUT',
                
                success : function() {
                    notification.message(this.bootstrap.launcher.text, 'Success');
                    this.getForm().reset();
                    this.fireEvent('showHidePasswordForm');
                },
                
                failure : function() {
                    notification.message(this.bootstrap.launcher.text, 'Failure');
                },
                
                scope:this
            });
        }
    }
});
