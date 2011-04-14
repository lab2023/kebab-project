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
            bootstrap:this.bootstrap
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
            //TODO auto select row
            {
                header   : 'Roles',
                dataIndex: 'firstName'
            }
        ];

        Ext.apply(this, config);

        KebabOS.applications.userManager.application.views.RolesGrid.superclass.initComponent.apply(this, arguments);
    }
});
