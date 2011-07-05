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

Ext.namespace('Kebab.OS.Indicator');
/**
 * Kebab.OS.Indicator
 *
 * @namespace   Kebab
 * @author      Tayfun Öziş ERİKAN <tayfun.ozis.erikan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */
Kebab.OS.Indicator = Ext.extend(Ext.Button, {

    kernel: null,
    tpl:  new Ext.Template(
        '<table cellspacing="0" class="x-btn"><tbody class="{1}"><tr>',
        '<td><em class="{2}">',
        '<button class="x-btn-text" type="{0}" style="height:30px;"></button>',
        '</em></td>',
        '</tr></tbody></table>'
    ),

    initComponent : function() {

        var indicator = {
            template : this.tpl
        };

        Ext.apply(this, indicator);

        Kebab.OS.Indicator.superclass.initComponent.call(this);
    }
});

Ext.namespace('Kebab.OS.Indicator.Window');
/**
 * Kebab.OS.Indicator
 *
 * @namespace   Kebab
 * @author      Tayfun Öziş ERİKAN <tayfun.ozis.erikan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */
Kebab.OS.Indicator.Window = Ext.extend(Ext.Window, {

    indicator: null,

    initComponent : function() {

        var window = {
            shim:false,
            layout:'fit',
            collapsible: true,
            animShowCfg: {
                duration: .0,
                easing: 'backOut'
            },
            animHideCfg: {
                duration: .15,
                easing: 'easeIn'
            },
            renderTo: this.indicator.kernel.getDesktop().kebabOsDesktop,
            manager: this.indicator.kernel.getDesktop().getManager(),
            x: this.indicator.el.getX() - this.width,
            y: 50,
            constrainHeader:true,
            animateTarget: this.indicator.el,
            closeAction:'hide',
            buttons: [{
                text: Kebab.helper.translate('Close'),
                iconCls: 'icon-cancel',
                handler: function() {
                    this.hide();
                },
                scope:this
            }]
        };

        Ext.apply(this, window);

        Kebab.OS.Indicator.Window.superclass.initComponent.call(this);
    }
});