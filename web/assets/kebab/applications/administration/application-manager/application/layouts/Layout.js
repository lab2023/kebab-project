/**
 * Kebab Application Bootstrap Class
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.applicationManager.application.layouts
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/licensing
 */
KebabOS.applications.applicationManager.application.layouts.Layout = Ext.extend(Ext.Panel, {

    // Application bootstrap
    bootstrap: null,

    initComponent: function() {
  this.applicationGrid = new KebabOS.applications.applicationManager.application.views.ApplicationGrid({
            bootstrap: this.bootstrap
        });

        var config = {
            layout:'fit',
            items : this.applicationGrid
        }

        Ext.apply(this, config);

        KebabOS.applications.applicationManager.application.layouts.Layout.superclass.initComponent.call(this);
    }
});
