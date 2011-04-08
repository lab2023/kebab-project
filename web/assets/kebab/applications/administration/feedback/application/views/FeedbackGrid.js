/**
 * Kebab Application FeedbackGrid
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.feedback.application.views
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/licensing
 */
KebabOS.applications.feedback.application.views.FeedbackGrid = Ext.extend(Ext.grid.GridPanel, {

    // Application bootstrap
    bootstrap: null,

    initComponent: function() {

        // json data store
        this.store = new KebabOS.applications.feedback.application.models.FeedbackDataStore({
            bootstrap:this.bootstrap
        });

        // grid config
        var config = {
            enableColumnResize: false,
            enableColumnHide:false,
            sortable:true,
            loadMask: true,
            viewConfig: {
                // To be equal to the width of columns
                forceFit: true

            }
        }

        this.columns = [
            {
                header   : 'Application Name',
                dataIndex: 'application_id'
            },
            {
                header   : 'Description',
                dataIndex: 'description'
            },
            {
                header   : 'Status',
                width:40,
                dataIndex: 'status'
            }
        ];

        this.bbar = this.buildBbar();

        Ext.apply(this, config);

        KebabOS.applications.feedback.application.views.FeedbackGrid.superclass.initComponent.apply(this, arguments);
    },

    buildBbar: function() {
        return  new Kebab.library.ext.ExtendedPagingToolbar({
            store: this.store
        });
    },

    listeners: {
        afterRender: function() {
            this.store.load();
        }
    }
});
