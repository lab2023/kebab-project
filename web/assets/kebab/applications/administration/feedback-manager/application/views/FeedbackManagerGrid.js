/**
 * FeedbackManagerApplication FeedbackGrid
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.feedback.application.views
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/licensing
 */
KebabOS.applications.feedbackManager.application.views.FeedbackManagerGrid = Ext.extend(Ext.grid.EditorGridPanel, {

    // Application bootstrap
    bootstrap: null,

    initComponent: function() {

        // json data store
        this.store = new KebabOS.applications.feedbackManager.application.models.FeedbackDataStore({
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

        //KBBTODO add i18n
        var statusData = [
            ['open', 'Open'],
            ['process', 'In Progress'],
            ['closed', 'Closed']
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
                        status: c.getValue()
                    });
                    this.fireEvent('loadGrid');
                },
                scope: this
            }
        });
        this.columns = [
            {
                header   : 'Identity',
                width:40,
                dataIndex: 'id'
            },
            {
                header   : 'User Name',
                dataIndex: 'User',
                renderer: function(v) {
                    return v.firstName + ' ' + v.lastName;
                }
            },
            {
                header   : 'Application Name',
                dataIndex: 'title'
            },
            {
                header   : 'Description',
                dataIndex: 'description'
            },
            {
                header   : 'Status',
                dataIndex: 'status',
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
