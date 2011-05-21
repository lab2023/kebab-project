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
    * http://www.kebab-project.com/cms/licensing
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
    * http://www.kebab-project.com/cms/licensing
    * If you did not receive a copy of the license and are unable to
    * obtain it through the world-wide-web, please send an email
    * to info@lab2023.com so we can send you a copy immediately.
----------------------------------------------------------------------------- */
/**
 * Sign-up Singleton Object
 */
SignUp = function(){

    var signUpAPI = Kebab.OS.generateUrl('kebab/user-sign-up'),
        signUpForm = null;

    return {

        init: function(){

            // Call builders
            this.buildSignUpForm();
            
            Ext.fly('login-link').on('click', function(e) {
                e.stopEvent();
                Kebab.OS.redirect('backend');
            }, this)
        },

        // BUILDERS ----------------------------------------------------------------------------------------------------

        /**
         * Login form builder
         */
        buildSignUpForm: function() {
            signUpForm = new Ext.FormPanel({
                renderTo: 'signUp-form',
                bodyCssClass: 'kebab-transparent',
                url: signUpAPI,
                frame:false,
                labelAlign:'top',
                width: '100%',
                buttonAlign:'left',
                border:false,
                defaultType: 'textfield',
                hideLabels: true,
                items: [{
                    name: 'type',
                    hidden:true,
                    value: 'signUp'
                },{
                    fieldLabel: 'Your full name',
                    emptyText: 'Your full name',
                    name: 'fullName',
                    allowBlank:false,
                    anchor:'100%'
                },{
                    fieldLabel: 'Your e-mail address',
                    emptyText: 'Your e-mail address',
                    name: 'email',
                    allowBlank:false,
                    vtype: 'email',
                    anchor:'100%'
                }],
                buttons: [{
                    width:80,
                    iconCls: 'icon-accept',
                    text: 'Send',
                    handler: function() {
                        this.signUpAction();
                    },
                    scope:this
                },{
                    xtype:'panel',
                    border:false,
                    bodyCssClass: 'kebab-transparent',
                    html: '<a href="#" id="login-link">Login</a>'
                }],
                keys:[{
                    key: [Ext.EventObject.ENTER], handler: function() {
                        this.signUpAction();
                    }, scope:this
                }]
            });
        },
        
        // UTILS -------------------------------------------------------------------------------------------------------

        /**
         * Show the pre-loader
         */
        showPreLoader: function() {
            Ext.fly('kebab-progress-bar').fadeIn();
            Ext.fly('signUp-form-wrapper').fadeOut();
            Ext.fly('kebab-system-backend-signUp-container').scale(270,[65]);
        },

        /**
         * Show the sign-up form
         */
        showSignUpForm: function() {
            Ext.fly('kebab-progress-bar').fadeOut();
            Ext.fly('signUp-form-wrapper').fadeIn();
            Ext.fly('kebab-system-backend-signUp-container').scale([330],[175]);
        },

        // ACTIONS -----------------------------------------------------------------------------------------------------

        /**
         * Sign-up action
         */
        signUpAction: function() {
            if (signUpForm.getForm().isValid()) {
                this.showPreLoader();
                signUpForm.getForm().submit({
                    success : function() {
                        Kebab.OS.redirect('backend');
                    },
                    failure : function() {
                        this.showSignUpForm();
                    },
                    scope:this
                });
            }
        }
    };
}();

Ext.onReady(SignUp.init, SignUp);
