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
Ext.onReady(function() {
    Ext.QuickTips.init();

    Ext.get('kebab-loading-mask').fadeOut({remove: true, duration:1});

    var loginForm = new Ext.FormPanel({
        url: BASE_URL + '/authentication/session',
        frame:false,
        labelAlign:'top',
        width: 190,
        buttonAlign:'left',
        border:false,
        renderTo: 'login-form',
        bodyStyle: 'background:transparent !important',
        defaults: {anchor: '100%'},
        defaultType: 'textfield',
        hideLabels: true,
        items: [{
            emptyText: 'Your username',
            name: 'username',
            allowBlank:false
        },{
            emptyText: 'Your password',
            name: 'password',
            anchor: '78%',
            inputType: 'password',
            allowBlank:false
        }, {
            xtype: 'checkboxgroup',
            items: [{boxLabel: 'Remember me on this computer', name: 'rememberMe', checked: false}]
        }],
        buttons: [{
            width:80,
            iconCls: 'icon-accept',
            text: 'Send',
            handler: function() {
                loginAction();
            }
        },{
            xtype:'panel',
            border:false,
            bodyStyle: 'background:transparent !important;',
            html: '<a href="#" id="forgotPassword-link">Forgot password</a>'
        }]
    });

    var forgotPasswordForm = new Ext.FormPanel({
        url:'/authentication/forgot-password',
        method: 'POST',
        frame:false,
        labelAlign:'top',
        width: 290,
        buttonAlign:'left',
        border:false,
        renderTo: 'forgotPassword-form',
        bodyStyle: 'background:transparent !important',
        defaults: {anchor: '100%'},
        defaultType: 'textfield',
        hideLabels: true,
        items: [{
            emptyText: 'Your e-mail address',
            name: 'email',
            allowBlank:false,
            vtype: 'email'
        }],
        buttons: [{
            width:80,
            iconCls: 'icon-accept',
            text: 'Send',
            handler: function() {
                forgotPasswordAction();
            }
        },{
            xtype:'panel',
            border:false,
            bodyStyle: 'background:transparent !important;',
            html: '<a href="#" id="forgotPasswordCancel-link">Cancel</a>'
        }]
    });

    var showLoginForm = function() {
        Ext.fly('loader').fadeOut();
        Ext.fly('login-form-wrapper').fadeIn();
        Ext.fly('forgotPassword-form-wrapper').fadeOut();
        Ext.fly('kebab-system-backend-login-container').scale([330],[220]);
    }
    var loginAction = function() {
        if (loginForm.getForm().isValid()) {
            showLoader();
            loginForm.getForm().submit({
                success : function() {
                    window.location.href = '/backend/desktop';
                },
                failure : function() {
                    var msg = "Login failure. Please try again."
                    loginForm.getForm().reset();
                    loginForm.getForm().markInvalid({
                        username: msg, password: msg
                    });
                    showLoginForm();
                }
            });
        }
    }

    var showLoader = function() {
        Ext.fly('loader').fadeIn();
        Ext.fly('login-form-wrapper').fadeOut();
        Ext.fly('forgotPassword-form-wrapper').fadeOut();
        Ext.fly('kebab-system-backend-login-container').scale(270,[65]);
    }
    var showForgotPasswordForm = function() {
        Ext.fly('loader').fadeOut();
        Ext.fly('login-form-wrapper').fadeOut();
        Ext.fly('forgotPassword-form-wrapper').fadeIn();
        Ext.fly('kebab-system-backend-login-container').scale(400,[128]);
    }
    var forgotPasswordAction = function() {
         if (forgotPasswordForm.getForm().isValid()) {
            showLoader();
            forgotPasswordForm.getForm().submit({
                success : function() {
                    showLoginForm();
                },
                failure : function() {
                    showForgotPasswordForm();
                }
            });
        }
    }

    Ext.fly('forgotPassword-link').on('click', function(e) {
        e.stopEvent();
        showForgotPasswordForm();
    });
    Ext.fly('forgotPasswordCancel-link').on('click', function(e) {
        e.stopEvent();
        showLoginForm();
    });
});
