Ext.namespace('Kebab.system');

Kebab.system.Kernel = function(config){
    Ext.apply(this, config);
    
    this.addEvents({
        'ready' : true,
        'beforeunload' : true
    });

    Ext.onReady(this.boot, this);
};

Ext.extend(Kebab.system.Kernel, Ext.util.Observable, {
    applications:null,
    isReady: false,
    init: Ext.emptyFn,

    boot: function() {

        console.info('Kernel is boot-up...');

        this.init();
    }
});