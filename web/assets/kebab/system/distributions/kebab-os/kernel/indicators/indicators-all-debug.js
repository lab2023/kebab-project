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

Ext.namespace('Kebab.OS.Indicators.Connection');
/**
 * Kebab.OS.Indicators.Language
 *
 * @namespace   Kebab
 * @author      Tayfun Öziş ERİKAN <tayfun.ozis.erikan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */
Kebab.OS.Indicators.Connection = Ext.extend(Kebab.OS.Indicator, {

    /**
     * Component identity
     */
    id: 'kebab-os-panel-indicators-connection',

    /**
     * Initialize the component
     */
    initComponent : function() {// Setup Indicator Config

        var indicatorCfg = {
            iconCls: 'icon-server-connect',
            handler:function() {
                this.showWindowAction(); // Show detail window
            },
            scope:this
        };

        // Build connection details
        this.buildConnetionDetails();

        Ext.apply(this, indicatorCfg);

        Kebab.OS.Indicators.Connection.superclass.initComponent.call(this);
    },

    /**
     * Connection details builder
     */
    buildConnetionDetails: function() {
        // Connection Details Grid
        this.connectionDetails = new Ext.grid.GridPanel({
            frame: true,
            store: new Ext.data.JsonStore({
                autoDestroy:true,
                fields: ['url', 'method', 'status', 'statusText', 'response', 'success', 'time']
            }),
            colModel: new Ext.grid.ColumnModel({
                columns: [
                    {header: 'Url', width:300, sortable: true, dataIndex: 'url'},
                    {header: 'Method', sortable: true, dataIndex: 'method'},
                    {header: 'Status', sortable: true, dataIndex: 'status',
                        renderer: function(v, m, r) {
                            return v + ' - ' + r.data.statusText;
                        }
                    },
                    {header: 'Success', sortable: true, dataIndex: 'success',
                        renderer: function(v) {
                            var color = v ? 'green' : 'red';
                            return "<span style='color:"+color+"'>"+v+"</span>";
                        }
                    },
                    {header: 'Time', align:'right', sortable: true, dataIndex: 'time', renderer: function(v){return v +  ' sec'} }
                ]
            }),
            viewConfig: {
                forceFit:true
            }
        });
    },

    /**
     * Setup listeners
     */
    listeners: {
        afterRender: function() {
            Ext.Ajax.on('beforerequest', this.beforeRequestAction, this); // Before ajax request
            Ext.Ajax.on('requestcomplete', this.requestCompleteAction, this); // Ajax request complete
            Ext.Ajax.on('requestexception', this.requestExceptionAction, this); // Ajax request exception
        }
    },

    // ACTIONS ---------------------------------------------------------------------------------------------------------

    /**
     * Before request action
     */
    beforeRequestAction: function() {
        this.reqStart = new Date();
        this.setIconClass('icon-loading');
    },

    /**
     * Request complete action
     * @param connection
     * @param response
     * @param options
     */
    requestCompleteAction: function(connection, response, options) {
        // KBBTODO Listen and notice requests for server side messages

        this.reqStop = new Date();

        // Request diff time
        var reqDiff = (this.reqStop.getTime() - this.reqStart.getTime()) / 1000;

        this.setTooltip('Time: ' + reqDiff + ' seconds');
        this.setIconClass('icon-server-connect');

         // Populate and log data
        var responseData = Ext.util.JSON.decode(response.responseText);

        try {
            if (responseData.notifications.length > 0 ) {
                Ext.each(responseData.notifications, function(notification) {
                    Kebab.helper.message('Server Message', notification.message, notification.keep, notification.type);
                });
            }
        } catch(e) {}

        this.logAction({
            url: options.url,
            method: options.method,
            status: response.status,
            statusText: response.statusText,
            response: responseData,
            success: responseData.success,
            time: reqDiff
        });
    },

    /**
     * Request exception action
     */
    requestExceptionAction: function(connection, response, options) {

        this.setIconClass('icon-server-disconnect');
        if (response.status == 401) {
            // KBBTODO This way is too bad but time is everything :( I will review code and implement observer design pattern later.
            var sessionIndicator = Ext.getCmp('kebab-os-panel-indicators-session');
            if (sessionIndicator) {
                sessionIndicator.showLockScreenAction();
            }
        } else {
            Kebab.helper.dialog('Connection Error', 'There was a connection problem. Please try again.', 'ERR');
        }
    },

    /**
     * Log connection details
     * @param data
     */
    logAction: function(data) {
        var store = this.connectionDetails.getStore();
        var record = new store.recordType(data);
        store.insert(0, record);
    },

    /**
     * Show the details window
     */
    showWindowAction: function() {

        if (!this.window) { // If window not created
            // Detail window
            this.window = new Kebab.OS.Indicator.Window({
                title: Kebab.helper.translate('Kebab Connection'),
                indicator:this,
                width:600,
                border:false,
                height:300,
                iconCls: 'icon-connect',
                items: this.connectionDetails
            });
            this.window.show();
        } else {
            this.window.hidden ? this.window.show() : this.window.hide();
        }
    }
});

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

Ext.namespace('Kebab.OS.Indicators.Language');
/**
 * Kebab.OS.Indicators.Language
 *
 * @namespace   Kebab
 * @author      Tayfun Öziş ERİKAN <tayfun.ozis.erikan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */
Kebab.OS.Indicators.Language = Ext.extend(Kebab.OS.Indicator, {

    initComponent : function() {

        // Setup Indicator Config
        var indicatorCfg = {
            text: Kebab.helper.translate(Kebab.getOS().getTranslator().getLanguages(true).text),
            iconCls: Kebab.getOS().getTranslator().getLanguages(true).iconCls
        };

        // Merge Object Configs
        Ext.apply(this, indicatorCfg);

        Kebab.OS.Indicators.Language.superclass.initComponent.call(this);
    }
});

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

Ext.namespace('Kebab.OS.Indicators.SystemTime');
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