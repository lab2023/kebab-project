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
    * http://www.kebab-project.com/licensing
    * If you did not receive a copy of the license and are unable to
    * obtain it through the world-wide-web, please send an email
    * to info@lab2023.com so we can send you a copy immediately.
----------------------------------------------------------------------------- */

Ext.namespace('Kebab.OS.Panel');
Kebab.OS.Panel.WindowList = function(kernel){
    this.kernel = kernel;
    this.init();
}
Ext.extend(Kebab.OS.Panel.WindowList, Ext.util.Observable, {
    
    init : function(){
        
        // Only menu! Ext.menu.Menu() object
        this.mainMenu = new Ext.menu.Menu();
                
        this.applications = new Ext.Toolbar().render('kebab-os-panel-main-menu');
        
        var applicationsButtonTemplate = new Ext.Template(
            '<table cellspacing="0" class="x-btn"><tbody class="{1}"><tr>',
            '<td><em class="{2} unselectable="on">',
            '<button id="kebab-os-panel-main-menu-applications-button" class="x-btn-text" type="{0}" style="height:30px;"></button>',
            '</em></td>',
            '</tr></tbody></table>'
            );
        var stdButtonTemplate = new Ext.Template(
            '<table cellspacing="0" class="x-btn"><tbody class="{1}"><tr>',
            '<td><em class="{2} unselectable="on">',
            '<button class="x-btn-text" type="{0}" style="height:30px;"></button>',
            '</em></td>',
            '</tr></tbody></table>'
            );
        
        var baseMenu = ['-',{
            text:'Kebab Application Center',
            iconCls: 'icon-box'
        }];
        
        var applicationsMenu = {
            text:'Applications',
            id: 'kebab-os-panel-main-menu-applications',
            iconCls: 'icon-kebab-os',
            template: applicationsButtonTemplate,
            menu: this.mainMenu  // assign menu by instance
        };
        
        var systemMenu = {
            text:'System',
            id: 'kebab-os-panel-main-menu-system',
            template: stdButtonTemplate,
            menuWidth: 200,
            menu: [{
                id: 'kebab-os-panel-main-menu-system-preferences',
                text: 'Preferences',
                iconCls: 'icon-brick',
                menu: [{
                   text: 'Preparing...',
                   iconCls: 'icon-hourglass'
                }]
            }, {
               id: 'kebab-os-panel-main-menu-system-administration',
               text: 'Administration',
               iconCls: 'icon-award-star-silver-3',
               menu: [{
                   text: 'Preparing...',
                   iconCls: 'icon-hourglass'
               }]
            }, '-', {
               text: 'Help and Support',
               iconCls: 'icon-book-open',
               handler: function() {
                    var desktop = this.kernel.getDesktop();
                    var app = desktop.getApplication(this.id);
                    if(!app){
                        app = desktop.createApplication({
                            id: this.id,
                            title:'Help and Support',
                            width:750,
                            height:500,
                            iconCls:'icon-information',
                            html: '<iframe style="overflow:auto;width:100%;height:100%;" frameborder="0"  src="http://www.kebab-project.com/community"></iframe>'
                        });
                    }
                    app.show();
               },
               scope:this
            }, {
               text: 'About Kebab',
               iconCls: 'icon-information',
               handler: function() {
                    var desktop = this.kernel.getDesktop();
                    var app = desktop.getApplication(this.id);
                    if(!app){
                        app = desktop.createApplication({
                            id: this.id,
                            title:'About Kebab',
                            width:1024,
                            height:600,
                            iconCls:'icon-information',
                            html: '<iframe style="overflow:auto;width:100%;height:100%;" frameborder="0"  src="http://www.kebab-project.com"></iframe>'
                        });
                    }
                    app.show();
               },
               scope:this
            }, {
               text: 'About lab2023',
               iconCls: 'icon-information',
               handler: function() {
                    var desktop = this.kernel.getDesktop();
                    var app = desktop.getApplication(this.id);
                    if(!app){
                        app = desktop.createApplication({
                            id: this.id,
                            title:'About lab2023',
                            width:1024,
                            height:600,
                            iconCls:'icon-information',
                            html: '<iframe style="overflow:auto;width:100%;height:100%;" frameborder="0"  src="http://www.lab2023.com"></iframe>'
                        });
                    }
                    app.show();
               },
               scope:this
            }]
        };
        
        this.applications.add([
            applicationsMenu,
            systemMenu
        ]);       
        this.applications.doLayout();

        this.applicationsMenu = new Ext.BoxComponent({
            el: 'kebab-os-panel-main-menu',
            id: 'kebab-os-panel-main-menu',
            region:'west',
            width:185,
            minSize:185,
            maxSize:185,
            split: true,
            items: [this.applications]
        });
        
        var user = this.kernel.getUser();
        
        this.indicatorsToolbar = new Ext.Toolbar().render('kebab-os-panel-indicators');
        this.indicatorsToolbar.add({
            iconCls : 'icon-status-online',
            template: stdButtonTemplate,
            text: user.firstName + ' ' + user.surname,
            menu: [{
                text:'Avaliable',
                iconCls: 'icon-status-online'
            },{
                text:'Away',
                iconCls: 'icon-status-away'
            },{
                text:'Busy',
                iconCls: 'icon-status-busy'
            },{
                text:'Offline',
                iconCls: 'icon-status-offline'
            }, '-', {
                text:'About Me',
                iconCls: 'icon-vcard'
            }]
        });
        this.indicatorsToolbar.add({
            iconCls : 'icon-shutdown',
            template: stdButtonTemplate,
            menu: [{
               text: 'Logout',
               iconCls: 'icon-door-out',
               handler: function() {
                    window.location.href= '/auth/logout';
               }
            }, {
               text: 'Restart',
               iconCls: 'icon-information',
               handler: function() {
                    window.location.href= '/main';
               }
            }, {
               text: 'Shut Down',
               iconCls: 'icon-information',
               handler: function() {
                    window.close();
               }
            }]
        });
        this.indicatorsToolbar.doLayout();
        
        var totalIndicatorsWidth = this.indicatorsToolbar.items.itemAt(0).getWidth() + this.indicatorsToolbar.items.itemAt(1).getWidth();
        this.indicatorsMenu = new Ext.BoxComponent({
            el: 'kebab-os-panel-indicators',
            id: 'kebab-os-panel-indicators',
            width: totalIndicatorsWidth,
            minWidth: totalIndicatorsWidth,
            region:'east',
            split:true
        });

        this.windowList = new Kebab.OS.Panel.WindowList.Buttons({
            el: 'kebab-os-panel-window-list',
            id: 'kebab-os-panel-window-list',
            region:'center'
        });

        var kebabOsPanelContainer = new Kebab.OS.Panel.Container({
            el: 'kebab-os-panel',
            layout: 'border',
            items: [
                this.applicationsMenu, 
                this.windowList,
                this.indicatorsMenu                
            ]
        });
        return this;
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

Kebab.OS.Panel.WindowList.TaskButton = function(win, el){
    
    this.win = win;
    
    Kebab.OS.Panel.WindowList.TaskButton.superclass.constructor.call(this, {
        iconCls: win.iconCls,
        text: Ext.util.Format.ellipsis(win.title, 18),
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
            '<td class="ux-taskbutton-left"><i>&#160;</i></td>',
            '<td class="ux-taskbutton-center"><em class="{5} unselectable="on">',
                '<button class="x-btn-text {2}" type="{1}" style="height:28px;">{0}</button>',
            '</em></td>',
            '<td class="ux-taskbutton-right"><i>&#160;</i></td>',
            "</tr></tbody></table>")
    });
};

Ext.extend(Kebab.OS.Panel.WindowList.TaskButton, Ext.Button, {
    onRender : function(){
        Kebab.OS.Panel.WindowList.TaskButton.superclass.onRender.apply(this, arguments);

        this.cmenu = new Ext.menu.Menu({
            items: [{
                text: 'Restore',
                handler: function(){
                    if(!this.win.isVisible()){
                        this.win.show();
                    }else{
                        this.win.restore();
                    }
                },
                scope: this
            },{
                text: 'Minimize',
                handler: this.win.minimize,
                scope: this.win
            },{
                text: 'Maximize',
                handler: this.win.maximize,
                scope: this.win
            }, '-', {
                text: 'Close',
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