KebabOS.applications.aboutMe.application.views.Layout = Ext.extend(Ext.Panel, {
    layout: 'border',
    border:false,
    defaults: {
        border: false
    },
    initComponent: function() {
        
        Ext.apply(this, {
            items: new KebabOS.applications.aboutMe.application.views.MainForm({
                ownerApplication: this.ownerApplication,
                region: 'center'
            })
        });
        
        KebabOS.applications.aboutMe.application.views.Layout.superclass.initComponent.call(this);
    }
});
