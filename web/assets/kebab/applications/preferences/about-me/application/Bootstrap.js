/**
 * AboutMe Application
 * @namespace KebabOS.applications.aboutMe
 */
Ext.extend(KebabOS.applications.aboutMe.application.Bootstrap, Kebab.OS.Application, {
    
    createApplication : function(){
        
        var desktop = this.app.getDesktop();
        var app = desktop.getApplication(this.id);
        
        if(!app){
            
            this.layout = new KebabOS.applications.aboutMe.application.views.Layout({
                ownerApplication: this
            });
            
            app = desktop.createApplication({
                id: this.id,
                title: this.launcher.text,                
                iconCls: 'aboutMe-application-gui-icon',
                width: 500,
                height: 400,
                resizable: false,
                maximizable: false,
                border: false,
                items: this.layout
            });
            
        }
        app.show();
    }
});


