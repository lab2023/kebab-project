/**
 * Kebab Application Bootstrap Class
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.feedback.application.controllers
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/licensing
 */
KebabOS.applications.feedback.application.controllers.Index = Ext.extend(Ext.util.Observable, {

    // Application bootstrap
    bootstrap: null,

    constructor: function(config) {

        // Base Config
        Ext.apply(this, config || {});

        // Call Superclass initComponent() method
        KebabOS.applications.feedback.application.controllers.Index.superclass.constructor.apply(this, arguments);

        this.init();
    },

    // Initialize and define routing settings
    init: function() {
        this.bootstrap.layout.feedbackForm.on('loadFeedbackGrid', this.loadGridAction, this);
        this.bootstrap.layout.feedbackForm.on('feedbackFormOnSave', this.formOnSaveAction, this);

    },

    // Actions -----------------------------------------------------------------
    loadGridAction: function() {
        this.bootstrap.layout.feedbackGrid.store.load();
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
                },

                failure : function() {
                    notification.message(this.bootstrap.launcher.text, 'Failure');
                }, scope:this
            });
        }
    }
});
