/**
 * roleManager Application RoleForm
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.roleManager.application.views
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/licensing
 */
KebabOS.applications.roleManager.application.views.RoleForm = Ext.extend(Ext.form.FormPanel, {

    // Application bootstrap
    bootstrap: null,
    //POST url
    url : BASE_URL + '/role/manager',

    initComponent: function() {

        // form config
        var config = {
            title:'Add new role',
            iconCls:'icon-user-add',
            region: 'west',
            width:275,
            collapseMode:'mini',
            split:true,
            border:false,
            bodyStyle: 'padding:5px 10px;',
            labelAlign: 'top',
            defaults: {
                anchor: '100%'
            },

            items: [
                {
                    fieldLabel: 'Name',
                    allowBlank:false,
                    name: 'description',
                    xtype: 'textfield'
                },
                {
                    fieldLabel: 'Title',
                    allowBlank:false,
                    name: 'description',
                    xtype: 'textfield'
                },
                {
                    fieldLabel: 'Description',
                    allowBlank:false,
                    name: 'description',
                    xtype: 'textarea',
                    height:235
                }

            ],
            buttons: [
                {
                    text: 'Create',
                    iconCls: 'icon-email',
                    scope: this,
                    handler : this.onSave
                }
            ]
        }

        Ext.apply(this, Ext.apply(this.initialConfig, config));

        KebabOS.applications.roleManager.application.views.RoleForm.superclass.initComponent.apply(this, arguments);
    },

    onSave: function() {

        if (this.getForm().isValid()) {

            var notification = new Kebab.OS.Notification();

            this.getForm().submit({

                url: this.url,

                method: 'POST',

                //waitMsg: 'Updating...',

                success : function() {
                    notification.message(this.bootstrap.launcher.text, 'Success');
                    this.fireEvent('loadGrid');
                    this.getForm().reset();
                },

                failure : function() {
                    notification.message(this.bootstrap.launcher.text, 'Failure');
                },

                scope:this
            });
        }
    }

});
