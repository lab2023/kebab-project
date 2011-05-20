/**
 * Kebab Application Bootstrap Class
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.roleManager.application.controllers
 * @author      Yunus ÖZCAN <yuns.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/licensing
 */
KebabOS.applications.roleManager.application.controllers.Index = Ext.extend(Ext.util.Observable, {

    // Application bootstrap
    bootstrap: null,

    constructor: function(config) {

        // Base Config
        Ext.apply(this, config || {});

        // Call Superclass initComponent() method
        KebabOS.applications.roleManager.application.controllers.Index.superclass.constructor.apply(this, arguments);

        this.init();
    },

    // Initialize and define routing settings
    init: function() {
        this.bootstrap.layout.roleGrid.on('roleSelected', this.showRoleStoryAction, this);

    },

    // Actions -----------------------------------------------------------------

    showRoleStoryAction: function(role) {
        
        if (role.length > 0) {

            // Each the selected diseases
            Ext.each(role, function(role) {

                if (role.id) { // Is really record

                    // Create new disease detail tab
                    var roleDetail = new KebabOS.applications.roleManager.application.views.RoleStoryGrid({
                        id: 'role-' + role.id,
                        roleId: role.id,
                        closable:true,
                        title: role.id + ' # ' + role.data.title,
                        iconCls: 'icon-application-view-detail',
                        bootstrap: this.bootstrap,
                        border: false
                    });

                    // Add and activate tab
                    this.bootstrap.layout.add(roleDetail);
                    this.bootstrap.layout.setActiveTab(1);
                }
            }, this);
        }
    }
});
