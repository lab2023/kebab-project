/**
 * roleManager Application RoleManagerStoryGrid class
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.roleManager.application.views
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/licensing
 */
KebabOS.applications.roleManager.application.views.RoleManagerStoryGrid = Ext.extend(Ext.grid.EditorGridPanel, {

    // Application bootstrap
    bootstrap: null,

    initComponent: function() {

        // json data store
        this.store = new KebabOS.applications.roleManager.application.models.RoleManagerStoryDataStore({
            bootstrap:this.bootstrap,
            listeners: {
                load: function(store, records) {
                    var roleId = this.roleId;
                    var selectRow = new Array(), j = 0;
                    Ext.each(records, function(record) {
                        Ext.each(record.data.Permission, function(permission) {
                            if (roleId == permission.role_id) {
                                selectRow[j] = record.data.id - 1;
                            }
                            j++;
                        }, this);

                    }, this);

                    var sm = this.getSelectionModel();
                    sm.selectRows(selectRow);
                },
                scope: this
            }
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
            loadMask: true,
            viewConfig: {
                // To be equal to the width of columns
                forceFit: true
            }
        }

        this.sm = new Ext.grid.CheckboxSelectionModel();
        this.columns = [
            expander,
            this.sm,
            {
                header   : 'Title',
                dataIndex: 'title',
                sortable:true
            }
        ];
        this.buttons = [
            {
                text: 'Save',
                iconCls: 'icon-accept',
                handler : this.onSave,
                scope: this
            }
        ];

        this.addEvents('request');

        Ext.apply(this, config);

        KebabOS.applications.roleManager.application.views.RoleManagerStoryGrid.superclass.initComponent.apply(this, arguments);
    },

    onSave: function() {
        var storyIDs = new Array(), i = 0;
        Ext.each(this.selModel.getSelections(), function(story) {
            storyIDs[i++] = story.data.id;
        });
        var story = Ext.util.JSON.encode(storyIDs);

        this.fireEvent('request', {from:this, method:'PUT', roleId:this.roleId, story:story, url:'/kebab/role-story'})
    }
});

