/**
 * roleManager Application RoleStoryGrid class
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.roleManager.application.views
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/licensing
 */
KebabOS.applications.roleManager.application.views.RoleStoryGrid = Ext.extend(Kebab.library.ext.ComplexEditorGridPanel, {

    // Application bootstrap
    bootstrap: null,

    initComponent: function() {

        // json data store
        this.dataStore = new KebabOS.applications.roleManager.application.models.RoleStoryDataStore({
            bootstrap:this.bootstrap
        });

        KebabOS.applications.roleManager.application.views.RoleStoryGrid.superclass.initComponent.apply(this, arguments);
    },

    listeners: {
        afterRender: function() {
            this.store.setBaseParam('roleId', this.roleId);
            this.store.load({params:{start:0, limit:25}});
            this.onDisableButtonGroup('export');
            this.onDisableButton('add');
            this.batchButton.toggle();
            this.getView().fitColumns();
        }
    },

    buildColumns: function() {

        return [
            {
                header : 'ID',
                dataIndex :'id',
                width:20
            },
            {
                header : 'Story Title',
                dataIndex :'title',
                width:60
            },
            {
                header : 'Story Description',
                dataIndex :'description',
                width:150
            },
            {
                header   : 'Allow ?',
                dataIndex: 'allow',
                xtype:'checkcolumn',
                width:20
            }
        ]
    }
});

