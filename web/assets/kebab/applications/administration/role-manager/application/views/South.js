/**
 * roleManager Application South Class
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.roleManager.application.views
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/licensing
 */
KebabOS.applications.roleManager.application.views.South = Ext.extend(Ext.Panel, {

    // Application bootstrap
    bootstrap: null,
    layout:'fit',
    border:false,

    initComponent: function() {
        this.roleManagerStoryGrid = new KebabOS.applications.roleManager.application.views.RoleManagerStoryGrid({
            bootstrap: this.bootstrap
        });
        var config = {
            title:'Chance story',
            items:[this.roleManagerStoryGrid],
            bbar:[
                 {
                    text: 'Save story',
                    iconCls:'icon-accept',
                    handler: function() {
                        this.collapsed = true;


                    }
                }
            ]
        }

        Ext.apply(this, config);

        KebabOS.applications.roleManager.application.views.South.superclass.initComponent.apply(this, arguments);
    }
});
