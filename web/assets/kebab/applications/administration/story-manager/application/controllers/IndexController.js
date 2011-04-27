/**
 * Kebab Application Bootstrap Class
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.storyManager.application.controllers
 * @author      Yunus ÖZCAN <yuns.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/licensing
 */
KebabOS.applications.storyManager.application.controllers.Index = Ext.extend(Ext.util.Observable, {

    // Application bootstrap
    bootstrap: null,

    constructor: function(config) {

        // Base Config
        Ext.apply(this, config || {});

        // Call Superclass initComponent() method
        KebabOS.applications.storyManager.application.controllers.Index.superclass.constructor.apply(this, arguments);

        this.init();
    },

    // Initialize and define routing settings
    init: function() {
        this.bootstrap.layout.storyManagerGrid.on('statusChanged', this.statusChangeAction, this);
        this.bootstrap.layout.storyManagerGrid.on('loadGrid', this.loadGridAction, this);
    },

    // Actions -----------------------------------------------------------------

    statusChangeAction: function(data) {
        Ext.Ajax.request({
            url: BASE_URL + '/access/story-manager',
            method:'PUT',
            params: { id: data.id, status: data.status }
        });

    },

    loadGridAction: function(data) {
        data.load();
    }
});
