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

Ext.namespace('Kebab.OS.Indicators.User');
/**
 * Kebab.OS.Indicators.User
 *
 * @namespace   Kebab
 * @author      Tayfun Öziş ERİKAN <tayfun.ozis.erikan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */
Kebab.OS.Indicators.User = Ext.extend(Kebab.OS.Indicator, {

    // User Object
    user:null,
    
    initComponent : function() {

        // Setup Indicator Config
        var indicatorCfg = {
            iconCls : 'icon-status-online',
            template: this.buttonTpl,
            text: this.user.fullName,
            handler: function() {
                this.kernel.getDesktop().launchApplication('aboutMe-application');
            },
            scope:this
        };

        // Merge Object Configs
        Ext.apply(this, indicatorCfg);

        Kebab.OS.Indicators.User.superclass.initComponent.call(this);
    }
});