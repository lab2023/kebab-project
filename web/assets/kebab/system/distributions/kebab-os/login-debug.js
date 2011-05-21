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
/**
 * Login Singleton Object
 */
Login = function(){

    var loginForm = null,
        forgotPasswordForm = null;

    return {

        init: function(){

            // Call builders
            this.buildLoginForm();
            this.buildForgotPasswordForm();

            // Add events
            Ext.fly('forgotPassword-link').on('click', function(e) {
                e.stopEvent();
                this.showForgotPasswordForm();
            }, this);
            Ext.fly('forgotPasswordCancel-link').on('click', function(e) {
                e.stopEvent();
                this.showLoginForm();
            }, this)
        },

        // BUILDERS ----------------------------------------------------------------------------------------------------

        /**
         * Login form builder
         */
        buildLoginForm: function() {
            loginForm = new Ext.FormPanel({
                url: BASE_URL + '/kebab/session',
                frame:false,
                labelAlign:'top',
                width: 190,
                buttonAlign:'left',
                border:false,
                renderTo: 'login-form',
                bodyStyle: 'background:transparent;',
                defaults: {anchor: '100%'},
                defaultType: 'textfield',
                hideLabels: true,
                items: [{
                    emptyText: 'Your username',
                    name: 'userName',
                    allowBlank:false
                },{
                    emptyText: 'Your password',
                    name: 'password',
                    anchor: '78%',
                    inputType: 'password',
                    allowBlank:false
                }, {
                    xtype: 'checkboxgroup',
                    items: [{boxLabel: 'Remember me', name: 'rememberMe', checked: false}]
                }],
                buttons: [{
                    width:80,
                    iconCls: 'icon-accept',
                    text: 'Send',
                    handler: function() {
                        this.loginAction();
                    },
                    scope:this
                },{
                    xtype:'panel',
                    border:false,
                    bodyStyle: 'background:transparent;',
                    html: '<a href="#" id="forgotPassword-link">Forgot password</a>'
                }],
                keys:[{
                    key: [Ext.EventObject.ENTER], handler: function() {
                        this.loginAction();
                    }, scope:this
                }]
            });
        },

        /**
         * Forgot password form builder
         */
        buildForgotPasswordForm: function() {
            forgotPasswordForm = new Ext.FormPanel({
                url: Kebab.OS.generateUrl('kebab/forgot-password'),
                method: 'POST',
                frame:false,
                labelAlign:'top',
                width: 290,
                buttonAlign:'left',
                border:false,
                renderTo: 'forgotPassword-form',
                bodyStyle: 'background:transparent;',
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
                        this.forgotPasswordAction();
                    },
                    scope:this
                },{
                    xtype:'panel',
                    border:false,
                    bodyStyle: 'background:transparent;',
                    html: '<a href="#" id="forgotPasswordCancel-link">Cancel</a>'
                }],
                keys:[{
                    key: [Ext.EventObject.ENTER], handler: function() {
                        this.forgotPasswordAction();
                    }, scope:this
                }]
            });
        },

        // UTILS -------------------------------------------------------------------------------------------------------

        /**
         * Show the preloader
         */
        showPreLoader: function() {
            Ext.fly('loader').fadeIn();
            Ext.fly('login-form-wrapper').fadeOut();
            Ext.fly('forgotPassword-form-wrapper').fadeOut();
            Ext.fly('kebab-system-backend-login-container').scale(270,[65]);
        },

        /**
         * Show the login form
         */
        showLoginForm: function() {
            Ext.fly('loader').fadeOut();
            Ext.fly('login-form-wrapper').fadeIn();
            Ext.fly('forgotPassword-form-wrapper').fadeOut();
            Ext.fly('kebab-system-backend-login-container').scale([330],[220]);
        },

        /**
         * Show the forgot password form
         */
        showForgotPasswordForm: function() {
            Ext.fly('loader').fadeOut();
            Ext.fly('login-form-wrapper').fadeOut();
            Ext.fly('forgotPassword-form-wrapper').fadeIn();
            Ext.fly('kebab-system-backend-login-container').scale(400,[128]);
        },

        // ACTIONS -----------------------------------------------------------------------------------------------------

        /**
         * Login action
         */
        loginAction: function() {
            if (loginForm.getForm().isValid()) {
                this.showPreLoader();
                loginForm.getForm().submit({
                    success : function() {
                        Kebab.OS.redirect('backend/desktop');
                    },
                    failure : function() {
                        var msg = "Login failure. Please try again."
                        loginForm.getForm().reset();
                        loginForm.getForm().markInvalid({
                            username: msg, password: msg
                        });
                        this.showLoginForm();
                    },
                    scope:this
                });
            }
        },

        /**
         * Forgot password action
         */
        forgotPasswordAction: function() {
             if (forgotPasswordForm.getForm().isValid()) {
                this.showPreLoader();
                forgotPasswordForm.getForm().submit({
                    success : function() {
                        this.showLoginForm();
                    },
                    failure : function() {
                        this.showForgotPasswordForm();
                    },
                    scope:this
                });
            }
        }
    };
}();

Ext.onReady(Login.init, Login);
