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
                    //var userId = this.user;
                    var sm = this.getSelectionModel();
                    Ext.each(records, function(record) {
                        //console.log(record);
                        if (record.id == this.userId) {

                            Ext.each(record.data.Roles, function(role) {
                                sm.selectRow(role.id - 1);

                            }, this);
                        }
                    }, this);
                }, scope: this
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
                handler : this.onSave
            }
        ];

        Ext.apply(this, config);

        KebabOS.applications.roleManager.application.views.RoleManagerStoryGrid.superclass.initComponent.apply(this, arguments);
    },

    listeners: {
        afterRender: function() {
            this.store.load();
        }
    }
});

