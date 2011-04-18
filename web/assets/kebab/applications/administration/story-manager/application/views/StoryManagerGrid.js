/**
 * storyManager Application StoryManagerGrid class
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.storyManager.application.views
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/licensing
 */
KebabOS.applications.storyManager.application.views.StoryManagerGrid = Ext.extend(Ext.grid.EditorGridPanel, {

    // Application bootstrap
    bootstrap: null,

    initComponent: function() {

        // json data store
        this.store = new KebabOS.applications.storyManager.application.models.StoryManagerDataStore({
            bootstrap:this.bootstrap
        });

        // grid config
        var config = {
            border:false,
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
            ['active', 'Active'],
            ['passive', 'Passive']
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
                width:20,
                dataIndex: 'id'
            },
            {
                header   : 'Story Name',
                width:60,
                dataIndex: 'name'
            },
            {
                header   : 'Title',
                width:60,
                dataIndex: 'title'
            },
            {
                header   : 'Description',
                dataIndex: 'description'
            },
            {
                header   : 'Status',
                width:30,
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

        KebabOS.applications.storyManager.application.views.StoryManagerGrid.superclass.initComponent.apply(this, arguments);
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
