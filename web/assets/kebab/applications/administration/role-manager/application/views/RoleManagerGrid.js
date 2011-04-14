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
                header   : 'Identity',
                width:40,
                dataIndex: 'id'
            },
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

        this.bbar = this.buildBbar();

        Ext.apply(this, config);

        KebabOS.applications.roleManager.application.views.RoleManagerGrid.superclass.initComponent.apply(this, arguments);
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
