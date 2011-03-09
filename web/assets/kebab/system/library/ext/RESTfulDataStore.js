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
Kebab.library.ext.RESTfulDataStore = Ext.extend(Ext.data.Store, {
    
    // RESTful enable
    restful: true,    
    
    // Autoload enable
    autoLoad: false,
    
    // Autodestroy enable
    autoDestroy: true,
    
    // Remote sort enable
    remoteSort: true,
    
    // Autosave default disable
    autoSave: false,
    
    // Batch editing default enable
    batch: false,
    
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
        
        // JSON Writer
        this.writer = new Ext.data.JsonWriter({
            encode: true,
            writeAllFields: false
        });
        
        // Base Config
        var config = {
            proxy: this.proxy,
            reader: this.reader,
            writer: this.writer
        }
        
        // Merge initialConfig and base config
        Ext.apply(this, config);
        
        // Call Superclass initComponent() method
        Kebab.library.ext.RESTfulDataStore.superclass.constructor.apply(this, arguments);
    },
    
    buildReaderFields: function() {        
        return this.readerFields;
    },
    
    listeners : {
        write : function(){
            var notification = new Kebab.OS.Notification();
            notification.message('Write title', 'Your message here');
            this.reload();
        },
        exception : function(){
            var notification = new Kebab.OS.Notification();
            notification.message('Exception title', 'Your message here');
        }
    }
});