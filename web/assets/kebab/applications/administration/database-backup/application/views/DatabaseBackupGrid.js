/**
 * databaseBackup Application databaseBackupGrid class
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.databaseBackup.application.views
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/licensing
 */
KebabOS.applications.databaseBackup.application.views.DatabaseBackupGrid = Ext.extend(Ext.grid.EditorGridPanel, {

    // Application bootstrap
    bootstrap: null,

    initComponent: function() {

        // json data store
        this.store = new KebabOS.applications.databaseBackup.application.models.DatabaseBackupDataStore({
            bootstrap:this.bootstrap
        });

        // grid config
        var config = {
            border:false,
            enableColumnHide:false,
            sortable:true,
            loadMask: true,
            viewConfig: {
                // To be equal to the width of columns
                forceFit: true
            }
        }

        this.bbar = new Kebab.library.ext.ExtendedPagingToolbar({
            store: this.store
        });

        this.columns = [
            {
                header   : 'Date',
                dataIndex: 'name',
                sortable:true
            },
                {
                header   : 'Size',
                dataIndex: 'size',
                sortable:true
            },
            {
                dataIndex: 'buttons',
                width:5,
                xtype: 'actioncolumn',
                items: [
                    {
                        iconCls:'icon-cancel',
                        tooltip: 'Download Backup',
                        handler: function(grid, rowIndex) {
                            var rec = this.store.getAt(rowIndex);
                            var file = rec.data.name;
                                    this.fireEvent('downloadRequest', {name:file, url:'/system/backup', method:'GET'});
                        },
                        scope:this
                    }
                ]
            },
            {
                dataIndex: 'buttons',
                width:5,
                xtype: 'actioncolumn',
                items: [
                    {
                        iconCls:'icon-cancel',
                        tooltip: 'Delete Backup',
                        handler: function(grid, rowIndex) {
                            var rec = this.store.getAt(rowIndex);
                            var file = rec.data.name;
                            Ext.Msg.confirm('Are you sure?', 'Do you want to ' + file + ' file delete?', function(button) {
                                if (button == 'yes') {
                                    this.fireEvent('deleteRequest', {name:file, from:this, store:this.store, url:'/system/backup',method:'DELETE'});
                                }
                            }, this);
                        },
                        scope:this
                    }
                ]
            }
        ];

        this.addEvents('backupRequest');
        this.addEvents('deleteRequest');
        this.addEvents('downloadRequest');
        this.addEvents('loadGrid');

        Ext.apply(this, config);

        KebabOS.applications.databaseBackup.application.views.DatabaseBackupGrid.superclass.initComponent.apply(this, arguments);
    },

    listeners: {
        afterRender: function() {
            this.store.load();
        }
    }
});
