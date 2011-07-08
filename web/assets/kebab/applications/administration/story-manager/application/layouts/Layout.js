/**
 * storyManager Bootstrap Class
 *
 * @category    Kebab
 * @package     Applications
 * @namespace   KebabOS.applications.storyManager.application.layouts
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 */
KebabOS.applications.storyManager.application.layouts.Layout = Ext.extend(Ext.Panel, {

    // Application bootstrap
    bootstrap: null,

    initComponent: function() {
        this.storyGrid = new KebabOS.applications.storyManager.application.views.StoryGrid({
            bootstrap: this.bootstrap
        });
        
        var config = {
            layout:'fit',
            items : this.storyGrid
        }

        Ext.apply(this, config);

        KebabOS.applications.storyManager.application.layouts.Layout.superclass.initComponent.call(this);
    }
});
