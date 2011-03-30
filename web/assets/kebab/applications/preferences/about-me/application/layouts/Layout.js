/**
 * Kebab Application Bootstrap Class
 * 
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.aboutMe.application.layouts
 * @author      Tayfun Öziş ERİKAN <tayfun.ozis.erikan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/licensing
 */
KebabOS.applications.aboutMe.application.layouts.Layout = Ext.extend(Ext.Panel, {
    
    // Application bootstrap
    bootstrap: null,
    
    layout: 'border',
    disabled:true,
    border: false,
    
    initComponent: function() {

        this.mainForm = new KebabOS.applications.aboutMe.application.views.MainForm({
            bootstrap: this.bootstrap,
            region: 'center'
        });


        this.passwordForm = new KebabOS.applications.aboutMe.application.views.PasswordForm({
            bootstrap: this.bootstrap,
            region:'south',
            collapsible:true,
            collapseMode:'mini',
            collapsed:true,
            border:true,
            height:360
        });


        Ext.apply(this, {
            items: [this.mainForm,this.passwordForm]
        });

        KebabOS.applications.aboutMe.application.layouts.Layout.superclass.initComponent.call(this);
    }
});