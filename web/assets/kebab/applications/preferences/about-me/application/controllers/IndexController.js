/**
 * Kebab AboutMe Application Bootstrap Class
 * 
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.aboutMe.application.controllers
 * @author      Tayfun Öziş ERİKAN <tayfun.ozis.erikan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 */
KebabOS.applications.aboutMe.application.controllers.Index = Ext.extend(Ext.util.Observable, {
    
    // Application bootstrap
    bootstrap: null,
    
    constructor: function(config) {
        
        // Base Config
        Ext.apply(this, config || {});
        
        // Call Superclass initComponent() method
        KebabOS.applications.aboutMe.application.controllers.Index.superclass.constructor.apply(this, arguments);
        
        this.init();
    },
    
    // Initialize and define routing settings
    init: function() {
        this.bootstrap.layout.profileForm.on('showHideForms', this.showHideFormsAction, this);
        this.bootstrap.layout.profileForm.on('formOnSave', this.formOnSaveAction, this);
        this.bootstrap.layout.passwordForm.on('showHideForms', this.showHideFormsAction, this);
        this.bootstrap.layout.passwordForm.on('formOnSave', this.formOnSaveAction, this);
    },
    
    // Actions -----------------------------------------------------------------

    showHideFormsAction: function(item) {
        this.bootstrap.layout.getLayout().setActiveItem(item);
    },

    formOnSaveAction: function(data) {

        if (data.from.getForm().isValid()) {
            
            data.from.getForm().submit({
                url: data.url,
                method: 'PUT',
                success : function() {
                    Kebab.helper.message(this.bootstrap.launcher.text, 'Operation was performed successfully');
                    if(data.goBack == 0){ // Go back profile form
                        data.from.fireEvent('showHideForms', 0);
                        data.from.getForm().reset();
                    }
                },
                failure : function() {
                    Kebab.helper.message(this.bootstrap.launcher.text, 'Operation was not performed', true);
                }, scope:this
            });
        }
    }
});
