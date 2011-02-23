Ext.namespace('KebabOS.applications.AboutMeApplication.library');
KebabOS.applications.AboutMeApplication.library.MainForm = Ext.extend(Ext.form.FormPanel, {
    
    url: 'preferences/about-me/read',
    defaultType: 'textfield',    
    padding: 5,    
    defaults: {
        labelAlign: 'top',
        anchor: '100%'
    },
    
    listeners: {
        actioncomplete:function(form, response) {
            console.log(response.result);
        }
    },


    initComponent: function() {
        
        var config = {
            items: [{
                layout:'column',
                padding: 10,
                xtype:'panel',
                border:false,
                items: [{
                    columnWidth: .6,
                    bodyStyle: 'font-size:20px; margin-top:10px; font-weight:bold;',
                    html: 'First Name Surname',
                    border:false
                }, {
                    columnWidth: .4,
                    xtype: 'panel',
                    border:false,
                    bodyStyle: 'text-align:center;',
                    defaults:{border:false},
                    items: [{
                        bodyStyle: 'font-size:15px;',
                        html: 'username@email'
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
                xtype:'fieldset',
                title: 'Personal Info',
                defaults: {
                    anchor: '100%'
                },
                autoHeight:true,
                defaultType: 'textfield',
                items: [{
                    fieldLabel: 'First Name',
                    name: 'firstName',
                    allowBlank: false
                },{
                    fieldLabel: 'Last Name',
                    name: 'surname',
                    allowBlank: false
                },{
                    fieldLabel: 'E-mail',
                    name: 'email',
                    vtype: 'email',
                    allowBlank: false
                }]
            }],
            buttons: [{
                text: 'Save',
                iconCls: 'icon-disk',
                handler: this.onUpdate,
                scope: this
            }]
        }
    
        Ext.apply(this, Ext.apply(this.initialConfig, config));
        
        KebabOS.applications.AboutMeApplication.library.MainForm.superclass.initComponent.apply(this, arguments);
    },
    
    onRender:function() {
        
        this.load({
            url:this.url            
        });
        
        KebabOS.applications.AboutMeApplication.library.MainForm.superclass.onRender.apply(this, arguments);
        
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

