/**
 * roleManager Application MainCenter Class
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.roleManager.application.views
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/licensing
 */
KebabOS.applications.roleManager.application.views.MainCenter = Ext.extend(Ext.Panel, {

    // Application bootstrap
    bootstrap: null,

    initComponent: function() {
        this.roleManagerGrid = new KebabOS.applications.roleManager.application.views.RoleManagerGrid({
            bootstrap: this.bootstrap
        });
        this.eastCenter = new KebabOS.applications.roleManager.application.views.EastCenter({
            bootstrap: this.bootstrap
        });

        var config = {
            items:[this.roleManagerGrid, this.eastCenter],
            layout:'border',
            border:false,
            region: 'center'
        }

        Ext.apply(this, config);

        KebabOS.applications.roleManager.application.views.MainCenter.superclass.initComponent.apply(this, arguments);
    }
});
