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

Ext.namespace('Kebab.OS.Desktop');

/**
 * Kebab.OS.Desktop
 *
 * @namespace   Kebab.OS
 * @author      Tayfun Öziş ERİKAN <tayfun.ozis.erikan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */
Kebab.OS.Desktop = Ext.extend(Ext.util.Observable, {
    
    kernel: null,
    
    activeApplicationWindow : null,
    
    applicationWindowManager : new Ext.WindowGroup(),
    
    contextMenu :null,
    
    constructor : function(kernel){

        this.kernel = kernel;

        this.windowList = new Kebab.OS.Panel.WindowList(kernel);
        this.el = Ext.getBody().createChild({tag: 'div', cls: 'x-desktop'});

        this.xTickSize = this.yTickSize = 1;

        this.kebabOsPanel = Ext.get('kebab-os-panel');
        this.kebabOsDesktop = Ext.get('kebab-os-desktop');
        this.kebabOsDesktopShortcuts = Ext.get('kebab-os-desktop-shortcuts');

        // todo: fix bug where Ext.Msg are not displayed properly
        this.applicationWindowManager.zseed = 7000; //10000;

        this.contextMenu = new Ext.menu.Menu({
            items: [{
                text: Kebab.helper.translate('Close All'),
                iconCls: 'icon-application-delete',
                handler: this.closeApplications,
                scope: this
            },{
                text: Kebab.helper.translate('Hide All'),
                iconCls: 'icon-application-put',
                handler: this.minimizeApplications,
                scope: this
            },'-',{
                text: Kebab.helper.translate('Tile Applications'),
                iconCls: 'icon-application-tile-horizontal',
                handler: this.tileApplications,
                scope: this
            },{
                text: Kebab.helper.translate('Cascade Applications'),
                iconCls: 'icon-application-cascade',
                handler: this.cascadeApplications,
                scope: this
            },'-',{
                text: Kebab.helper.translate('Appearance'),
                iconCls: 'icon-palette',
                scope: this
            }]
        });

        Kebab.OS.Desktop.superclass.constructor.call(this);

        this.initEvents();
        this.layout();
    },
    
    initEvents : function(){
        Ext.EventManager.onWindowResize(this.layout, this);
        
        this.kebabOsDesktop.on('contextmenu', function(e) {            
            if(e.target.id === this.kebabOsDesktop.id){
                e.stopEvent();
                if(!this.contextMenu.el){
                   this.contextMenu.render();
                }
                this.contextMenu.showAt(e.getXY());
            }
        },this);
        
        if (this.kebabOsDesktopShortcuts) {
            this.kebabOsDesktopShortcuts.on('click',
            function(e, t) {
                t = e.getTarget('dt', this.kebabOsDesktopShortcuts);
                if (t) {
                    e.stopEvent();
                    var application = this.kernel.getApplication(t.id.replace('-shortcut', ''));
                    if (application) {
                        application.createApplication();
                    }
                }
            },this);
        }
    },
    
    layout: function() {
       this.kebabOsDesktop.setHeight(Ext.lib.Dom.getViewHeight() - this.kebabOsPanel.getHeight());
    },
    
    createApplication : function(config, cls) {

        var appWin = new(cls || Ext.Window)(

            Ext.applyIf(config || {},
            {
                renderTo: this.kebabOsDesktop,
                manager: this.applicationWindowManager,
                minimizable: true,
                maximizable: true,
                collapsible: true,
                constrainHeader:true,
                shim:false,
                layout:'fit',
                animShowCfg: {
                    duration: .0,
                    easing: 'backOut'
                },
                animHideCfg: {
                    duration: .15,
                    easing: 'easeIn'
                },
                tools: [{
                    id:'gear',
                    qtip: Kebab.helper.translate('Send feedback this application'),
                    handler: function(event, toolEl, panel) {
                        /* KBBTODO Sending custom parameters to another application
                        (application wiring & event listening)
                         */
                       this.launchApplication('feedback-application');
                    },
                    scope:this
                }]
            })
        );
        appWin.dd.xTickSize = this.xTickSize;
        appWin.dd.yTickSize = this.yTickSize;
        if (appWin.resizer) {
            appWin.resizer.widthIncrement = this.xTickSize;
            appWin.resizer.heightIncrement = this.yTickSize;
        }
        appWin.render(this.kebabOsDesktop);
        appWin.taskButton = this.windowList.addTaskButton(appWin);

        appWin.cmenu = new Ext.menu.Menu({
            items: []
        });

        appWin.animateTarget = appWin.taskButton.el;
        //appWin.animateTarget = Ext.get(appWin.id + '-shortcut');

        appWin.on({
            'activate': {
                fn: this.markActive,
                scope: this
            },
            'beforeshow': {
                fn: this.markActive,
                scope:this
            },
            'deactivate': {
                fn: this.markInactive,
                scope:this
            },
            'minimize': {
                fn: this.minimizeApplication,
                scope:this
            },
            'close': {
                fn: this.removeApplication,
                scope:this
            }
        });

        this.layout();

        Kebab.helper.message(appWin.title, 'Initialized...');

        return appWin;
    },

    getManager: function() {
        return this.applicationWindowManager;
    },

    getApplication: function(id) {
        return this.applicationWindowManager.get(id);
    },

    launchApplication : function(id){
        this.kernel.callApplication(id, function(app){
            if(app){
                app.createApplication();
            }
        }, this);
    },

    getWinWidth: function() {
        var width = Ext.lib.Dom.getViewWidth();
        return width < 200 ? 200: width;
    },

    getWinHeight: function() {
        var height = (Ext.lib.Dom.getViewHeight() - this.kebabOsPanel.getHeight());
        return height < 100 ? 100: height;
    },

    getWinX: function(width) {
        return (Ext.lib.Dom.getViewWidth() - width) / 2;
    },

    getWinY: function(height) {
        return (Ext.lib.Dom.getViewHeight() - this.kebabOsPanel.getHeight() - height) / 2;
    },

    setTickSize: function(xTickSize, yTickSize) {

        this.xTickSize = xTickSize;
        if (arguments.length == 1) {
            this.yTickSize = xTickSize;
        } else {
            this.yTickSize = yTickSize;
        }
        this.applicationWindowManager.each(function(win) {
            win.dd.xTickSize = this.xTickSize;
            win.dd.yTickSize = this.yTickSize;
            win.resizer.widthIncrement = this.xTickSize;
            win.resizer.heightIncrement = this.yTickSize;
        },
        this);
    },

    minimizeApplication: function(appWin) {
        appWin.minimized = true;
        appWin.hide();
    },

    markActive: function(appWin) {
        if (this.activeApplicationWindow && this.activeApplicationWindow != appWin) {
            this.markInactive(this.activeApplicationWindow);
        }
        this.windowList.setActiveButton(appWin.taskButton);
        this.activeApplicationWindow = appWin;
        Ext.fly(appWin.taskButton.el).addClass('active-application-window');
        appWin.minimized = false;
    },

    markInactive: function(appWin) {
        if (appWin == this.activeApplicationWindow) {
            this.activeApplicationWindow = null;
            Ext.fly(appWin.taskButton.el).removeClass('active-application-window');
        }
    },

    removeApplication : function(appWin) {
        this.windowList.removeTaskButton(appWin.taskButton);
        this.layout();
    },


    closeApplications : function() {
        this.applicationWindowManager.each(function(app) {
            app.close();
        }, this);
    },

    minimizeApplications : function() {
        this.applicationWindowManager.each(function(app) {
            this.minimizeApplication(app);
        }, this);
    },

    cascadeApplications: function() {
        var x = 0,
        y = 0;
        this.applicationWindowManager.each(function(win) {
            if (win.isVisible() && !win.maximized) {
                win.setPosition(x, y);
                x += 20;
                y += 20;
            }
        },
        this);
    },

    tileApplications: function() {
        var availWidth = this.kebabOsDesktop.getWidth(true);
        var x = this.xTickSize;
        var y = this.yTickSize;
        var nextY = y;
        this.applicationWindowManager.each(function(win) {
            if (win.isVisible() && !win.maximized) {
                var w = win.el.getWidth();

                //Wrap to next row if we are not at the line start and this Window will go off the end
                if ((x > this.xTickSize) && (x + w > availWidth)) {
                    x = this.xTickSize;
                    y = nextY;
                }

                win.setPosition(x, y);
                x += w + this.xTickSize;
                nextY = Math.max(nextY, y + win.el.getHeight() + this.yTickSize);
            }
        },
        this);
    },

    /**
     * @param {string} hex The hexadecimal number for the color.
     */
    setBackgroundColor : function(hex){
        if(hex){
            Ext.get(document.body).setStyle('background-color', '#'+hex);
        }
    },

    /**
     * @param {string} hex The hexadecimal number for the color.
     */
    setFontColor : function(hex){
        if(hex){
            // KBBTODO find shortcut class and replace
            Ext.util.CSS.updateRule('.ux-shortcut-btn-text', 'color', '#'+hex);
        }
    }
});