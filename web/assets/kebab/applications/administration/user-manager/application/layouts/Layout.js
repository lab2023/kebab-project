/**
 * Kebab Application Bootstrap Class
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.userManager.application.layouts
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 */
KebabOS.applications.userManager.application.layouts.Layout = Ext.extend(Ext.TabPanel, {

    // Application bootstrap
    bootstrap: null,

    initComponent: function() {

        // panels are defined here
        this.userGrid = new KebabOS.applications.userManager.application.views.UserGrid({
            bootstrap: this.bootstrap,
            title: 'Users',
            iconCls: 'icon-application-view-list',
            border:false
        });

        this.inviteUserWindow = new KebabOS.applications.userManager.application.views.InviteUserWindow({
            bootstrap: this.bootstrap
        });

        var config = {
            items:this.userGrid,
            activeTab: 0
        }

        Ext.apply(this, config);

        KebabOS.applications.userManager.application.layouts.Layout.superclass.initComponent.call(this);
    }
});
