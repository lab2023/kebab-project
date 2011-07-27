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