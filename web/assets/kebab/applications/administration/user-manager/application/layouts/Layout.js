/**
 * userManager Application layout Class
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.userManager.application.layouts
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/licensing
 */
KebabOS.applications.userManager.application.layouts.Layout = Ext.extend(Ext.Panel, {

    // Application bootstrap
    bootstrap: null,
    layout:'fit',
    border:false,

    initComponent: function() {

        this.userManagerDataView = new KebabOS.applications.userManager.application.views.UserManagerDataView({
            bootstrap: this.bootstrap
        });

        this.inviteUserWindow = new KebabOS.applications.userManager.application.views.InviteUserWindow({
            bootstrap: this.bootstrap
        });

        this.userRolesWindow = new KebabOS.applications.userManager.application.views.UserRolesWindow({
            bootstrap: this.bootstrap
        });

        this.emailForm = new KebabOS.applications.userManager.application.views.EmailForm({
            bootstrap: this.bootstrap
        });
        
        this.rolesGrid = new KebabOS.applications.userManager.application.views.RolesGrid({
            bootstrap: this.bootstrap
        });

        var config = {
            items : [this.userManagerDataView]
        };

        Ext.apply(this, config);

        KebabOS.applications.userManager.application.layouts.Layout.superclass.initComponent.call(this);
    }

});
