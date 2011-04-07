/**
 * Kebab Application Bootstrap Class
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.calculator.application.layouts
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/licensing
 */
KebabOS.applications.calculator.application.layouts.Layout = Ext.extend(Ext.Panel, {

    // Application bootstrap
    bootstrap: null,

    layout: 'fit',
    border: false,

    initComponent: function() {

        Ext.apply(this, {
            items: [
                {
                    html: '<iframe id="iframe-calculator" name="iframe-calculator" frameborder="0" src="http://web2.0calc.com/" style="width: 100%; height: 100%; "></iframe>'
                }
            ]
        });

        KebabOS.applications.calculator.application.layouts.Layout.superclass.initComponent.call(this);
    }
});
