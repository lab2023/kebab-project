/**
 * userManager Application EmailWindow class
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.userManager.application.view
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/licensing
 */
KebabOS.applications.userManager.application.views.EmailWindow = Ext.extend(Ext.Window, {

    // Application Bootstrap
    bootstrap: null,
    layout:'fit',
    initComponent: function() {

        this.emailForm = new KebabOS.applications.userManager.application.views.EmailForm({
            bootstrap: this.bootstrap
        });

        var config = {
            width:300,
            height:140,
            title:'InviteUser',
            iconCls: 'userManager-application-gui-icon',
            border:false,
            resizable: false,
            maximizable: false,
            closeAction: 'hide',
            manager: this.bootstrap.app.getDesktop().getManager(),
            modal:true,
            items:[this.emailForm]
        }

        Ext.apply(this, config);

        KebabOS.applications.userManager.application.views.EmailWindow.superclass.initComponent.apply(this, arguments);
    },

    showWindow: function(user) {
        if (user.status == 'active') {
            var title = ' reset password';
            var requestUrl = '/user/managera';
        }
        if (user.status == 'passive') {
            var title = ' re invite';
            var requestUrl = '/user/managerb';
        }
        this.setTitle(user.firstName + " " + user.lastName + title);
        this.emailForm.setUrl(BASE_URL + requestUrl);
        this.show();
    }
});