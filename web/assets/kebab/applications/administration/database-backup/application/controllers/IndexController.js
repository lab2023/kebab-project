/**
 * Kebab Application Bootstrap Class
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.databaseBackup.application.controllers
 * @author      Yunus ÖZCAN <yuns.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 */
KebabOS.applications.databaseBackup.application.controllers.Index = Ext.extend(Ext.util.Observable, {

    // Application bootstrap
    bootstrap: null,

    constructor: function(config) {

        // Base Config
        Ext.apply(this, config || {});

        // Call Superclass initComponent() method
        KebabOS.applications.databaseBackup.application.controllers.Index.superclass.constructor.apply(this, arguments);

        this.init();
        this.bootstrap.layout.databaseBackupGrid.on('deleteRequest', this.RequestAction, this);
        this.bootstrap.layout.databaseBackupGrid.on('backupRequest', this.RequestAction, this);
        this.bootstrap.layout.databaseBackupGrid.on('loadGrid', this.loadGridAction, this);
    },

    // Initialize and define routing settings
    init: function() {

    },

    // Actions -----------------------------------------------------------------
    loadGridAction: function(component) {
        component.load();
    },
    RequestAction: function(data) {
        var notification = new Kebab.OS.Notification();
        Ext.Ajax.request({
            url: data.url,
            method: data.method,
            params: {
                fileName: data.name
            },
            success : function() {
                notification.message(this.bootstrap.launcher.text, 'Success');
                if (data.store) {
                    data.from.fireEvent('loadGrid', data.store);
                }
            },

            failure : function() {
                notification.message(this.bootstrap.launcher.text, 'Failure');
            }, scope:this
        });
    }
});
