/**
 * FeedbackManagerApplication FeedbackGrid
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.feedback.application.views
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 */
KebabOS.applications.feedbackManager.application.views.FeedbackManagerGrid = Ext.extend(Ext.grid.EditorGridPanel, {

    // Application bootstrap
    bootstrap: null,

    initComponent: function() {

        // json data store
        this.store = new KebabOS.applications.feedbackManager.application.models.FeedbackDataStore({
            bootstrap:this.bootstrap
        });

        var expander = new Ext.ux.grid.RowExpander({
            tpl : new Ext.Template(
                    '<p><b>'+Kebab.helper.translate('Description')+':</b><br />{description}</p><br>'
                    )
        });

        // grid config
        var config = {
            enableColumnHide:false,
            plugins: expander,
            sortable:true,
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
        
        var statusCombobox = new Ext.form.ComboBox({
            typeAhead: true,
            triggerAction: 'all',
            forceSelection: true,
            lazyRender:false,
            mode: 'local',
            store: new Ext.data.ArrayStore({
                fields: ['id', 'name'],
                data: statusData
            }),
            valueField: 'id',
            displayField: 'name',
            hiddenName: 'status',
            scope:this,
            listeners: {
                select: function(c) {
                    this.fireEvent('statusChanged', {
                        id: c.gridEditor.record.id,
                        status: c.getValue(),
                        from:this,
                        store:this.store
                    });
                },
                scope: this
            }
        });
        this.columns = [
            expander,
            {
                header   : 'ID',
                width:12,
                dataIndex: 'id',
                sortable:true
            },
            {
                header   : Kebab.helper.translate('User name'),
                dataIndex: 'User',
                sortable:true,
                renderer: function(v) {
                    return v.fullName;
                }
            },
            {
                header   : Kebab.helper.translate('Application name'),
                dataIndex: 'title',
                sortable:true
            },
            {
                header   : Kebab.helper.translate('Status'),
                dataIndex: 'status',
                sortable:true,
                editor: statusCombobox,
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

        this.addEvents('statusChanged');
        this.addEvents('loadGrid');

        KebabOS.applications.feedbackManager.application.views.FeedbackManagerGrid.superclass.initComponent.apply(this, arguments);
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
