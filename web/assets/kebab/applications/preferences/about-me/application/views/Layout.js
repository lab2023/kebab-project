Ext.namespace('KebabOS.applications.AboutMeApplication.views');

KebabOS.applications.AboutMeApplication.views.Layout = Ext.extend(Ext.Panel, {
    layout: 'border',
    border:false,
    defaults: {
        border: false
    },
    initComponent: function() {
        Ext.apply(this, {
            items: new KebabOS.applications.AboutMeApplication.library.MainForm({
                application: this.application,
                region: 'center'
            })
        });
        KebabOS.applications.AboutMeApplication.views.Layout.superclass.initComponent.call(this);
    }
});

