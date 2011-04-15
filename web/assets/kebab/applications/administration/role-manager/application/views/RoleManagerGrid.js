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
    border:false,

    initComponent: function() {

        // json data store
        this.store = new KebabOS.applications.roleManager.application.models.RoleManagerDataStore({
            bootstrap:this.bootstrap
        });

        // grid config

        var config = {
            enableColumnResize: false,
            enableColumnHide:false,
            sortable:true,
            selectRow:true,
            loadMask: true,
            editor:true,
            title:'Chance role',
            iconCls:'icon-user',
            viewConfig: {
                // To be equal to the width of columns
                forceFit: true
            }
        };
        this.sm = new Ext.grid.RowSelectionModel();
        this.sm.width = 10;
        this.columns = [
            this.sm,
            {
                header   : 'Title',
                dataIndex: 'title'
            },
            {
                header   : 'Description',
                dataIndex: 'description'
            },
            {
                header   : 'Status',
                dataIndex: 'status'
            }
        ];

        Ext.apply(this, config);

        KebabOS.applications.roleManager.application.views.RoleManagerGrid.superclass.initComponent.apply(this, arguments);
    },

    listeners: {
        afterRender: function() {
            this.store.load();
        }
    }

});
