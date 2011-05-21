/**
 * userManager Application RolesGrid class
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.userManager.application.views
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
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

                    var userId = this.userId;

                    var roles = new Array(),rolesID = new Array(), i = 0;
                    Ext.each(records, function(record) {
                        roles[i] = record.data.name;
                        rolesID[i] = record.data.id;
                        i++;
                    }, this);

                    var userRoles = new Array(), i = 0;
                    Ext.each(this.Roles, function(role) {
                        userRoles[i++] = role.name;
                    });

                    var sm = this.getSelectionModel();
                    var j = 0, selectRow = new Array();
                    Ext.each(userRoles, function(userRoles) {
                        Ext.each(roles, function(role) {

                            if (userRoles == role) {
                                selectRow[j] = rolesID[j] - 1;
                            } else {
                                j++;
                            }

                        }, this);
                        j = 0;
                    }, this);
                    sm.selectRows(selectRow);
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

        this.sm = new Ext.grid.CheckboxSelectionModel();
        this.columns = [
            this.sm,
            {
                header   : 'Roles',
                dataIndex: 'title'
            }
        ];

        this.buttons = [
            {
                text: 'Save',
                iconCls: 'icon-accept',
                handler : this.onSave,
                scope:this
            },
            {
                text: 'Cancel',
                iconCls: 'icon-cancel',
                handler : function(){
                    this.fireEvent('hideWindow', this.bootstrap.layout.userRolesWindow);
                },
                scope:this
            }
        ];

        this.addEvents('closeUserRolesWindow');
        this.addEvents('hideWindow');
        this.addEvents('loadGrid');
        this.addEvents('hideUserRolesWindow');

        Ext.apply(this, config);

        KebabOS.applications.userManager.application.views.RolesGrid.superclass.initComponent.apply(this, arguments);
    },
    onSave: function(){
        var roleIDs = new Array(), i = 0;
        Ext.each(this.selModel.getSelections(), function(role) {
           roleIDs[i++] = role.id;
        });
        var roles = Ext.util.JSON.encode(roleIDs);
        
        this.fireEvent('userRequest',{from:this, method:'PUT', user:this.userId, roles:roles, url:'/user/role-manager', store:this.bootstrap.layout.userManagerDataView.store})
        this.fireEvent('hideUserRolesWindow', this.bootstrap.layout.userRolesWindow);
    }
});
