/**
 * Kebab Application Bootstrap Class
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.storyManager.application.layouts
 * @author      Yunus ÖZCAN <yuns.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/licensing
 */
KebabOS.applications.storyManager.application.layouts.Layout = Ext.extend(Ext.Panel, {

    // Application bootstrap
    bootstrap: null,

    initComponent: function() {
        this.storyManagerGrid = new KebabOS.applications.storyManager.application.views.StoryManagerGrid({
            bootstrap: this.bootstrap
        });
        var config = {
            items : [this.storyManagerGrid]
        }

        Ext.apply(this, config);

        KebabOS.applications.storyManager.application.layouts.Layout.superclass.initComponent.call(this);
    }
});
