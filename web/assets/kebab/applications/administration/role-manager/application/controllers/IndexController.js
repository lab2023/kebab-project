/**
 * Kebab Application Bootstrap Class
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.roleManager.application.controllers
 * @author      Yunus ÖZCAN <yuns.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/licensing
 */
KebabOS.applications.roleManager.application.controllers.Index = Ext.extend(Ext.util.Observable, {

    // Application bootstrap
    bootstrap: null,

    constructor: function(config) {

        // Base Config
        Ext.apply(this, config || {});

        // Call Superclass initComponent() method
        KebabOS.applications.roleManager.application.controllers.Index.superclass.constructor.apply(this, arguments);

        this.init();
    },

    // Initialize and define routing settings
    init: function() {
        this.bootstrap.layout.roleForm.on('roleFormOnSave', this.formOnSaveAction, this);
        this.bootstrap.layout.roleForm.on('loadGrid', this.loadGridAction, this);
        this.bootstrap.layout.mainCenter.eastCenter.roleManagerStoryGrid.on('request', this.requestAction, this);
        this.bootstrap.layout.mainCenter.roleManagerGrid.on('loadParamsGrid', this.loadParamsGridAction, this);
    },

    // Actions -----------------------------------------------------------------
    loadGridAction: function(component) {
        component.load();
    },

    loadParamsGridAction: function(data) {
        data.store.load({params: {roleId: data.roleId}});
    },

    formOnSaveAction: function(data) {

        if (data.from.getForm().isValid()) {
            var notification = new Kebab.OS.Notification();

            data.from.getForm().submit({
                url: data.url,
                method: 'POST',

                success : function() {
                    notification.message(this.bootstrap.launcher.text, 'Success');
                    data.from.getForm().reset();
                    if (data.store) {
                         data.from.fireEvent('loadGrid', data.store);
                    }
                },

                failure : function() {
                    notification.message(this.bootstrap.launcher.text, 'Failure');
                }, scope:this
            });
        }
    },
    
    requestAction: function(data) {
        var notification = new Kebab.OS.Notification();
        Ext.Ajax.request({
            url: BASE_URL + data.url,
            method: data.method,
            params: {

                id: data.roleId,
                story: data.story
            },
            success : function() {
                notification.message(this.bootstrap.launcher.text, 'Success');
            },

            failure : function() {
                notification.message(this.bootstrap.launcher.text, 'Failure');
            }, scope:this
        });
    }

});
