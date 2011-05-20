/**
 * roleManager Application RoleGrid class
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.roleManager.application.views
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/licensing
 */
KebabOS.applications.roleManager.application.views.RoleGrid = Ext.extend(Kebab.library.ext.ComplexEditorGridPanel, {

    // Application bootstrap
    bootstrap: null,

    initComponent: function() {

        // json data store
        this.dataStore = new KebabOS.applications.roleManager.application.models.RoleDataStore({
            bootstrap:this.bootstrap
        });

        this.extraTbarButtons = this.buildExtraTbarButtonsData();

        this.addEvents('roleSelected');

        KebabOS.applications.roleManager.application.views.RoleGrid.superclass.initComponent.apply(this, arguments);
    },

    listeners: {
        beforeRender: function() {
            if (this.extraTbarButtons) {
                this.buildExtraTbarButtons();
            }
        },
        
        afterRender: function() {
            this.store.load({params:{start:0, limit:25}});
            this.onDisableButtonGroup('export');
            this.batchButton.toggle();
            this.getView().fitColumns();
        }
    },
    buildExtraTbarButtonsData: function() {

        return [{
            xtype: 'buttongroup',
            title: 'Extra',
            defaults: {
                iconAlign: 'top',
                scale: 'small',
                width:50,
                scope: this
            },
            items: [{
                text: 'See details',
                tooltip: 'See the selected record(s) details',
                iconCls: 'icon-application-view-detail',
                handler: function() { // Records selected
                    var sm = this.getSelectionModel();
                    if (sm.getCount()) {
                        this.fireEvent('roleSelected', sm.getSelections());
                    }
                }
            }]
        }];
    },
    
    buildColumns: function() {

        this.editorTextField = new Ext.form.TextField({});
        this.editorTextArea = new Ext.form.TextArea({});

        return [
            this.selectionModel,
            new Ext.grid.RowNumberer({header:'No', width:40}), {
                header : 'Role Title',
                dataIndex :'title',
                editor:this.editorTextField,
                width:30
            },{
                header : 'Role Description',
                dataIndex :'description',
                editor:this.editorTextArea,
                width:120
            },{
                header   : 'Users Number',
                dataIndex: 'usersNumber',
                width:30
            },{
                header   : 'Stories Number',
                dataIndex: 'storiesNumber',
                width:30
            },{
                header   : 'Active ?',
                dataIndex: 'active',
                xtype:'checkcolumn',
                width:20
            }
        ]
    }

});
