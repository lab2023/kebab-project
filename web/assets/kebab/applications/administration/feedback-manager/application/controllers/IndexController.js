/**
 * FeedbackManager Application Controller Class
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.feedbackManager.application.controllers
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 */
KebabOS.applications.feedbackManager.application.controllers.Index = Ext.extend(Ext.util.Observable, {

    // Application bootstrap
    bootstrap: null,

    constructor: function(config) {

        // Base Config
        Ext.apply(this, config || {});

        // Call Superclass initComponent() method
        KebabOS.applications.feedbackManager.application.controllers.Index.superclass.constructor.apply(this, arguments);

        this.init();
    },

    // Initialize and define routing settings
    init: function() {
        this.bootstrap.layout.feedbackManagerGrid.on('statusChanged', this.statusChangeAction, this);
        this.bootstrap.layout.feedbackManagerGrid.on('loadGrid', this.loadGridAction, this);
    },

    // Actions -----------------------------------------------------------------

    statusChangeAction: function(data) {
        Ext.Ajax.request({
            url: Kebab.OS.generateUrl('kebab/feedback-manager'),
            method:'PUT',
            params: { id: data.id, status: data.status }
        });
        data.from.fireEvent('loadGrid', data.store);
    },

    loadGridAction: function(data) {
        data.load();
    }
});