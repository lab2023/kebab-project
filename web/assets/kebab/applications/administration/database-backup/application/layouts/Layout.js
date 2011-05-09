/**
 * Kebab Application Bootstrap Class
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.databaseBackup.application.layouts
 * @author      Yunus ÖZCAN <yuns.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/licensing
 */
KebabOS.applications.databaseBackup.application.layouts.Layout = Ext.extend(Ext.Panel, {

    // Application bootstrap
    bootstrap: null,
    layout:'fit',
    
    initComponent: function() {
        this.databaseBackupGrid = new KebabOS.applications.databaseBackup.application.views.DatabaseBackupGrid({
            bootstrap: this.bootstrap
        });

        var config = {

            items : this.databaseBackupGrid
        };

        Ext.apply(this, config);

        KebabOS.applications.databaseBackup.application.layouts.Layout.superclass.initComponent.call(this);
    }
});
