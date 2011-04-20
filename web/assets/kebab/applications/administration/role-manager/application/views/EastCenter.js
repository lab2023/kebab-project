/**
 * roleManager Application EastCenter Class
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.roleManager.application.views
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/licensing
 */
KebabOS.applications.roleManager.application.views.EastCenter = Ext.extend(Ext.Panel, {

    // Application bootstrap
    bootstrap: null,

    initComponent: function() {
        this.roleManagerStoryGrid = new KebabOS.applications.roleManager.application.views.RoleManagerStoryGrid({
            bootstrap: this.bootstrap
        });
        var config = {
            width:250,
            iconCls:'icon-page',
            title:'Change story',
            items:[this.roleManagerStoryGrid],
            layout:'fit',
            border:false,
            region: 'east',
            split:true
        }

        Ext.apply(this, config);

        KebabOS.applications.roleManager.application.views.EastCenter.superclass.initComponent.apply(this, arguments);
    }
});
