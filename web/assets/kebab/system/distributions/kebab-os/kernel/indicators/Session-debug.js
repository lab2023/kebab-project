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

Ext.namespace('Kebab.OS.Indicators.Session');
/**
 * Kebab.OS.Indicators.Session
 *
 * @namespace   Kebab
 * @author      Tayfun Öziş ERİKAN <tayfun.ozis.erikan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */
Kebab.OS.Indicators.Session = Ext.extend(Kebab.OS.Indicator, {

    /**
     * Component identity
     */
    id: 'kebab-os-panel-indicators-session',
    
    initComponent : function() {

        // Setup Indicator Config
        var indicatorCfg = {
            iconCls : 'icon-shutdown',
            menu: [{
                text: Kebab.helper.translate('Lock Screen'),
                iconCls: 'icon-lock',
                handler: function() {
                    this.showLockScreenAction();
                },
                scope:this
            },'-', {
                text: Kebab.helper.translate('Logout...'),
                iconCls: 'icon-door-out',
                handler: function() {
                    Kebab.getOS().logoutAction(this.user.id, true);
                },
                scope:this
            },{
                text: Kebab.helper.translate('Restart...'),
                iconCls: 'icon-arrow-refresh',
                handler: function() {
                    Kebab.getOS().rebootAction();
                }
            }]
            /*{
                text: Kebab.helper.translate('Shutdown...'),
                iconCls: 'icon-shutdown',
                handler: function() {
                    //Kebab.getOS().shutDownAction(this.user.id);
                },
                scope:this
            }*/
        };

        // Merge Object Configs
        Ext.apply(this, indicatorCfg);

        Kebab.OS.Indicators.Session.superclass.initComponent.call(this);
    },

    /**
     * Show lock screen window
     */
    showLockScreenAction: function() {
        if (!this.window) { // If window not created

            this.unlockForm = new Ext.FormPanel({
                title: Kebab.helper.translate('Unlock Screen'),
                iconCls: 'icon-lock-open',
                url: Kebab.helper.url('kebab/session'),
                frame:true,
                bodyStyle: 'text-align:center;',
                labelAlign:'top',
                width: '100%',
                buttonAlign:'left',
                border:false,
                defaultType: 'textfield',
                hideLabels: true,
                items: [{
                    hidden:true,
                    name: 'userName',
                    value: this.user.userName
                },{
                    xtype: 'panel',
                    bodyStyle: 'font-size:18px;',
                    html: this.user.fullName
                },{
                    fielLabel: Kebab.helper.translate('Your password'),
                    emptyText: Kebab.helper.translate('Your password'),
                    name: 'password',
                    anchor: '80%',
                    inputType: 'password',
                    allowBlank:false
                }],
                keys:[{
                    key: [Ext.EventObject.ENTER], handler: function() {
                        this.unlockAction();
                    }, scope:this
                }]
            });

            // Detail window
            this.window = new Ext.Window({
                indicator:this,
                width:250,
                border:false,
                modal:true,
                draggable:false,
                closable:false,
                collapsible:false,
                autoHeight:true,
                resizable:false,
                buttonAlign: 'center',
                items: this.unlockForm,
                buttons: [{
                    width:80,
                    iconCls: 'icon-door-out',
                    text: Kebab.helper.translate('Logout...'),
                    handler: function() {
                        Kebab.getOS().logoutAction(this.user.id, true);
                    },
                    scope:this
                },{
                    width:80,
                    iconCls: 'icon-accept',
                    text: Kebab.helper.translate('Unlock'),
                    handler: function() {
                        this.unlockAction();
                    },
                    scope:this
                }]
            });
            this.window.show();
        } else {
            this.window.show();
        }
    },

    unlockAction: function() {
        var form = this.unlockForm.getForm();
        if (form.isValid()) {
            form.submit({
                waitMsg: Kebab.helper.translate('Unlocking your screen...'),
                success : function() {
                    this.window.hide();
                    form.reset();
                },
                failure: function() {
                    form.findField('password').reset();
                    form.findField('password').markInvalid();
                },
                scope:this
            });
        }
    }
});