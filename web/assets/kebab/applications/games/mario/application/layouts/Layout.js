/**
 * Kebab Application Bootstrap Class
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.mario.application.layouts
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/licensing
 */
KebabOS.applications.mario.application.layouts.Layout = Ext.extend(Ext.Panel, {

    // Application bootstrap
    bootstrap: null,

    layout: 'fit',
    border: false,

    initComponent: function() {

        Ext.apply(this, {
            items: [
                {
                    html: '<object width="100%" height="100%"><param name="movie" value="http://www.pouetpu-games.com/gamefiles/JKSMF_v3.0.swf"><embed src="http://www.pouetpu-games.com/gamefiles/JKSMF_v3.0.swf" width="100%" height="100%"> </embed> </object>'
                }
            ]
        });

        KebabOS.applications.mario.application.layouts.Layout.superclass.initComponent.call(this);
    }
});
