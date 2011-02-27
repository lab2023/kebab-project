KebabOS.applications.aboutMe.application.views.MainForm = Ext.extend(Ext.form.FormPanel, {
    
    url: 'preferences/about-me/read',
    defaultType: 'textfield',    
    bodyStyle: 'padding:0 10px;',    
    defaults: {
        labelAlign: 'top',
        anchor: '100%'
    },
    
    initComponent: function() {
        
        var config = {
            items: [{
                layout:'column',
                bodyStyle: 'padding:20px 0;',
                xtype:'panel',
                border:false,
                items: [{
                    columnWidth: .5,
                    bodyCssClass: 'aboutMe-application-firstNameSurnameText',
                    id: 'aboutMe-application-firstNameLastName-text',
                    html: '-',
                    border:false
                },{
                    columnWidth: .5,
                    xtype: 'panel',
                    border:false,
                    bodyStyle: 'text-align:center;',
                    defaults:{border:false},
                    items: [{                        
                        bodyCssClass: 'aboutMe-application-emailText',
                        id: 'aboutMe-application-email-text',
                        html: '-'
                    },{
                        xtype: 'button',
                        width: '100%',
                        iconCls: 'icon-key',
                        text: 'Change Password'
                    }]
                }]
            },{
                name: 'customerId',
                hidden:true
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
                }, new Ext.form.ComboBox({
                    fieldLabel: 'Your language',
                    typeAhead: true,
                    triggerAction: 'all',
                    lazyRender:true,
                    mode: 'local',
                    store: new Ext.data.JsonStore({
                        fields: ['language', 'iconCls','text'],
                        data: this.ownerApplication.app.getLanguages()
                    }),
                    valueField: 'language',
                    displayField: 'text',
                    scope:this
                })]
            }],
            buttons: [{
                text: 'Save',
                iconCls: 'icon-disk',
                handler: this.onUpdate,
                scope: this,
                disabled: true
            }]
        }
    
        Ext.apply(this, Ext.apply(this.initialConfig, config));
        
        KebabOS.applications.aboutMe.application.views.MainForm.superclass.initComponent.apply(this, arguments);
    },
    
    listeners: {
        actioncomplete:function(form, response) {
            
            Ext.getCmp('aboutMe-application-firstNameLastName-text')
               .update(response.result.data.firstName + " " + response.result.data.lastName);
            Ext.getCmp('aboutMe-application-email-text')
               .update(response.result.data.email);
        }
    },
    
    onRender:function() {
        
        this.load({
            url:this.url            
        });
        
        KebabOS.applications.aboutMe.application.views.MainForm.superclass.onRender.apply(this, arguments);
        
    },
    
    onUpdate: function() {
        
        if (this.getForm().isValid()) {
            
            this.getForm().submit({
                
                url: 'preferences/about-me/update',
                
                waitMsg: 'Updating...',
                
                failure : function(m, o) {
                    Kebab.OS.Notification.message('Bilgilendirme', 'Update failure...', true);
                },
                
                success : function(m, o) {
                    Kebab.OS.Notification.message('Bilgilendirme', 'Update completed...');
                },
                
                scope:this
            });
        }
    }
});
