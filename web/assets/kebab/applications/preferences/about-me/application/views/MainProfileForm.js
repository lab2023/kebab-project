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
KebabOS.applications.aboutMe.application.views.MainProfileForm = Ext.extend(Ext.form.FormPanel, {

    url: '/kebab/profile',
    defaultType: 'textfield',
    border:false,
    bodyStyle: 'padding:5px 10px;',
    defaults: {
        labelAlign: 'top',
        anchor: '100%'
    },
    
    initComponent: function() {

        var languges = {
            all: Kebab.OS.getLanguages(),
            current: Kebab.OS.getLanguages('current')
        };

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
                        bodyCssClass: 'aboutMe-application-userNameText',
                        id: 'aboutMe-application-userName-text',
                        html: '-'
                    },{
                        xtype: 'button',
                        width: '100%',
                        iconCls: 'icon-key',
                        text: 'Change Password',
                        handler: function() {
                            this.fireEvent('showHidePasswordForm', this.bootstrap.layout.passwordForm);
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
                    fieldLabel: 'Fullname',
                    name: 'fullName',
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
                scope: this,
                handler: this.onSubmit
            }]
        }

        this.addEvents('showHidePasswordForm');
        this.addEvents('mainProfileFormOnSave');

        Ext.apply(this, Ext.apply(this.initialConfig, config));
        
        KebabOS.applications.aboutMe.application.views.MainProfileForm.superclass.initComponent.apply(this, arguments);
    },

    listeners: {
        actioncomplete:function(form, response) {
            //KBBTODO response error 
            if(response.result.data.id) {
                this.bootstrap.layout.enable();
                Ext.getCmp('aboutMe-application-firstNameLastName-text')
                   .update(response.result.data.fullName);
                Ext.getCmp('aboutMe-application-userName-text')
                   .update(response.result.data.userName);
            }
        }
    },
    
    onRender:function() {

        this.load({
            url: BASE_URL + this.url + '/' + this.bootstrap.app.getUser().id,
            method: 'GET'
        });
        
        KebabOS.applications.aboutMe.application.views.MainProfileForm.superclass.onRender.apply(this, arguments);
    },

    onSubmit: function() {
        this.fireEvent('mainProfileFormOnSave', {from:this, url:this.url});
    }
});
