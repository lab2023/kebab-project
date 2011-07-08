/**
 * storyManager Application StoryGrid class
 *
 * @category    Kebab
 * @package     Applications
 * @namespace   KebabOS.applications.storyManager.application.views
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 */
KebabOS.applications.storyManager.application.views.StoryGrid = Ext.extend(Kebab.library.ext.ComplexEditorGridPanel, {

    // Application bootstrap
    bootstrap: null,
    border:false,

    initComponent: function() {

        this.dataStore = new KebabOS.applications.storyManager.application.models.StoryDataStore({
            bootstrap:this.bootstrap
        });

        KebabOS.applications.storyManager.application.views.StoryGrid.superclass.initComponent.apply(this, arguments);
    },

    buildColumns: function() {

        return [
            {
                header : 'ID',
                dataIndex :'id',
                width:12
            },
            {
                header : Kebab.helper.translate('Story title'),
                dataIndex :'title',
                width:50
            },
            {
                header : Kebab.helper.translate('Story description'),
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
