/**
 * Kebab Application Bootstrap Class
 * 
 * @category    Kebab
 * @package     Applications
 * @namespace   KebabOS.applications.aboutMe.application.layouts
 * @author      Tayfun Öziş ERİKAN <tayfun.ozis.erikan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 */
KebabOS.applications.aboutMe.application.layouts.Layout = Ext.extend(Ext.Panel, {
    
    // Application bootstrap
    bootstrap: null,
    
    layout: 'card',
    activeItem: 0,
    border: false,
    
    initComponent: function() {

        this.profileForm = new KebabOS.applications.aboutMe.application.views.ProfileForm({
            bootstrap: this.bootstrap
        });

        this.passwordForm = new KebabOS.applications.aboutMe.application.views.PasswordForm({
            bootstrap: this.bootstrap
        });

        Ext.apply(this, {
            items: [this.profileForm,this.passwordForm]
        });

        KebabOS.applications.aboutMe.application.layouts.Layout.superclass.initComponent.call(this);
    }
});