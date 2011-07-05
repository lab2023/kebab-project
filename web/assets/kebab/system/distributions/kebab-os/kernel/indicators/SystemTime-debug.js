/* -----------------------------------------------------------------------------
 Kebab Project 1.5.x (Kebab Reloaded)
 http://kebab-project.com
 Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc.
 http://www.lab2023.com

    * LICENSE
    *
    * This source file is subject to the  Dual Licensing Model that is bundled
    * with this package in the file LICENSE.txt.
    * It is also available through the world-wide-web at this URL:
    * http://www.kebab-project.com/cms/licensing
    * If you did not receive a copy of the license and are unable to
    * obtain it through the world-wide-web, please send an email
    * to info@lab2023.com so we can send you a copy immediately.
----------------------------------------------------------------------------- */

/**
 * Kebab.OS.Indicators.SystemTime
 *
 * @namespace   Kebab
 * @author      Tayfun Öziş ERİKAN <tayfun.ozis.erikan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */
Kebab.OS.Indicators.SystemTime = Ext.extend(Kebab.OS.Indicator, {

    initComponent : function() {

        // Setup Indicator Config
        var indicatorCfg = {
            text: new Date().format('d-m-Y, G:i'),
            iconCls: 'icon-clock',
            menu:  new Ext.menu.DateMenu()
        };

        // Merge Object Configs
        Ext.apply(this, indicatorCfg);

        Kebab.OS.Indicators.SystemTime.superclass.initComponent.call(this);
    },

    listeners: {
        afterRender: function(indicator) {
            var systemTimeTask = {
                run: function(){
                    indicator.setText(new Date().format('d-m-Y, G:i'));
                },
                interval: 60000 //1 minute
            };
            Ext.TaskMgr.start(systemTimeTask);
        }
    }
});