/**
 * Kebab Application PasswordForm
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.aboutMe.application.views
 * @author      Tayfun Öziş ERİKAN <tayfun.ozis.erikan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 */
KebabOS.applications.aboutMe.application.views.PasswordForm = Ext.extend(Ext.form.FormPanel, {

    // Application bootstrap
    bootstrap: null,

    initComponent: function() {

        Ext.apply(Ext.form.VTypes, {
            password : function(val, field) {
                if (field.initialPassField) {
                    var pwd = Ext.getCmp(field.initialPassField);
                    return (val == pwd.getValue());
                }
                return true;
            },
            passwordText : Kebab.helper.translate('Passwords do not match!')
        });

        var config = {
            frame:true,
            title: Kebab.helper.translate('Change Password'),
            iconCls: 'icon-key',
            url: Kebab.helper.url('kebab/password'),
            defaultType: 'textfield',
            border:false,
            bodyStyle: 'padding:5px 10px;',
            defaults: {
                labelAlign: 'top',
                anchor: '100%',
                inputType: 'password',
                allowBlank: false
            },
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
                    fieldLabel: Kebab.helper.translate('Your current password'),
                    name: 'oldPassword'
                },{
                    fieldLabel: Kebab.helper.translate('New password'),
                    name: 'newPassword',
                    id: 'aboutMe-application-newPasword'
                },{
                    fieldLabel: Kebab.helper.translate('Confirm new password'),
                    name: 'newPasswordConfirm',
                    vtype: 'password',
                    initialPassField: 'aboutMe-application-newPasword'
                }]
            }],
            buttons: [{
                text: Kebab.helper.translate('Cancel'),
                iconCls: 'icon-cancel',
                handler: function() {
                    this.fireEvent('showHideForms', 0);
                },
                scope: this
            }, {
                text: Kebab.helper.translate('Save'),
                iconCls: 'icon-disk',
                scope: this,
                handler: this.onSubmit
            }]
        };

        this.addEvents('showHideForms');
        this.addEvents('formOnSave');
        
        Ext.apply(this, Ext.apply(this.initialConfig, config));
        
        KebabOS.applications.aboutMe.application.views.PasswordForm.superclass.initComponent.apply(this, arguments);
    },

    onSubmit: function() {
        this.fireEvent('formOnSave', {from:this, url:this.url, goBack:0});
    }
});
