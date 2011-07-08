/**
 * Kebab Application Bootstrap Class
 *
 * @category    Kebab
 * @package     Applications
 * @namespace   KebabOS.applications.maps.application.layouts
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 */
KebabOS.applications.maps.application.layouts.Layout = Ext.extend(Ext.Panel, {

    // Application bootstrap
    bootstrap: null,

    layout: 'fit',
    border: false,

    initComponent: function() {

        Ext.apply(this, {
            items: [
                {
                    html: '<iframe width="100%" height="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/?ie=UTF8&amp;ll=17.308688,-52.382812&amp;spn=41.9185,86.572266&amp;z=4&amp;output=embed"></iframe>'
                }
            ]
        });

        KebabOS.applications.maps.application.layouts.Layout.superclass.initComponent.call(this);
    }
});
