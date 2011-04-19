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

        this.emailWindow = new KebabOS.applications.userManager.application.views.EmailWindow({
            bootstrap: this.bootstrap
        });

        var config = {
            layout:'fit',
            border:false,
            items : this.userManagerDataView

        };

        Ext.apply(this, config);

        this.bbar = new Kebab.library.ext.ExtendedPagingToolbar({
            store: this.userManagerDataView.store
        });

        this.tbar = [{
            text: 'Invite User',
            iconCls:'icon-user',
            handler: function() {
                this.fireEvent('showInviteUserWindow', this.inviteUserWindow)
            }, scope:this
        }];

        KebabOS.applications.userManager.application.layouts.Layout.superclass.initComponent.call(this);
    }
});
