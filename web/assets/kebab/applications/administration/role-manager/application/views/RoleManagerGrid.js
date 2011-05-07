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
            header:'Name',
            dataIndex:'title'
        });
        
        Ext.select('span.roleManager-application-span').on('click', function() {
            console.log(arguments);
        });
        this.columns = [
            expander,
            this.sm,
            {
                header   : 'Active',
                dataIndex: 'active',
                sortable:true,
                xtype:'checkcolumn',
                width:12
            },{
                dataIndex: 'buttons',
                width:5,
                xtype: 'actioncolumn',
                items: [
                    {
                        iconCls:'icon-cancel',
                        tooltip: 'Delete role',
                        handler: function(grid, rowIndex) {
                            var rec = this.store.getAt(rowIndex);
                            var roleId = rec.id;
                            Ext.Msg.confirm('Are you sure?', 'Do you want to delete', function(button) {
                                if (button == 'yes') {
                                    this.fireEvent('roleRequest', {from:this, method:'DELETE' ,url:'/role/manager', roleId:roleId, store:this.store});
                                }
                            }, this);
                        },
                        scope:this
                    }
                ]
            }
        ];

        this.bbar = this.buildBbar();

        this.addEvents('loadGrid');
        this.addEvents('roleRequest');

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
