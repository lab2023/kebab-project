Ext.ns('Ext.ux');

/**
 * This simple plugin will brings to webkit speech feature is activated.
 *
 * @author      Tayfun Öziş ERİKAN <tayfun.ozis.erikan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 * @class Ext.ux.FieldSpeecher
 *
 * <p>Usage:</p>
 * <pre><code>
    {
        xtype: 'textfield',
        plugins: [ Ext.ux.FieldSpeecher ],
        listeners: {
            speech: function(field, value) {
                // todo...
            }
        }
    }
 * </code></pre>
 */
Ext.ux.FieldSpeecher = {

    init: function(f) {
        if (Ext.isWebKit) {

            f.addEvents('speech');

            f.on('render', function() {

                f.getEl().set({
                    'x-webkit-speech': true,
                    'speech': true
                });

                f.getEl().on('webkitspeechchange', function() {
                    f.fireEvent('speech', f, f.getValue());
                });
            });
        }
    }
};