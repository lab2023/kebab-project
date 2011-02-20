Ext.override(Ext.Window, {
    defaultAnimShowCfg: {
        duration: .25,
        easing: 'easeNone',
        opacity: .5
    },
    defaultAnimHideCfg: {
        duration: .25,
        easing: 'easeNone',
        opacity: 0
    },
    animShow : function(){
        this.proxy.show();
        this.proxy.setBox(this.animateTarget.getBox());
        this.proxy.setOpacity(0);
        var b = this.getBox(false);
        b.callback = this.afterShow;
        b.scope = this;
        b.block = true;
        Ext.apply(b, this.animShowCfg, this.defaultAnimShowCfg);
        this.el.setStyle('display', 'none');
        this.proxy.shift(b);
    },
    animHide : function(){
        this.proxy.setOpacity(.5);
        this.proxy.show();
        var tb = this.getBox(false);
        this.proxy.setBox(tb);
        this.el.hide();
        var b = this.animateTarget.getBox();
        b.callback = this.afterHide;
        b.scope = this;
        b.block = true;
        Ext.apply(b, this.animHideCfg, this.defaultAnimHideCfg);
        this.proxy.shift(b);
    }
});