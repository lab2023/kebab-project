/**
 * Kebab Application FeedbackForm
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.feedback.application.views
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/licensing
 */
KebabOS.applications.feedback.application.views.FeedbackForm = Ext.extend(Ext.form.FormPanel, {

    // Application bootstrap
    bootstrap: null,
    //POST url
    url : BASE_URL + '/feedback/feedback',

    bodyStyle: 'padding:5px 10px;',

    initComponent: function() {

        var userId = this.bootstrap.app.getUser().id;

        var applications = this.bootstrap.app.getApplications();

        var applicationsCombobox = new Ext.form.ComboBox({
            fieldLabel: 'Application name',
            typeAhead: true,
            triggerAction: 'all',
            forceSelection: true,
            lazyRender:false,
            allowBlank:false,
            mode: 'local',
            store: new Ext.data.JsonStore({
                fields: ['name', 'id',{name:'title', type: 'object', mapping: 'launcher.text'}],
                data: applications
            }),
            valueField: 'id',
            displayField: 'title',
            hiddenName: 'applicationIdentity',
            scope:this
        });

        // form config
        var config = {

            items: [
                {
                    xtype:'panel',
                    layout: 'form',
                    border:false,
                    labelAlign: 'top',
                    defaults: {
                        anchor: '100%'
                    },
                    items: [
                        {
                            name:'userId',
                            xtype:'hidden',
                            value: userId
                        },
                        applicationsCombobox ,
                        {
                            fieldLabel: 'Description',
                            allowBlank:false,
                            name: 'description',
                            xtype: 'textarea',
                            height:235
                        }
                    ]
                }
            ],
            buttons: [
                {
                    text: 'Send',
                    iconCls: 'icon-email',
                    scope: this,
                    handler : this.onSave
                }
            ]

        }

        Ext.apply(this, Ext.apply(this.initialConfig, config));

        KebabOS.applications.feedback.application.views.FeedbackForm.superclass.initComponent.apply(this, arguments);
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
