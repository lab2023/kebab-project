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

Kebab.OS.Notification = Ext.extend(Ext.util.Observable, {
    
    SUCCESS: "Transaction successful",
    FAIL: "Transaction failed",
    
    messageContainer: null,
    
    constructor: function(config) { 
        
        Ext.apply(this, config);
        
        Kebab.OS.Notification.superclass.constructor.call(this, arguments);
    },
    
    message : function(title, message, keep){
        
        var messageBody = function(t, s){
            return ['<div class="kebab-notifications kebab-shadow-std kebab-rounded-corners">',
            '<p><h3>', t, '</h3>', s, '</p>',
            '</div>'].join('');
        }

        this.messageContainer = Ext.DomHelper.insertFirst('kebab-os-desktop', {
            cls:'kebab-notifications-body'
        }, true);

        this.messageContainer.alignTo('kebab-os-desktop', 'tr-tr');
        
        var message = Ext.DomHelper.append(this.messageContainer, {
            html: messageBody(title, message)
        }, true);

        if (!keep) {
            message.slideIn('t', {easing: 'backOut', duration: .0}).pause(2).ghost("b", {
                remove:true,
                easing: 'easeIn',
                duration: .15,
                callback: function() {
                    this.remove();
                    this.messageContainer.remove();
                },
                scope:this
            });
        } else {
            message.slideIn('t', {easing: 'backOut', duration: .0});
            message.on('click', function() {
                message.ghost("b", {
                    remove: true,
                    easing: 'easeIn',
                    duration: .15,
                    callback: function() {
                        this.remove();
                        this.messageContainer.remove();
                    },
                    scope:this
                });
            });
        }
    },
    
    dialog: function(title, message, type) {
        
        var icon = null;
        
        switch (type) {
            case 'WARNING':
                icon = Ext.MessageBox.WARNING
                break;
            case 'ERROR':
                icon = Ext.MessageBox.ERROR
                break;
            default:
                icon = Ext.MessageBox.INFO
                break;
        }
        
        Ext.Msg.show({
            modal:true,
            title: title,
            msg: message,
            icon: icon,
            buttons: Ext.Msg.OK
        });
    },
    
    remove: function() {
        // TODO remove after finish...
        //this.messageContainer.remove();
        //console.log(this.messageContainer.remove());
    }
});