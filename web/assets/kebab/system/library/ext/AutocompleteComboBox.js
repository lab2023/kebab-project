/**
 * AutocompleteComboBox extend by Ext.form.ComboBox
 * 
 * @category    Kebab (kebab-reloaded)
 * @package     Kebab
 * @namespace   Kebab.library
 * @author      Tayfun Öziş ERİKAN <tayfun.ozis.erikan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 */
Ext.namespace('Kebab.library.ext');
Kebab.library.ext.AutocompleteComboBox = Ext.extend(Ext.form.ComboBox, {

    constructor: function(){

        // Base Config
        var config = {
            tpl:'<tpl for="."><div ext:qtip="{description}" class="x-combo-list-item">{title}</div></tpl>',
            name : 'record_id',
            mode : 'remote',
            emptyText: 'Please type any keyword here...',
            displayField : 'title',
            valueField : 'id',
            hiddenName : 'record_id',
            triggerAction: 'all',
            listEmptyText : 'Record not found...',
            lazyRender: false,
            selectOnFocus: true,
            minListWidth: 230,
            forceSelection: true,
            minChars: 3,
            pageSize: 25
        }

        // Merge initialConfig and baseConfig
        Ext.apply(this, config);

        Kebab.library.ext.AutocompleteComboBox.superclass.constructor.apply(this, arguments);
    }
});