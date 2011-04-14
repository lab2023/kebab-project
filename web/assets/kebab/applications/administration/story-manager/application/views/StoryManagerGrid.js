/**
 * storyManager Application StoryManagerGrid class
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.storyManager.application.views
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/licensing
 */
KebabOS.applications.storyManager.application.views.StoryManagerGrid = Ext.extend(Ext.grid.EditorGridPanel, {

    // Application bootstrap
    bootstrap: null,

    initComponent: function() {

        // json data store
        this.store = new KebabOS.applications.storyManager.application.models.StoryManagerDataStore({
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
                header   : 'Story Name',
                dataIndex: 'name'
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

        KebabOS.applications.storyManager.application.views.StoryManagerGrid.superclass.initComponent.apply(this, arguments);
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
