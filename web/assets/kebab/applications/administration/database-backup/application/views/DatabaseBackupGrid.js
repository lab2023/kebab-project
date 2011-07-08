/**
 * databaseBackup Application databaseBackupGrid class
 *
 * @category    Kebab
 * @package     Applications
 * @namespace   KebabOS.applications.databaseBackup.application.views
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 */
KebabOS.applications.databaseBackup.application.views.DatabaseBackupGrid = Ext.extend(Ext.grid.EditorGridPanel, {

    // Application bootstrap
    bootstrap: null,

    url: 'backup/backup',

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
        };

        this.bbar = new Kebab.library.ext.ExtendedPagingToolbar({
            store: this.store
        });

        this.tbar = [{
            text: Kebab.helper.translate('Backup'),
            iconCls:'icon-database-save',
            handler: function() {
                this.fireEvent('backupRequest', {from:this, url:this.url, method:'POST', store:this.store})
            }, scope:this
        }];
        
        this.columns = [
            {
                header   : Kebab.helper.translate('Date'),
                dataIndex: 'name',
                sortable:true
            },
            {
                header   : Kebab.helper.translate('Size'),
                dataIndex: 'size',
                sortable:true
            },
            {
                dataIndex: 'buttons',
                width:16,
                xtype: 'actioncolumn',
                items: [{
                        iconCls:'icon-disk databaseBackup-icon-icon-disk',
                        tooltip: Kebab.helper.translate('Download backup'),
                        handler: function(grid, rowIndex) {
                            var rec = this.store.getAt(rowIndex);
                            var file = rec.data.name;
                            window.location.href = Kebab.helper.url(this.url + '/fileName/' + file);
                        },
                        scope:this
                    }, {
                        iconCls:'icon-cancel databaseBackup-icon-cancel',
                        tooltip: Kebab.helper.translate('Delete backup'),
                        handler: function(grid, rowIndex) {
                            var rec = this.store.getAt(rowIndex);
                            var file = rec.data.name;
                            Ext.Msg.confirm(Kebab.helper.translate('Are you sure?'), Kebab.helper.translate('Do you want to {0} backup file delete?', file), function(button) {
                                if (button == 'yes') {
                                    this.fireEvent('deleteRequest', {
                                        name:file,
                                        from:this,
                                        store:this.store,
                                        url:this.url ,
                                        method:'DELETE'
                                    });
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