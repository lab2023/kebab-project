/**
 * roleManager Application RoleForm
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.roleManager.application.views
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @author      Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 */
KebabOS.applications.roleManager.application.views.RoleForm = Ext.extend(Ext.form.FormPanel, {

    // Application bootstrap
    bootstrap: null,
    //POST url
    url : '/kebab/role',

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
                    name: 'name',
                    xtype: 'textfield'
                },
                {
                    fieldLabel: 'Title',
                    allowBlank:false,
                    name: 'title',
                    xtype: 'textfield'
                },
                {
                    fieldLabel: 'Description',
                    allowBlank:false,
                    name: 'description',
                    xtype: 'textarea',
                    height:235
                },
                {
                    name:'active',
                    xtype:'hidden',
                    value: true
                }

            ],
            buttons: [
                {
                    text: 'Create',
                    iconCls: 'icon-accept',
                    scope: this,
                    handler : this.onSubmit
                }
            ]
        }

        this.addEvents('roleFormOnSave');
        this.addEvents('loadGrid');

        Ext.apply(this, Ext.apply(this.initialConfig, config));

        KebabOS.applications.roleManager.application.views.RoleForm.superclass.initComponent.apply(this, arguments);
    },

    onSubmit: function() {
        this.fireEvent('roleFormOnSave', {from:this, url:this.url, store: this.bootstrap.layout.mainCenter.roleManagerGrid.store});
    }

});
