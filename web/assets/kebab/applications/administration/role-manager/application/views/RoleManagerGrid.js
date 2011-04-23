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
            selectRow:true,
            loadMask: true,
            editor:true,
            title:'Change role',
            iconCls:'icon-user',
            viewConfig: {
                // To be equal to the width of columns
                forceFit: true
            }
        };
        this.sm = new Ext.grid.RowSelectionModel({
            header:'No',
            width:25
        });

        this.columns = [
            expander,
            this.sm,
            {
                header   : 'Title',
                dataIndex: 'title',
                sortable:true
            },
            {
                header   : 'Status',
                dataIndex: 'status',
                sortable:true
            }
        ];

        this.addEvents('loadParamsGrid');

        Ext.apply(this, config);

        KebabOS.applications.roleManager.application.views.RoleManagerGrid.superclass.initComponent.apply(this, arguments);

        this.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
            this.fireEvent('loadParamsGrid', {store: this.bootstrap.layout.mainCenter.eastCenter.roleManagerStoryGrid.store, roleId:r.data.id});
            this.bootstrap.layout.mainCenter.eastCenter.roleManagerStoryGrid.roleId = r.data.id;
        }, this);
    },

    listeners: {
        afterRender: function() {
            this.store.load();
        }
    }

});
