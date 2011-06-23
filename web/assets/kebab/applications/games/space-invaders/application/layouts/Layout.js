/**
 * Kebab Application Bootstrap Class
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.spaceInvaders.application.layouts
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 */
KebabOS.applications.spaceInvaders.application.layouts.Layout = Ext.extend(Ext.Panel, {

    // Application bootstrap
    bootstrap: null,

    layout: 'fit',
    border: false,

    initComponent: function() {

        Ext.apply(this, {
            items: [
                {
                    html: '<object width="100%" height="100%"><param name="wmode" value="opaque"><param name="movie" value="http://info.al/lojra/Neave_Invaders.swf"><embed src="http://info.al/lojra/Neave_Invaders.swf" width="100%" height="100%"> </embed> </object>'
                }
            ]
        });

        KebabOS.applications.spaceInvaders.application.layouts.Layout.superclass.initComponent.call(this);
    }
});
