/**
 * userManager Application UserRoleGrid class
 *
 * @category    Kebab
 * @package     Applications
 * @namespace   KebabOS.applications.userManager.application.views
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 */
KebabOS.applications.userManager.application.views.UserRoleGrid = Ext.extend(Kebab.library.ext.ComplexEditorGridPanel, {

    // Application bootstrap
    bootstrap: null,

    initComponent: function() {

        // json data store
        this.dataStore = new KebabOS.applications.userManager.application.models.UserRoleDataStore({
            bootstrap:this.bootstrap
        });

        KebabOS.applications.userManager.application.views.UserRoleGrid.superclass.initComponent.apply(this, arguments);
    },

    listeners: {
        afterRender: function() {
            this.store.setBaseParam('userId', this.userId);
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
                width: 12
            },
            {
                header : Kebab.helper.translate('Role title'),
                dataIndex :'title',
                width:60
            },
            {
                header : Kebab.helper.translate('Role description'),
                dataIndex :'description',
                width:150
            },
            {
                header   : Kebab.helper.translate('Allow ?'),
                dataIndex: 'allow',
                xtype:'checkcolumn',
                width:20
            }
        ]
    }
});

