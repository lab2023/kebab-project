/**
 * Kebab Application Bootstrap Class
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.pixlr.application.layouts
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 */
KebabOS.applications.pixlr.application.layouts.Layout = Ext.extend(Ext.Panel, {

    // Application bootstrap
    bootstrap: null,

    layout: 'fit',
    border: false,

    initComponent: function() {

        Ext.apply(this, {
            items: [
                {
                    html: '<iframe id="iframe-photo-editor" name="iframe-photo-editor" frameborder="0" src="http://www.pixlr.com/editor" style="width: 100%; height: 100%; "></iframe>'
                }
            ]
        });

        KebabOS.applications.pixlr.application.layouts.Layout.superclass.initComponent.call(this);
    }
});
