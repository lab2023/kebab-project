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
    applicationsMenu: null,
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

        var applicationsCombobox = new Ext.form.ComboBox({
            tpl:'<tpl for="."><div ext:qtip="{description}" class="x-combo-list-item">{title}</div></tpl>',
            storeLoaded: false,
            emptyText: Kebab.helper.translate('Quick start...'),
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

        this.indicatorsToolbar = new Ext.Toolbar({
            renderTo: 'kebab-os-panel-indicators',
            items: [applicationsCombobox, {
                iconCls : 'icon-status-online',
                template: this.buttonTpl,
                text: this.user.fullName,
                handler: function() {
                    this.kernel.getDesktop().launchApplication('aboutMe-application');
                },
                scope:this
            }, {
                template: this.buttonTpl,
                text: Kebab.helper.translate(Kebab.getOS().getTranslator().getLanguages('active').text),
                iconCls: Kebab.getOS().getTranslator().getLanguages('active').iconCls
            }, {
                iconCls : 'icon-shutdown',
                template: this.buttonTpl,
                menu: [{
                    text: Kebab.helper.translate('Logout...'),
                    iconCls: 'icon-door-out',
                    handler: function() {
                        Kebab.getOS().logoutAction(this.user.id, true);
                    },
                    scope:this
                },{
                    text: Kebab.helper.translate('Reboot...'),
                    iconCls: 'icon-arrow-refresh',
                    handler: function() {
                        Kebab.getOS().rebootAction();
                    }
                },{
                    text: Kebab.helper.translate('Shutdown...'),
                    iconCls: 'icon-shutdown',
                    handler: function() {
                        //Kebab.getOS().shutDownAction(this.user.id);
                    },
                    scope:this
                }]
            }]
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
    animScroll: true,
    resizeButtons: true,
    buttonWidth: 168,
    minButtonWidth: 118,
    buttonMargin: 2,
    buttonWidthSet: false,

    initComponent : function() {
        
        Kebab.OS.Panel.WindowList.Buttons.superclass.initComponent.call(this);
        
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
        text: Ext.util.Format.ellipsis(win.title, 18),
        tooltip: win.description,
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
                handler: this.win.minimize,
                scope: this.win
            },{
                text: Kebab.helper.translate('Maximize'),
                handler: this.win.maximize,
                scope: this.win
            }, '-', {
                text: Kebab.helper.translate('Close'),
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