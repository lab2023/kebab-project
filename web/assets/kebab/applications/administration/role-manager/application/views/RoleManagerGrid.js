/**
 * roleManager Application RoleManagerGrid class
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.roleManager.application.views
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/licensing
 */
KebabOS.applications.roleManager.application.views.RoleManagerGrid = Ext.extend(Ext.grid.EditorGridPanel, {

    // Application bootstrap
    bootstrap: null,

    initComponent: function() {

        // json data store
        this.store = new KebabOS.applications.roleManager.application.models.RoleManagerDataStore({
            bootstrap:this.bootstrap
        });

        var expander = new Ext.ux.grid.RowExpander({
            tpl : new Ext.Template(
                    '<p><b>Description:</b><br />{description}</p><br>'
                    )
        });

        // grid config
        var config = {
            plugins:expander,
            border:false,
            region: 'center',
            enableColumnHide:false,
            sortable:true,
            editor:true,
            selectRow:true,
            loadMask: true,
            title:'Change role',
            iconCls:'icon-user',
            viewConfig: {
                // To be equal to the width of columns
                forceFit: true
            }
        };

        this.sm = new Ext.grid.RowSelectionModel({
            sortable:true,
            header:'Title',
            dataIndex:'title'
        });

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
                        status: c.getValue(),
                        store:this.store,
                        from:this
                    });
                },
                scope: this
            }
        });

        this.columns = [
            expander,
            this.sm,
            {
                header   : 'Status',
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
            },{
                dataIndex: 'buttons',
                iconCls:'icon-delete',
                width:20
            }
        ];

        this.bbar = this.buildBbar();

        this.addEvents('loadGrid');
        this.addEvents('statusChanged', this);

        Ext.apply(this, config);

        KebabOS.applications.roleManager.application.views.RoleManagerGrid.superclass.initComponent.apply(this, arguments);

        this.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
            this.bootstrap.layout.mainCenter.eastCenter.roleManagerStoryGrid.roleId = r.data.id;
            this.fireEvent('loadGrid', this.bootstrap.layout.mainCenter.eastCenter.roleManagerStoryGrid.store);
        }, this);
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
