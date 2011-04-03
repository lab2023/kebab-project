/**
 * Kebab Application MainForm
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.aboutMe.application.views
 * @author      Tayfun Öziş ERİKAN <tayfun.ozis.erikan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/licensing
 */
KebabOS.applications.aboutMe.application.views.MainForm = Ext.extend(Ext.form.FormPanel, {

    url: 'about-me/get-own-user-info',
    defaultType: 'textfield',
    border:false,
    bodyStyle: 'padding:5px 10px;',
    defaults: {
        labelAlign: 'top',
        anchor: '100%'
    },
    
    initComponent: function() {

        var languges = {
            all: this.bootstrap.app.getLanguages(),
            current: this.bootstrap.app.getLanguages('current')
        }

        var languagesCombobox = new Ext.form.ComboBox({
            fieldLabel: 'Your language',
            typeAhead: true,
            triggerAction: 'all',
            forceSelection: true,
            lazyRender:false,
            mode: 'local',
            store: new Ext.data.JsonStore({
                fields: ['language', 'iconCls','text'],
                data: languges.all
            }),
            valueField: 'language',
            displayField: 'text',
            hiddenName: 'language',
            scope:this,
            listeners: {
                afterRender: function() {
                    this.setValue(languges.current.language);
                }
            }
        });


        var config = {
            items: [{
                layout:'column',
                bodyStyle: 'padding:5px 0;',
                xtype:'panel',
                border:false,
                items: [{
                    bodyCssClass: 'aboutMe-application-userPicture',
                    border:true
                },{
                    columnWidth: .6,
                    bodyCssClass: 'aboutMe-application-firstNameSurnameText',
                    id: 'aboutMe-application-firstNameLastName-text',
                    html: '-',
                    border:false
                },{
                    columnWidth: .4,
                    xtype: 'panel',
                    border:false,
                    bodyStyle: 'text-align:center; margin-top:5px;',
                    defaults:{border:false},
                    items: [{
                        bodyCssClass: 'aboutMe-application-usernameText',
                        id: 'aboutMe-application-username-text',
                        html: '-'
                    },{
                        xtype: 'button',
                        width: '100%',
                        iconCls: 'icon-key',
                        text: 'Change Password',
                        handler: function() {
                            this.fireEvent('showHidePasswordForm');
                        },
                        scope:this
                    }]
                }]
            },{
                xtype:'panel',
                layout: 'form',
                padding: 10,
                defaults: {
                    anchor: '100%'
                },
                autoHeight:true,
                defaultType: 'textfield',
                items: [{
                    fieldLabel: 'First name',
                    name: 'firstName',
                    allowBlank: false
                },{
                    fieldLabel: 'Last name',
                    name: 'lastName',
                    allowBlank: false
                },{
                    fieldLabel: 'E-mail',
                    name: 'email',
                    vtype: 'email',
                    allowBlank: false
                }, languagesCombobox
                ]
            }],
            buttons: [{
                text: 'Save',
                iconCls: 'icon-disk',
                handler: this.onUpdate,
                scope: this,
                handler: this.onSave
            }]
        }

        this.addEvents('showHidePasswordForm');

        Ext.apply(this, Ext.apply(this.initialConfig, config));
        
        KebabOS.applications.aboutMe.application.views.MainForm.superclass.initComponent.apply(this, arguments);
    },

    listeners: {
        actioncomplete:function(form, response) {
            if(response.result.data.id) {
                this.bootstrap.layout.enable();
                Ext.getCmp('aboutMe-application-firstNameLastName-text')
                   .update(response.result.data.firstName + " " + response.result.data.lastName);
                Ext.getCmp('aboutMe-application-username-text')
                   .update(response.result.data.username);
            }
        }
    },
    
    onRender:function() {

        this.load({
            url:this.url + '/' + this.bootstrap.app.getUser().id,
            method: 'GET'
        });
        
        KebabOS.applications.aboutMe.application.views.MainForm.superclass.onRender.apply(this, arguments);
        
    },
    
    onSave: function() {
        
        if (this.getForm().isValid()) {

            var notification = new Kebab.OS.Notification();

            this.getForm().submit({

                url: this.url + '/' + this.bootstrap.app.getUser().id,
                method: 'PUT',
                params : {
                    id : this.bootstrap.app.getUser().id
                },

                
                //waitMsg: 'Updating...',
                
                success : function() {
                    notification.message(this.bootstrap.launcher.text, 'Success');
                },
                
                failure : function() {
                    notification.message(this.bootstrap.launcher.text, 'Failure');
                },
                
                scope:this
            });
        }
    }
});
