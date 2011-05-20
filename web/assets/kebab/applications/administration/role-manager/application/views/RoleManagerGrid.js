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
KebabOS.applications.roleManager.application.views.RoleManagerGrid = Ext.extend(Kebab.library.ext.ComplexEditorGridPanel, {

    // Application bootstrap
    bootstrap: null,

    initComponent: function() {

        // json data store
        this.dataStore = new KebabOS.applications.roleManager.application.models.RoleManagerDataStore({
            bootstrap:this.bootstrap
        });

        this.editorTextField = new Ext.form.TextField({

        });

        this.expander = new Ext.ux.grid.RowExpander({
            tpl : new Ext.Template(
                    '<b>Description:</b><br />{description}<br>'
                    )
        });

        var config = {
            plugins:this.expander,
            iconCls: 'icon-application-view-list',
            stateful: true,
            loadMask: true,
            stripeRows: true,
            trackMouseOver:true,
            columnLines: true,
            clicksToEdit: true,
            viewConfig: {
                emptyText: 'Record not found...',
                forceFit: false
            }
        }

        Ext.apply(this, config);

        KebabOS.applications.roleManager.application.views.RoleManagerGrid.superclass.initComponent.apply(this, arguments);
    },

    listeners: {
        afterRender: function() {
            this.store.load({params:{start:0, limit:25}});
            this.onDisableButtonGroup('export');
            this.batchButton.toggle();
            this.getView().fitColumns();
        }
    },

    buildColumns: function() {
        return [this.expander,
            this.selectionModel,
            new Ext.grid.RowNumberer({header:'NO', width:50}), {
                header : 'Role Title',
                dataIndex :'title',
                flex:true,
                editor:this.editorTextField
            },{
                header   : 'Active',
                dataIndex: 'active',
                sortable:true,
                xtype:'checkcolumn',
                width:10
            }
        ]
    }

});
