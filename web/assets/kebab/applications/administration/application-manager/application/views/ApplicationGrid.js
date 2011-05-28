/**
 * applicationManager Application ApplicationGrid class
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.applicationManager.application.views
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 */
KebabOS.applications.applicationManager.application.views.ApplicationGrid = Ext.extend(Kebab.library.ext.ComplexEditorGridPanel, {

    // Application bootstrap
    bootstrap: null,
    border:false,

    initComponent: function() {

        this.dataStore = new KebabOS.applications.applicationManager.application.models.ApplicationDataStore({
            bootstrap:this.bootstrap
        });

        KebabOS.applications.applicationManager.application.views.ApplicationGrid.superclass.initComponent.apply(this, arguments);
    },

    buildColumns: function() {

        return [
            {
                header : 'ID',
                dataIndex :'id',
                width:12
            },
            {
                header : Kebab.helper.translate('Application title'),
                dataIndex :'title',
                width:60
            },
            {
                header : Kebab.helper.translate('Application description'),
                dataIndex :'description',
                width:120
            },
            {
                header   : Kebab.helper.translate('Active ?'),
                dataIndex: 'active',
                xtype:'checkcolumn',
                width:20
            }
        ]
    },

    listeners: {
        afterRender: function() {
            this.store.load({params:{start:0, limit:25}});
            this.onDisableButtonGroup('export');
            this.onDisableButton('add');
            this.onDisableButton('remove');
            this.batchButton.toggle();
            this.getView().fitColumns();
        }
    }
});
