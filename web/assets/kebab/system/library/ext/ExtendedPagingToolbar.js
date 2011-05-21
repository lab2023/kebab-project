/**
 * ExtendedPagingToolbar extend by Ext.PagingToolbar
 * 
 * @category    Kebab (kebab-reloaded)
 * @package     Kebab
 * @namespace   Kebab.library
 * @author      Tayfun Öziş ERİKAN <tayfun.ozis.erikan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 */
Ext.namespace('Kebab.library.ext');
Kebab.library.ext.ExtendedPagingToolbar = Ext.extend(Ext.PagingToolbar, {

    constructor: function(){

        this.perPageCombo = new Ext.form.ComboBox({
            tpl:'<tpl for="."><div class="x-combo-list-item">{id}</div></tpl>',
            name : 'perpage',
            width: 50,
            store: new Ext.data.ArrayStore({
                fields: ['id'],
                data  : [['5'], ['15'],['25'],['50'],['150']]
            }),
            mode : 'local',
            value: '25',
            listWidth     : 50,
            triggerAction : 'all',
            displayField  : 'id',
            valueField    : 'id',
            editable      : false,
            forceSelection: true
        });
        
        
        this.perPageCombo.on('select', function(combo, record) {
            this.pageSize = parseInt(record.get('id'), 10);
            this.doLoad(this.cursor);
        }, this);
        
        // Base Config
        var config = {
            pageSize: 25,
            displayInfo: true,
            items :[
                '-','Show: ',
                this.perPageCombo
            ],
            plugins: new Ext.ux.SlidingPager()
        }

        // Merge initialConfig and baseConfig
        Ext.apply(this, config);

        Kebab.library.ext.ExtendedPagingToolbar.superclass.constructor.apply(this, arguments);
    }
});