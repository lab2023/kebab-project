/**
 * RESTfulDataStore extend by Ext.data.Store 
 * 
 * @category    Kebab (kebab-reloaded)
 * @package     Kebab
 * @namespace   Kebab.library
 * @author      Tayfun Öziş ERİKAN <tayfun.ozis.erikan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/licensing
 */
Ext.namespace('Kebab.library.ext');
Kebab.library.ext.RESTfulBasicDataStore = Ext.extend(Ext.data.Store, {
    
    // Store ID
    id: 'your_entity',
    
    // RESTful enable
    restful: true,    
    
    // Autoload enable
    autoLoad: false,
    
    // Remote sort enable
    remoteSort: true,
    
    // System REST API
    restAPI: 'api/url',
    
    // Json Reader Config
    readerConfig: {            
        idProperty: 'id',
        root: 'data',
        totalProperty: 'total',
        messageProperty: 'notifications'
    },
    
    readerFields: [
        {name: 'id', type: 'int'},
        {name: 'title', type: 'string', allowBlank: false},
        {name: 'description', type: 'string'}
    ],
    
    // Store Constructor
    constructor : function() {
        
        // HTTP Proxy
        this.proxy = new Ext.data.HttpProxy({
            url : this.restAPI
        });
        
        // JSON Reader
        this.reader = new Ext.data.JsonReader(
            this.readerConfig, 
            this.buildReaderFields()
        );
        
        // Base Config
        var config = {
            proxy: this.proxy,
            reader: this.reader
        }
        
        // Merge initialConfig and base config
        Ext.apply(this, config);
        
        // Call Superclass initComponent() method
        Kebab.library.ext.RESTfulBasicDataStore.superclass.constructor.apply(this, arguments);
    },
    
    buildReaderFields: function() {        
        return this.readerFields;
    },
    
    listeners : {
        exception : function(){
            var notification = new Kebab.OS.Notification();
            notification.message('Exception title', 'Your message here');
        }
    }
});