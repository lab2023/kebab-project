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

Ext.namespace('Kebab.OS.Indicators.QuickStart');
/**
 * Kebab.OS.Indicators.QuickStart
 *
 * @namespace   Kebab
 * @author      Tayfun Öziş ERİKAN <tayfun.ozis.erikan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */
Kebab.OS.Indicators.QuickStart = Ext.extend(Kebab.OS.Indicator, {

    initComponent : function() {

        // Setup Indicator Config
        var indicatorCfg = new Ext.form.ComboBox({
            emptyText: Kebab.helper.translate('Quick start...'),
            tpl:'<tpl for="."><div ext:qtip="{description}" class="x-combo-list-item">{title}</div></tpl>',
            storeLoaded: false,
            typeAhead: true,
            width:150,
            triggerAction: 'all',
            forceSelection: true,
            hideTrigger:true,
            lazyRender:false,
            mode: 'local',
            store: new Ext.data.JsonStore({
                fields: [
                    'id', 'name', 'department', {name:'title', type: 'object', mapping: 'title.text'},
                    {name:'description', type: 'object', mapping: 'title.description'}
                ]
            }),
            valueField: 'id',
            displayField: 'title',
            hiddenName: 'id',
            listeners: {
                focus: function(combo) {
                    if (!combo.storeLoaded) {
                        combo.store.loadData(this.kernel.getApplications());
                        combo.storeLoaded = true;
                    }
                },
                select: function(combo, data) {
                    this.kernel.getDesktop().launchApplication(data.id);
                    combo.reset();
                },
                scope:this
            }
        });

        // Merge Object Configs
        Ext.apply(this, indicatorCfg);

        Kebab.OS.Indicators.QuickStart.superclass.initComponent.call(this);
    }

});