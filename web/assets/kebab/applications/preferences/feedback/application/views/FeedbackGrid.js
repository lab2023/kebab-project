/**
 * Kebab Application FeedbackGrid
 *
 * @category    Kebab
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
                '<p><b>' + Kebab.helper.translate('Your feedbacks') + ':</b><br />{description}</p><br>'
            )
        });

        // grid config
        var config = {
            enableColumnHide:false,
            plugins: expander,
            loadMask: true,
            viewConfig: {
                // To be equal to the width of columns
                forceFit: true
            }
        };

        var statusData = [
            ['open', Kebab.helper.translate('Open')],
            ['progress', Kebab.helper.translate('In Progress')],
            ['closed', Kebab.helper.translate('Closed')]
        ];
        
        this.columns = [
            expander,
            {
                header   : Kebab.helper.translate('Application name'),
                width:.8,
                dataIndex: 'title',
                sortable:true
            },
            {
                header   : Kebab.helper.translate('Status'),
                width:.2,
                dataIndex: 'status',
                align:'center',
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
