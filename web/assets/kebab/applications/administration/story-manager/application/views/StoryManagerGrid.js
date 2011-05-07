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

        var expander = new Ext.ux.grid.RowExpander({
            tpl : new Ext.Template(
                    '<p><b>Description:</b><br />{description}</p><br>'
                    )
        });
        
        // grid config
        var config = {
            plugins:expander,
            border:false,
            enableColumnHide:false,
            sortable:true,
            loadMask: true,
            viewConfig: {
                // To be equal to the width of columns
                forceFit: true
            }
        }

        this.columns = [
                expander,
            {
                header   : 'Identity',
                width:10,
                dataIndex: 'id',
                sortable:true
            },
            {
                header   : 'Title',
                dataIndex: 'title'
            },
            {
                header   : 'Active',
                dataIndex: 'active',
                sortable:true,
                width:12,
                xtype:'checkcolumn'
            }
        ];

        this.addEvents('loadGrid');

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
