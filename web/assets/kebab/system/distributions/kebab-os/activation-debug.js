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
 * Activation Singleton Object
 */
Activation = function(){

    var activationAPI = null,
        activationForm = null,
        activationKey = 0;

    return {

        init: function(){

            // API setup
            activationAPI = Kebab.helper.url('kebab/user-activation');

            // Call builders
            this.buildActivationForm();
            
            Ext.fly('login-link').on('click', function(e) {
                e.stopEvent();
                Kebab.helper.redirect('backend');
            }, this)
        },

        // BUILDERS ----------------------------------------------------------------------------------------------------

        /**
         * Login form builder
         */
        buildActivationForm: function() {

            activationForm = new Ext.FormPanel({
                renderTo: 'activation-form',
                bodyCssClass: 'kebab-transparent',
                url: activationAPI,
                frame:false,
                labelAlign:'top',
                width: '100%',
                buttonAlign:'left',
                waitMsgTarget: true,
                border:false,
                defaultType: 'textfield',
                hideLabels: true,
                items: [{
                    name: 'activationKey',
                    hidden: true
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
                    readOnly: true,
                    vtype: 'email',
                    anchor:'100%'
                },{
                    xtype:'fieldset',
                    title: 'Your user name & password',
                    collapsible: false,
                    autoHeight:true,
                    hideLabels: true,
                    defaults: {
                        xtype: 'textfield',
                        allowBlank: false,
                        anchor:'100%'
                    },
                    items :[{
                        fieldLabel: 'User name',
                        emptyText: 'User name',
                        name: 'userName'
                    },{
                        xtype: 'compositefield',
                        hideLabels: true,
                        defaults: {
                            xtype: 'textfield',
                            flex: 1,
                            inputType: 'password'
                        },
                        items: [{
                            fieldLabel: 'Password',
                            emptyText: 'Password',
                            name: 'password',
                            id: 'activation-password'
                        },{
                            fieldLabel: 'Confirm password',
                            emptyText: 'Confirm password',
                            name: 'confirmPassword',
                            vtype: 'password',
                            initialPassField: 'activation-password'
                        }]
                    }]
                }],
                buttons: [{
                    width:80,
                    iconCls: 'icon-accept',
                    text: 'Send',
                    handler: function() {
                        this.activationAction();
                    },
                    scope:this
                },{
                    xtype:'panel',
                    border:false,
                    bodyCssClass: 'kebab-transparent',
                    html: '<a href="#" id="login-link">Login</a>'
                }],
                keys: [{
                    key: [Ext.EventObject.ENTER], handler: function() {
                        this.activationAction();
                    }, scope:this
                }],
                listeners: {
                    beforeRender: function() {
                        this.setActivationKey();
                    },
                    afterRender: function(form) {

                        if (this.getActivationKey() == 0) {
                            this.disableFormItems(form, form.items);
                        }

                        Ext.apply(Ext.form.VTypes, {
                            password : function(val, field) {
                                if (field.initialPassField) {
                                    var pwd = Ext.getCmp(field.initialPassField);
                                    return (val == pwd.getValue());
                                }
                                return true;
                            },
                            passwordText : 'Passwords do not match!'
                        });
                        this.checkActivationKeyAction(form);
                    },
                    scope: this
                }
            });
        },
        
        // UTILS -------------------------------------------------------------------------------------------------------

        /**
         * Disable all form items
         * @param items
         */
        disableFormItems: function(form, items) {
            items.each(function(item){
                if (item.items) {
                    this.disableFormItems(form, item.items);
                } else {
                    item.markInvalid();
                    item.disable();
                }
            }, this);

            form.buttons[0].setIconClass('icon-cancel');
            form.buttons[0].setText('Invalid activation key !&nbsp;');
            form.buttons[0].disable();
        },
                
        getActivationKey: function() {
            return activationKey;
        },

        setActivationKey: function(key) {
            activationKey = Ext.get('activation-key').getValue();
        },

        /**
         * Show the pre-loader
         */
        showPreLoader: function() {
            Ext.fly('kebab-progress-bar').fadeIn();
            Ext.fly('activation-form-wrapper').fadeOut();
            Ext.fly('kebab-system-backend-activation-container').scale(270,[65]);
        },

        /**
         * Show the sign-up form
         */
        showActivationForm: function() {
            Ext.fly('kebab-progress-bar').fadeOut();
            Ext.fly('activation-form-wrapper').fadeIn();
            Ext.fly('kebab-system-backend-activation-container').scale([300],[385]);
        },

        // ACTIONS -----------------------------------------------------------------------------------------------------

        /**
         * Check the user activation key
         */
        checkActivationKeyAction: function(form) {
            this.showPreLoader();
            form.getForm().load({
                method: 'GET',
                url: activationAPI + '/key/' + this.getActivationKey(),
                success : function() {
                    console.log(arguments);
                    this.showActivationForm();
                },
                failure : function() {
                    this.showActivationForm();
                    this.disableFormItems(form, form.items);
                },
                scope:this
            });
        },
        
        /**
         * Activation action
         */
        activationAction: function() {
            if (activationForm.getForm().isValid()) {
                this.showPreLoader();
                activationForm.getForm().submit({
                    method: 'POST',
                    success : function() {
                        Kebab.helper.redirect('backend/desktop');
                    },
                    failure : function() {
                        this.showActivationForm();
                    },
                    scope:this
                });
            }
        }
    };
}();

Ext.onReady(Activation.init, Activation);
