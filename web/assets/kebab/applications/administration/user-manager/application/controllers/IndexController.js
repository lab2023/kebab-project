/**
 * Kebab Application Bootstrap Class
 *
 * @category    Kebab
 * @package     Applications
 * @namespace   KebabOS.applications.userManager.application.controllers
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 */
KebabOS.applications.userManager.application.controllers.Index = Ext.extend(Ext.util.Observable, {

    // Application bootstrap
    bootstrap: null,

    constructor: function(config) {

        // Base Config
        Ext.apply(this, config || {});

        // Call Superclass initComponent() method
        KebabOS.applications.userManager.application.controllers.Index.superclass.constructor.apply(this, arguments);

        this.init();
    },

    // Initialize and define routing settings
    init: function() {

        this.bootstrap.layout.userGrid.on('userSelected', this.showUserRoleAction, this);

    },

    // Actions -----------------------------------------------------------------

    showUserRoleAction: function(user) {

        if (user.length > 0) {

            // Each the selected diseases
            Ext.each(user, function(user) {
                if (user.id) { // Is really record

                    // Create new disease detail tab
                    var userDetail = new KebabOS.applications.userManager.application.views.UserRoleGrid({
                        id: 'user-' + user.id,
                        userId: user.id,
                        closable:true,
                        title: user.id + ' # ' + user.data.fullName,
                        iconCls: 'icon-application-view-detail',
                        bootstrap: this.bootstrap,
                        border: false
                    });

                    // Add and activate tab
                    this.bootstrap.layout.add(userDetail);
                    this.bootstrap.layout.setActiveTab(1);
                }
            }, this);
        }
    }
});
