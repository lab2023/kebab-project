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
KebabOS.applications.storyManager.application.layouts.Layout = Ext.extend(Ext.grid.GridPanel, {

    // Application bootstrap
    bootstrap: null,

    initComponent: function() {

        // array data store
        var arrayData = [
            ['data 1', 11.11],
            ['data 2', 22],
            ['data 3', 33.33]
        ];

        // create the data store
        var store = new Ext.data.ArrayStore({
            fields: [
                {name: 'field_1'},
                {name: 'field_2'}
            ]
        });

        // manually load local data
        store.loadData(arrayData);

        var config = {
            title: 'Grid',
            viewConfig: {
                forceFit: true
            }
        }

        this.store = store;
        this.columns = [
            {
                header   : 'Field 1'
            },
            {
                header   : 'Field 2'
            }
        ];

        Ext.apply(this, config);

        KebabOS.applications.storyManager.application.layouts.Layout.superclass.initComponent.call(this);
    }
});
