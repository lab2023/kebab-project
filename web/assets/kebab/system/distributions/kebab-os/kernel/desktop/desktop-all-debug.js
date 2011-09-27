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

Ext.namespace('Kebab.OS.Kernel');

/**
 * Kebab.OS.Kernel
 *
 * @namespace   Kebab.OS
 * @author      Tayfun Öziş ERİKAN <tayfun.ozis.erikan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */
Kebab.OS.Kernel = function(config){

    // Initial Configs
    this.isBooted = false;
    this.serviceAPI = 'api';
    this.distribution = 'KebabOS';
    this.assets = null;
    this.settings = null;
    this.user = null;
    this.apps = null;
    this.stories = null;
    this.applications = null;
    this.stateProvider = new Ext.state.CookieProvider();

    Ext.apply(this, config);

    this.addEvents({
        'booted' : true,
        'beforeunload' : true
    });

    Ext.onReady(this.boot, this);
};
Ext.extend(Kebab.OS.Kernel, Ext.util.Observable, {

    init : function() {

        Ext.state.Manager.setProvider(this.stateProvider);

        Kebab.helper.log('Kebab.OS.Kernel initialized...');
    },

    boot : function(){

        this.init();

        // Initialize Desktop
        this.desktop = new Kebab.OS.Desktop(this);

        this.windowList = this.desktop.windowList;

        this.applications = this.applications || this.loadApplications();

        if(this.applications) {
            this.initApplications();
        }

        Ext.EventManager.on(window, 'beforeunload', this.onUnload, this);
        this.fireEvent('booted', this);
        this.isBooted = true;

        if (this.isBooted) {
            Kebab.helper.log('Kebab.OS.Kernel booted...');
            Kebab.helper.message('Kebab Project', 'Welcome to Kebab Project. Have fun!');
        }
    },

    loadApplications: function() {

        var i = 0,
            applicationInstances = new Array();

        Ext.each(this.apps, function(application) {

            try {
                applicationInstances[i++] = Ext.applyIf(
                    eval('new ' + application.className + '()'),
                    application
                );
            } catch(e) {
                Kebab.helper.log(application.className + ' not loaded...', 'ERR');
            }
        });

        Kebab.helper.log('Kebab applications loaded...');

        return applicationInstances;
    },

    initApplications : function(){

        var appSC = this.applications;

        for(var i = 0, len = appSC.length; i < len; i++){
            var app = appSC[i];

            var launcher = Ext.apply(app.launcher, app.title);

            this.windowList.addLauncherMenuItem(launcher, app.type);
            app.app = this;

            // Check launcher auto start flag and call createApplication()
            if (app.launcher.autoStart) {
                app.createApplication();
            }
        }
    },

    getApplication : function(id) {

        var app = this.applications;

        for (var i = 0, len = app.length; i < len; i++) {
            if (app[i].id == id || app[i].appType == id) {
                return app[i];
            }
        }
        return null;
    },

    getApplications : function() {
        return this.applications;
    },

    callApplication : function(id, cb, scope){
        var app = this.getApplication(id);
        if(app){
            cb.call(scope, app)
        }
    },

    getUser : function(){
        return this.user;
    },

    getAssets : function(){
        return this.assets;
    },

    getSettings : function(){
        return this.settings;
    },

    getDesktop : function(){
        return this.desktop;
    },

    onReady : function(fn, scope){
        if(!this.isBooted){
            this.on('booted', fn, scope);
        }else{
            fn.call(scope, this);
        }
    },

    onUnload : function(e){
        if(this.fireEvent('beforeunload', this) === false){
            e.stopEvent();
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

Ext.namespace('Kebab.OS.Application');

/**
 * Kebab.OS.Application
 *
 * @namespace   Kebab.OS
 * @author      Tayfun Öziş ERİKAN <tayfun.ozis.erikan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */
Kebab.OS.Application = function(){

    // Application translations
    this.translations = null;

    // Base Application Launcher Settings
    this.launcher = Ext.applyIf(this.launcher, {
        text: 'Default Application' ,
        iconCls: 'default-application-launcher-icon',
        handler: this.createApplication,
        scope: this
    });

    // Run init methods
    this.init();
    this.initResources();
    this.initTranslations();

    // Call constructor
    Kebab.OS.Application.superclass.constructor.call(this);

    Kebab.helper.log(this.id + ' application initialized...');
};

Ext.extend(Kebab.OS.Application, Ext.util.Observable, {
    init : Ext.emptyFn,
    createApplication : Ext.emptyFn,

    /**
     * Init application resources
     */
    initResources: function() {
        try { // Kebab OS resources
            this.resources = {
                getTranslator: function() {
                    return Kebab.getOS().getTranslator();
                },
                getLogger: function() {
                    return Kebab.getOS().getLogger();
                },
                getNotification: function() {
                    return Kebab.getOS().getNotification();
                }
            };
        } catch(e) {
            Kebab.getOS().getLogger().log(this.id + ' resources not loaded...', 'ERR');
        }
    },

    /**
     * Init application languages
     */
    initTranslations: function() {

        // Application name replacement
        var appName = this.id.replace('-application', '').trim();
        var _replaceNameSpace = function() {
            return "KebabOS.applications.APP_NAME.application.languages".replace('APP_NAME', appName).trim();
        };

        // Load translations
        var translations = eval(_replaceNameSpace());

        // Add translations to translator object
        this.getResources().getTranslator().addTranslations(translations[this.getResources().getTranslator().getLocale()]);

        // Set the translations
        this.setTranslations(translations);
    },

    /**
     * Set application translations
     */
    setTranslations: function(translations) {
        this.translations = translations;
    },

    /**
     * Get application's active or all translations
     * @param active boolean
     */
    getTranslations: function(active) {
        return active ? this.translations[this.getResources().getTranslator().getLocale()]
                      : this.translations;
    },

    /**
     * Get the application resources
     */
    getResources: function() {
        return this.resources;
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

Ext.namespace('Kebab.OS.Panel');

/**
 * Kebab.OS.Panel.WindowList
 *
 * @namespace   Kebab.OS.Panel
 * @author      Tayfun Öziş ERİKAN <tayfun.ozis.erikan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */
Kebab.OS.Panel.WindowList = function(kernel){
    this.kernel = kernel;
    this.init();
};

Ext.extend(Kebab.OS.Panel.WindowList, Ext.util.Observable, {

    user: null,
    mainMenu: null,
    mainMenuComponent: null,
    applicationsMenu: null,
    indicatorsAreaComponent: null,
    systemMenu: null,
    buttonTpl:  new Ext.Template(
        '<table cellspacing="0" class="x-btn"><tbody class="{1}"><tr>',
        '<td><em class="{2}">',
        '<button class="x-btn-text" type="{0}" style="height:30px;"></button>',
        '</em></td>',
        '</tr></tbody></table>'
    ),

    init : function(){

        // Access to active user data
        this.user = this.kernel.getUser();

        new Kebab.OS.Panel.Container({
            el: 'kebab-os-panel',
            layout: 'border',
            items: [
                this.buildMainMenuArea(),
                this.buildWindowListArea(),
                this.buildIndicatorsArea()
            ]
        });
        return this;
    },

    /**
     * Build system main menu area
     */
    buildMainMenuArea: function() {

        this.applicationsMenu = {
            text: Kebab.helper.translate('Applications'),
            id: 'kebab-os-panel-main-menu-applications',
            iconCls: 'icon-kebab-os',
            template: new Ext.Template(
                '<table cellspacing="0" class="x-btn"><tbody class="{1}"><tr>',
                '<td><em class="{2}">',
                '<button id="kebab-os-panel-main-menu-applications-button" class="x-btn-text" type="{0}" style="height:30px;"></button>',
                '</em></td>',
                '</tr></tbody></table>'
            ),
            menu: new Ext.menu.Menu()
        };

        this.systemMenu = {
            text: Kebab.helper.translate('System'),
            id: 'kebab-os-panel-main-menu-system',
            template: this.buttonTpl,
            menuWidth: 200,
            menu: new Ext.menu.Menu()
        };

        this.mainMenu = new Ext.Toolbar({
            renderTo: 'kebab-os-panel-main-menu',
            items: [
                this.applicationsMenu,
                this.systemMenu
            ]
        });

        return this.mainMenuComponent = new Ext.BoxComponent({
            el: 'kebab-os-panel-main-menu',
            region:'west',
            width:185,
            minSize:185,
            maxSize:185,
            split: true
        });
    },

    /**
     * Window list area
     */
    buildWindowListArea: function() {

        return this.windowList = new Kebab.OS.Panel.WindowList.Buttons({
            el: 'kebab-os-panel-window-list',
            region:'center'
        });

    },

    /**
     * Build system indicators area
     */
    buildIndicatorsArea: function() {

        // Indicator Objects
        var indicators = [
            new Kebab.OS.Indicators.QuickStart({kernel: this.kernel}),
            new Kebab.OS.Indicators.Connection({kernel: this.kernel}),
            new Kebab.OS.Indicators.Language({kernel: this.kernel}),
            new Kebab.OS.Indicators.SystemTime({kernel: this.kernel}),
            new Kebab.OS.Indicators.User({kernel: this.kernel, user:this.user}),
            new Kebab.OS.Indicators.Session({kernel: this.kernel, user:this.user})
        ];

        // Build Toolbar
        this.indicatorsToolbar = new Ext.Toolbar({
            renderTo: 'kebab-os-panel-indicators',
            items: indicators
        });

        /**
         * Calculate indicator items total width
         */
        var totalIndicatorsWidth = 5; // Initial value
        this.indicatorsToolbar.items.each(function(item) {
           totalIndicatorsWidth += item.getWidth();
        });

        return this.indicatorsAreaComponent = new Ext.BoxComponent({
            el: 'kebab-os-panel-indicators',
            width: totalIndicatorsWidth,
            minWidth: totalIndicatorsWidth,
            region:'east',
            split:true
        });
    },

    /**
     * Add and separate launcher items to application and system menu area
     * @param item
     * @param menu
     */
    addLauncherMenuItem: function(item, menu) {

        var launcherMenu = (menu == 'application')
                        ? this.applicationsMenu
                        : this.systemMenu;

        launcherMenu.menu.add(item);
    },

    addTaskButton : function(win){
        return this.windowList.addButton(win, 'kebab-os-panel-window-list');
    },

    removeTaskButton : function(btn){
        this.windowList.removeButton(btn);
    },

    setActiveButton : function(btn){
        this.windowList.setActiveButton(btn);
    }
});

/**
 * Kebab.OS.Panel.Container
 *
 * @namespace   Kebab.OS.Panel
 * @author      Tayfun Öziş ERİKAN <tayfun.ozis.erikan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */
Kebab.OS.Panel.Container = Ext.extend(Ext.Container, {

    initComponent : function() {

        Kebab.OS.Panel.Container.superclass.initComponent.call(this);

        this.el = Ext.get(this.el) || Ext.getBody();
        this.el.setHeight = Ext.emptyFn;
        this.el.setWidth = Ext.emptyFn;
        this.el.setSize = Ext.emptyFn;
        this.el.setStyle({
            overflow:'hidden',
            margin:'0',
            border:'0 none'
        });
        this.el.dom.scroll = 'no';
        this.allowDomMove = false;
        this.autoWidth = true;
        this.autoHeight = true;
        Ext.EventManager.onWindowResize(this.fireResize, this);
        this.renderTo = this.el;
    },

    fireResize : function(w, h){
        this.onResize(w, h, w, h);
        this.fireEvent('resize', this, w, h, w, h);
    }
});

/**
 * Kebab.OS.Panel.WindowList.Buttons
 *
 * @namespace   Kebab.OS.Panel.WindowList
 * @author      Tayfun Öziş ERİKAN <tayfun.ozis.erikan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */
Kebab.OS.Panel.WindowList.Buttons = Ext.extend(Ext.BoxComponent, {
    activeButton: null,
    enableScroll: true,
    scrollIncrement: 0,
    scrollRepeatInterval: 400,
    scrollDuration: .35,
    animScroll: false,
    resizeButtons: true,
    buttonWidth: 40,
    minButtonWidth: 40,
    buttonMargin: 2,
    buttonWidthSet: false,

    initComponent : function() {

        this.on('resize', this.delegateUpdates);

        this.items = [];

        this.stripWrap = Ext.get(this.el).createChild({
            cls: 'ux-taskbuttons-strip-wrap',
            cn: {
                tag:'ul', cls:'ux-taskbuttons-strip'
            }
        });
        this.stripSpacer = Ext.get(this.el).createChild({
            cls:'ux-taskbuttons-strip-spacer'
        });
        this.strip = new Ext.Element(this.stripWrap.dom.firstChild);

        this.edge = this.strip.createChild({
            tag:'li',
            cls:'ux-taskbuttons-edge'
        });
        this.strip.createChild({
            cls:'x-clear'
        });

        Kebab.OS.Panel.WindowList.Buttons.superclass.initComponent.call(this);

    },

    addButton : function(win){
        var li = this.strip.createChild({tag:'li'}, this.edge); // insert before the edge
        var btn = new Kebab.OS.Panel.WindowList.TaskButton(win, li);

        this.items.push(btn);

        if(!this.buttonWidthSet){
            this.lastButtonWidth = btn.container.getWidth();
        }

        this.setActiveButton(btn);
        return btn;
    },

    removeButton : function(btn){
        var li = document.getElementById(btn.container.id);
        btn.destroy();
        li.parentNode.removeChild(li);

        var s = [];
        for(var i = 0, len = this.items.length; i < len; i++) {
            if(this.items[i] != btn){
                s.push(this.items[i]);
            }
        }
        this.items = s;

        this.delegateUpdates();
    },

    setActiveButton : function(btn){
        this.activeButton = btn;
        this.delegateUpdates();
    },

    delegateUpdates : function(){
        /*if(this.suspendUpdates){
            return;
        }*/
        if(this.resizeButtons && this.rendered){
            this.autoSize();
        }
        if(this.enableScroll && this.rendered){
            this.autoScroll();
        }
    },

    autoSize : function(){
        var count = this.items.length;
        var ow = this.el.dom.offsetWidth;
        var aw = this.el.dom.clientWidth;

        if(!this.resizeButtons || count < 1 || !aw){ // !aw for display:none
            return;
        }

        var each = Math.max(Math.min(Math.floor((aw-4) / count) - this.buttonMargin, this.buttonWidth), this.minButtonWidth); // -4 for float errors in IE
        var btns = this.stripWrap.dom.getElementsByTagName('button');

        this.lastButtonWidth = Ext.get(btns[0].id).findParent('li').offsetWidth;

        for(var i = 0, len = btns.length; i < len; i++) {
            var btn = btns[i];

            var tw = Ext.get(btns[i].id).findParent('li').offsetWidth;
            var iw = btn.offsetWidth;

            btn.style.width = (each - (tw-iw)) + 'px';
        }
    },

    autoScroll : function(){
        var count = this.items.length;
        var ow = this.el.dom.offsetWidth;
        var tw = this.el.dom.clientWidth;

        var wrap = this.stripWrap;
        var cw = wrap.dom.offsetWidth;
        var pos = this.getScrollPos();
        var l = this.edge.getOffsetsTo(this.stripWrap)[0] + pos;

        if(!this.enableScroll || count < 1 || cw < 20){ // 20 to prevent display:none issues
            return;
        }

        wrap.setWidth(tw); // moved to here because of problem in Safari

        if(l <= tw){
            wrap.dom.scrollLeft = 0;
            //wrap.setWidth(tw); moved from here because of problem in Safari
            if(this.scrolling){
                this.scrolling = false;
                this.el.removeClass('x-taskbuttons-scrolling');
                this.scrollLeft.hide();
                this.scrollRight.hide();
            }
        }else{
            if(!this.scrolling){
                this.el.addClass('x-taskbuttons-scrolling');
            }
            tw -= wrap.getMargins('lr');
            wrap.setWidth(tw > 20 ? tw : 20);
            if(!this.scrolling){
                if(!this.scrollLeft){
                    this.createScrollers();
                }else{
                    this.scrollLeft.show();
                    this.scrollRight.show();
                }
            }
            this.scrolling = true;
            if(pos > (l-tw)){ // ensure it stays within bounds
                wrap.dom.scrollLeft = l-tw;
            }else{ // otherwise, make sure the active button is still visible
                this.scrollToButton(this.activeButton, true); // true to animate
            }
            this.updateScrollButtons();
        }
    },

    createScrollers : function(){
        var h = this.el.dom.offsetHeight; //var h = this.stripWrap.dom.offsetHeight;

        // left
        var sl = this.el.insertFirst({
            cls:'ux-taskbuttons-scroller-left'
        });
        sl.setHeight(h);
        sl.addClassOnOver('ux-taskbuttons-scroller-left-over');
        this.leftRepeater = new Ext.util.ClickRepeater(sl, {
            interval : this.scrollRepeatInterval,
            handler: this.onScrollLeft,
            scope: this
        });
        this.scrollLeft = sl;

        // right
        var sr = this.el.insertFirst({
            cls:'ux-taskbuttons-scroller-right'
        });
        sr.setHeight(h);
        sr.addClassOnOver('ux-taskbuttons-scroller-right-over');
        this.rightRepeater = new Ext.util.ClickRepeater(sr, {
            interval : this.scrollRepeatInterval,
            handler: this.onScrollRight,
            scope: this
        });
        this.scrollRight = sr;
    },

    getScrollWidth : function(){
        return this.edge.getOffsetsTo(this.stripWrap)[0] + this.getScrollPos();
    },

    getScrollPos : function(){
        return parseInt(this.stripWrap.dom.scrollLeft, 10) || 0;
    },

    getScrollArea : function(){
        return parseInt(this.stripWrap.dom.clientWidth, 10) || 0;
    },

    getScrollAnim : function(){
        return {
            duration: this.scrollDuration,
            callback: this.updateScrollButtons,
            scope: this
        };
    },

    getScrollIncrement : function(){
        return (this.scrollIncrement || this.lastButtonWidth+2);
    },

    /* getBtnEl : function(item){
        return document.getElementById(item.id);
    }, */

    scrollToButton : function(item, animate){
        item = item.el.dom.parentNode; // li
        if(!item){return;}
        var el = item; //this.getBtnEl(item);
        var pos = this.getScrollPos(), area = this.getScrollArea();
        var left = Ext.fly(el).getOffsetsTo(this.stripWrap)[0] + pos;
        var right = left + el.offsetWidth;
        if(left < pos){
            this.scrollTo(left, animate);
        }else if(right > (pos + area)){
            this.scrollTo(right - area, animate);
        }
    },

    scrollTo : function(pos, animate){
        this.stripWrap.scrollTo('left', pos, animate ? this.getScrollAnim() : false);
        if(!animate){
            this.updateScrollButtons();
        }
    },

    onScrollRight : function(){
        var sw = this.getScrollWidth()-this.getScrollArea();
        var pos = this.getScrollPos();
        var s = Math.min(sw, pos + this.getScrollIncrement());
        if(s != pos){
            this.scrollTo(s, this.animScroll);
        }
    },

    onScrollLeft : function(){
        var pos = this.getScrollPos();
        var s = Math.max(0, pos - this.getScrollIncrement());
        if(s != pos){
            this.scrollTo(s, this.animScroll);
        }
    },

    updateScrollButtons : function(){
        var pos = this.getScrollPos();
        this.scrollLeft[pos == 0 ? 'addClass' : 'removeClass']('ux-taskbuttons-scroller-left-disabled');
        this.scrollRight[pos >= (this.getScrollWidth()-this.getScrollArea()) ? 'addClass' : 'removeClass']('ux-taskbuttons-scroller-right-disabled');
    }
});

/**
 * Kebab.OS.Panel.WindowList.TaskButton
 *
 * @namespace   Kebab.OS.Panel.WindowList
 * @author      Tayfun Öziş ERİKAN <tayfun.ozis.erikan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */
Kebab.OS.Panel.WindowList.TaskButton = function(win, el){

    this.win = win;

    Kebab.OS.Panel.WindowList.TaskButton.superclass.constructor.call(this, {
        iconCls: win.iconCls,
        //text: Ext.util.Format.ellipsis(win.title, 18),
        tooltip: '<strong>' + win.title + '</strong><br/>' + win.description,
        renderTo: el,
        handler : function(){
            if(win.minimized || win.hidden){
                win.show();
            }else if(win == win.manager.getActive()){
                win.minimize();
            }else{
                win.toFront();
            }
        },
        clickEvent:'mousedown',
        template: new Ext.Template(
            '<table cellspacing="0" class="x-btn {3}"><tbody><tr>',
            '<td class="ux-taskbutton-left"><em>&#160;</em></td>',
            '<td class="ux-taskbutton-center"><em class="{5} unselectable="on">',
                '<button class="x-btn-text {2}" type="{1}" style="height:28px;">{0}</button>',
            '</em></td>',
            '<td class="ux-taskbutton-right"><em>&#160;</em></td>',
            "</tr></tbody></table>")
    });
};

Ext.extend(Kebab.OS.Panel.WindowList.TaskButton, Ext.Button, {
    onRender : function(){
        Kebab.OS.Panel.WindowList.TaskButton.superclass.onRender.apply(this, arguments);

        this.cmenu = new Ext.menu.Menu({
            items: [{
                text: Kebab.helper.translate('Restore'),
                iconCls: 'icon-arrow-inout',
                handler: function(){
                    if(!this.win.isVisible()){
                        this.win.show();
                    }else{
                        this.win.restore();
                    }
                },
                scope: this
            },{
                text: Kebab.helper.translate('Minimize'),
                iconCls: 'icon-arrow-in',
                handler: this.win.minimize,
                scope: this.win
            },{
                text: Kebab.helper.translate('Maximize'),
                iconCls: 'icon-arrow-out',
                handler: this.win.maximize,
                scope: this.win
            }, '-', {
                text: Kebab.helper.translate('Close'),
                iconCls: 'icon-cancel',
                handler: this.closeWin.createDelegate(this, this.win, true),
                scope: this.win
            }]
        });

        this.cmenu.on('beforeshow', function(){
            var items = this.cmenu.items.items;
            var w = this.win;
            items[0].setDisabled(w.maximized !== true && w.hidden !== true);
            items[1].setDisabled(w.minimized === true);
            items[2].setDisabled(w.maximized === true || w.hidden === true);
        }, this);

        this.el.on('contextmenu', function(e){
            e.stopEvent();
            if(!this.cmenu.el){
                this.cmenu.render();
            }
            var xy = e.getXY();
            xy[1] -= this.cmenu.el.getHeight() - 110;
            this.cmenu.showAt(xy);
        }, this);
    },

    closeWin : function(cMenu, e, win){
        if(!win.isVisible()){
            win.show();
        }else{
            win.restore();
        }
        win.close();
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