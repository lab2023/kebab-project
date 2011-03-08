/**
 * ComplexEditorGridPanel extend by Ext.grid.EditorGridPanel 
 * 
 * @category    Kebab (kebab-reloaded)
 * @package     Kebab
 * @namespace   Kebab.library
 * @author      Tayfun Öziş ERİKAN <tayfun.ozis.erikan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/licensing
 */
Ext.namespace('Kebab.library.ext');
Kebab.library.ext.ComplexEditorGridPanel = Ext.extend(Ext.grid.EditorGridPanel, {
    
    initComponent : function() {
        
        // Base Config
        var config = {
            iconCls: 'icon-application-view-list',
            stateful: true,
            loadMask: true,
            stripeRows: true,
            trackMouseOver:true,
            columnLines: true,
            clicksToEdit: true,
            autoExpandColumn: 'title',
            viewConfig: {
                emptyText: 'Record not found...',
                forceFit: true
            }
        }
        
        // Merge initialConfig and baseConfig
        Ext.apply(this, config);
        
        // Grid Selectionmodel instance
        this.selectionModel = new Ext.grid.CheckboxSelectionModel({
            listeners: {
                selectionchange: function(sm) {
                    if (sm.getCount()) {
                        this.removeButton.enable();
                    } else {
                        this.removeButton.disable();
                    }
                },
                scope: this
            }
        });

        // Grid Column Model instance
        this.columnModel = new Ext.grid.ColumnModel({
            defaults: {
                sortable: true
            },
            columns: this.buildColumns()
        });
        
        // Grid selection model & column model and store
        this.sm = this.selectionModel
        this.store = this.dataStore;
        this.cm = this.columnModel;
        
        // Build Toolbars and Buttons.
        this.tbar = this.buildTbar();
        this.bbar = this.buildBbar();
        
        // Call Superclass initComponent() method
        Kebab.library.ext.ComplexEditorGridPanel.superclass.initComponent.call(this);
    },
    
    /**
     * grid Listeners
     */
    listeners: {
        beforeRender: function() {            
            if(this.extraTbarButtons) {
                this.buildExtraTbarButtons();
            }            
        },
        afterRender: function() {
            this.store.load({params:{start:0, limit:25}});
            this.batchButton.toggle();
        }
    },
    
    /**
     * build grid Columns
     */
    buildColumns: function() {        
        
        return [
            this.selectionModel,
            new Ext.grid.RowNumberer({header:'Series', width:40}),
            {header: "Identity", dataIndex: 'id', width:30},
        ];  
    },

    /**
     * build TopToolbar
     */
    buildTbar : function() {
        
        this.tbarButtons = [{
            xtype: 'buttongroup',
            title: 'İşlemler',
            defaults: {
                scale: 'small',
                iconAlign: 'top',
                width:50,                
                scope: this
            },
            items: [{
                text: 'Kaydet',
                tooltip: 'Değişiklikleri kaydedin',
                iconCls: 'icon-disk',
                ref: '../../saveButton',
                handler: this.onSave
            },{
                text: 'Ekle',
                tooltip: 'Yeni kayıt ekleyin',
                iconCls: 'icon-add',
                ref: '../../addButton',
                handler: this.onAdd
            },{
                text: 'Çıkar',
                tooltip: 'Seçili kayıt(ları) kaldırın',
                iconCls: 'icon-delete',
                ref: '../../removeButton',
                disabled: true,
                handler: this.onRemove
            },{
                text: 'Tazele',
                tooltip: 'Listeyi tazeleyin',
                iconCls: 'icon-arrow-refresh',
                handler: this.onRefresh
            }]
        },{
            xtype: 'buttongroup',
            title: 'Arama & Filtreleme',
            defaults: {
                iconAlign: 'top',
                scale: 'small',
                width:50,                
                scope: this,
                border:false
            },
            items: [{
                xtype: 'panel',
                padding:2,
                items: [{
                    xtype: 'textfield',
                    emptyText: 'Anahtar kelime...',
                    width:100
                }],
                width:102
            },{
                text: 'Ara',
                iconCls: 'icon-folder-explore'
            }]
        },{
            xtype: 'buttongroup',
            title: 'Dışarı Aktar',
            defaults: {
                scale: 'medium',
                iconAlign: 'top',
                scale: 'small',
                width:50,                
                scope: this
            },
            items: [{
                text: 'Excel Aktar',
                tooltip: 'Seçili excele aktarın',
                iconCls: 'icon-page-white-excel',
                ref: '../../excelExportButton'
            }]
        },{
            xtype: 'buttongroup',
            title: 'Seçenekler',
            defaults: {
                iconAlign: 'top',
                scale: 'small',
                width:60,                
                scope: this,
                enableToggle: true
            },
            items: [{
                text: 'Oto. kayıt',
                iconCls: 'icon-database-lightning',
                pressed: false,
                ref: '../../autoSaveButton',
                tooltip: 'Yaptığınız her değişikliğin otomatik olarak veya siz ' 
                       + 'istediğinizde kaydedilmesini sağlar. '
                       + 'Yoğun trafik gerektiren işlerde kullanılmaması önerilir.',
                toggleHandler: function(btn, pressed) {
                    this.store.autoSave = pressed;                
                }
            }, {
                text: 'Toplu işlem',
                iconCls: 'icon-database-table',
                pressed: false,
                ref: '../../batchButton',
                tooltip: 'Bu seçenek yanlızca değişiklik olan kayıtları veya '
                       + 'hepsini bir seferde sunucuya göndermenizi sağlar.',
                toggleHandler: function(btn, pressed) {
                    this.store.batch = pressed;
                }
            }]
        }];
        
        var buttons = [], i = 0;
        Ext.each(this.tbarButtons, function(button){
            buttons[i++] = button;
        });
        
        return buttons;
    },
    
    buildExtraTbarButtons: function() {
        
        if(this.extraTbarButtons) {            
            Ext.each(this.extraTbarButtons, function(button) {
                this.getTopToolbar().addButton(button);
            }, this);
        }
    },

    /**
     * build BottomToolbar
     */
    buildBbar : function() {
        return new Ext.PagingToolbar({
            pageSize: 25,
            store: this.store,
            displayInfo: true,
            plugins: new Ext.ux.SlidingPager()
        });
    },
    
    /**
     * onSave action
     */
    onSave : function(btn, ev) {
        
        // Confirmation
        Ext.Msg.show({
           icon: Ext.MessageBox.QUESTION,
           title: 'Değişiklikleri onaylıyor musunuz ?',
           msg: 'Yapmış olduğunuz değişiklikler sunucuya gönderilecektir.'
              + '<br/>Lütfen bu işlemi onaylayınız.',
           buttons: Ext.Msg.YESNO,
           fn: commitChanges,
           scope:this
        });

        // Check message box status and commit store changes to server.
        function commitChanges(btn) {
            if(btn == 'yes') {
                this.store.save();
            }
        }
    },

    /**
     * onAdd action
     */
    onAdd : function(btn, ev) {
        
        var record = new this.store.recordType(this.emptyRecord);
        this.stopEditing();
        this.store.insert(0, record);
        this.startEditing(0, 1);
    },

    /**
     * onDelete
     */
    onRemove : function(btn, ev) {
                
        var sm = this.getSelectionModel();
        
        if (!sm.getCount()) {
            return false;
        } else {            
            sm.each(function(selection) {
                this.store.remove(selection);
            }, this);             
        }
        
    },
    
    /**
     * onRefresh
     */
    onRefresh : function() {                
         this.store.reload();
    }
});