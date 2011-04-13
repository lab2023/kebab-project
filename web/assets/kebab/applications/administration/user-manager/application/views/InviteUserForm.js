/**
 * userManager Application InviteUserForm class
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.userManager.application.views
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/licensing
 */
KebabOS.applications.userManager.application.views.InviteUserForm = Ext.extend(Ext.form.FormPanel, {

    // Application bootstrap
    bootstrap: null,
    //POST url
    url : BASE_URL + '/user/manager',

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
                    fieldLabel: 'First Name',
                    allowBlank:false,
                    name: 'firstName'
                },
                {
                   fieldLabel: 'Last Name',
                    allowBlank:false,
                    name: 'lastName'
                },
                {
                    fieldLabel: 'Email',
                    allowBlank:false,
                    name: 'email'
                },{
                    fieldLabel: 'Message',
                    allowBlank:false,
                    name: 'message',
                    xtype: 'textarea',
                    height:100
                }
            ],
            bbar:[
                '->',{
                    text:'Invite Send',
                    iconCls:'icon-email'
                }
            ]
        }

        Ext.apply(this, Ext.apply(this.initialConfig, config));

        KebabOS.applications.userManager.application.views.InviteUserForm.superclass.initComponent.apply(this, arguments);
    }
});
