/**
 * roleManager Application RoleStoryGrid class
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.roleManager.application.views
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/licensing
 */
KebabOS.applications.roleManager.application.views.RoleStoryGrid = Ext.extend(Kebab.library.ext.ComplexEditorGridPanel, {

    // Application bootstrap
    bootstrap: null,

    initComponent: function() {

        // json data store
        this.dataStore = new KebabOS.applications.roleManager.application.models.RoleStoryDataStore({
            bootstrap:this.bootstrap
        });

        this.expander = new Ext.ux.grid.RowExpander({
            tpl : new Ext.Template(
                    '<p><b>Description:</b><br />{description}</p><br>'
                    )
        });

        // grid config
        this.config.plugins = this.expander;

        KebabOS.applications.roleManager.application.views.RoleStoryGrid.superclass.initComponent.apply(this, arguments);
    },

    listeners: {
        afterRender: function() {
            this.store.load({params:{start:0, limit:25}});
            this.onDisableButtonGroup('export');
            this.batchButton.toggle();
            this.getView().fitColumns();
        }
    },

    buildColumns: function() {
        var roleStoydataStore = new KebabOS.applications.roleManager.application.models.RoleStoryDataStore({
            bootstrap:this.bootstrap
        });
        var editorComboBox = new Kebab.library.ext.AutocompleteComboBox({
            typeAhead: true,
            triggerAction: 'all',
            forceSelection: true,
            lazyRender:false,
            mode: 'remote',
            store:roleStoydataStore,
            valueField: 'id',
            displayField: 'title',
            hiddenName: 'symptom',
            scope:this,
            gridRenderer : function(combo) {
                return function(value, meta, record) {
                    var comboRecord = combo.findRecord(combo.valueField, value);
                    try {
                        var displayValue = record.data.title;
                        return comboRecord ?
                                comboRecord.get(combo.displayField) : displayValue
                    } catch (exception) {
                        combo.setValue(value);
                        return combo.getRawValue();
                    }
                }
            }
        });

        return [
            this.expander,
            new Ext.grid.RowNumberer({header:'No', width:50}),
            {
                header : 'Story Title',
                dataIndex :'title'
            },{
                header   : 'Allow ?',
                dataIndex: 'allow',
                xtype:'checkcolumn',
                width:10
            }
        ]
    }
});

