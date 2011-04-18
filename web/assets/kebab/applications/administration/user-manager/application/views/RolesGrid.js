/**
 * userManager Application RolesGrid class
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.userManager.application.views
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/licensing
 */
KebabOS.applications.userManager.application.views.RolesGrid = Ext.extend(Ext.grid.GridPanel, {

    // Application bootstrap
    bootstrap: null,

    initComponent: function() {

        // json data store
        this.store = new KebabOS.applications.userManager.application.models.RolesDataStore({
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
                },
                scope: this
            }
        });

        // grid config
        var config = {
            enableColumnResize: false,
            enableColumnHide:false,
            sortable:true,
            loadMask: true,
            multiSelect:true,
            autoScroll:true,
            viewConfig: {
                // To be equal to the width of columns
                forceFit: true
            }
        }
        this.buttons = [
            {
                text: 'Save',
                iconCls: 'icon-accept',
                handler : this.onSave
            },
            {
                text: 'Cancel',
                iconCls: 'icon-cancel',
                handler : this.onSave
            }
        ];

        this.sm = new Ext.grid.CheckboxSelectionModel();
        this.columns = [
            this.sm,
            {
                header   : 'Roles',
                dataIndex: 'firstName'
            }
        ];

        Ext.apply(this, config);

        KebabOS.applications.userManager.application.views.RolesGrid.superclass.initComponent.apply(this, arguments);
    }
});
