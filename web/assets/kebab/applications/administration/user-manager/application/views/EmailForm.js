/**
 * userManager Application EmailForm class
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.userManager.application.views
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/licensing
 */
KebabOS.applications.userManager.application.views.EmailForm = Ext.extend(Ext.form.FormPanel, {

    // Application bootstrap
    bootstrap: null,

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
                    fieldLabel: 'Email',
                    allowBlank:false,
                    name: 'email',
                    height:30,
                    vtype:'email'
                }
            ],
            buttons: [
                {
                    text: 'Send',
                    iconCls: 'icon-email',
                    scope: this,
                    handler : this.onSubmit
                },
                {
                    text: 'Cancel',
                    iconCls: 'icon-cancel',
                    handler : function() {
                        this.fireEvent('hideWindow', this.bootstrap.layout.emailWindow);
                    },
                    scope:this
                }
            ]
        };

        this.addEvents('emailFormOnSave');
        this.addEvents('hideWindow');

        Ext.apply(this, Ext.apply(this.initialConfig, config));

        KebabOS.applications.userManager.application.views.EmailForm.superclass.initComponent.apply(this, arguments);
    },

    onSubmit: function() {
        this.fireEvent('emailFormOnSave', {from:this, fromWindow:this.bootstrap.layout.emailWindow, url:this.url});
    }
});
