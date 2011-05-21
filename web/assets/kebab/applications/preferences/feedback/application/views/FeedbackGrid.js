/**
 * Kebab Application FeedbackGrid
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.feedback.application.views
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 */
KebabOS.applications.feedback.application.views.FeedbackGrid = Ext.extend(Ext.grid.GridPanel, {

    // Application bootstrap
    bootstrap: null,

    initComponent: function() {

        // json data store
        this.store = new KebabOS.applications.feedback.application.models.FeedbackDataStore({
            bootstrap:this.bootstrap
        });

        var expander = new Ext.ux.grid.RowExpander({
            tpl : new Ext.Template(
                    '<p><b>Description:</b><br />{description}</p><br>'
                    )
        });

        // grid config
        var config = {
            region: 'center',
            enableColumnHide:false,
            plugins: expander,
            loadMask: true,
            viewConfig: {
                // To be equal to the width of columns
                forceFit: true
            }
        };


        //KBBTODO add i18n
        var statusData = [
            ['open', 'Open'],
            ['progress', 'In Progress'],
            ['closed', 'Closed']
        ];
        
        this.columns = [
            expander,
            {
                header   : 'Application Name',
                width:40,
                dataIndex: 'title',
                sortable:true
            },
            {
                header   : 'Status',
                width:40,
                dataIndex: 'status',
                sortable:true,
                renderer: function(v) {
                    var retVal = null;
                    Ext.each(statusData, function(status) {
                        if (v == status[0])
                            retVal = status[1];
                    });
                    return retVal;
                }
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
