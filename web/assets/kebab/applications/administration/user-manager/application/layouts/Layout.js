/**
 * Kebab Application Bootstrap Class
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.userManager.application.layouts
 * @author      Yunus ÖZCAN <yuns.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/licensing
 */
KebabOS.applications.userManager.application.layouts.Layout = Ext.extend(Ext.Panel, {

    // Application bootstrap
    bootstrap: null,

    initComponent: function() {

        // create the data store
        var store = new Ext.data.ArrayStore({
            fields: [
                {name: 'name'},
                {name: 'lastName'},
                {name: 'userName'},
                {name: 'email'},
                {name: 'role'}
            ]
        });

        store.loadData([
            ['Yunus', 'ÖZCAN', 'youzncuasn', 'yunus.ozcan@lab2023.com', ['admin', 'guest', 'owner']],
            ['Yunus', 'ÖZCAN', 'youzncuasn', 'yunus.ozcan@lab2023.com', ['admin', 'guest', 'owner']],
            ['Yunus', 'ÖZCAN', 'youzncuasn', 'yunus.ozcan@lab2023.com', ['admin', 'guest', 'owner']],
            ['Yunus', 'ÖZCAN', 'youzncuasn', 'yunus.ozcan@lab2023.com', ['admin', 'guest', 'owner']],
            ['Yunus', 'ÖZCAN', 'youzncuasn', 'yunus.ozcan@lab2023.com', ['admin', 'guest', 'owner']],
            ['Yunus', 'ÖZCAN', 'youzncuasn', 'yunus.ozcan@lab2023.com', ['admin', 'guest', 'owner']],
            ['Yunus', 'ÖZCAN', 'youzncuasn', 'yunus.ozcan@lab2023.com', ['admin', 'guest', 'owner']],
            ['Yunus', 'ÖZCAN', 'youzncuasn', 'yunus.ozcan@lab2023.com', ['admin', 'guest', 'owner']],
            ['Yunus', 'ÖZCAN', 'youzncuasn', 'yunus.ozcan@lab2023.com', ['admin', 'guest', 'owner']]
        ]);

        // Template
        var tpl = new Ext.XTemplate(
                '<tpl for=".">',
                '<div class="userManager-application-user-information">',
                '<div class="userManager-application-user-photo" "></div>',
                '<div class="userManager-application-user-info-text">',
                '<div>{name} {lastName}</div>',
                '<div><span>{userName}</span><span>{email}</span></div>',
                '<tpl for="role"> <span>{.}, </span> </tpl>',
                '</div>',
                '</div>',
                '</tpl>'
                );

        var dataview = new Ext.DataView({
            store        : store,
            tpl          : tpl,
            itemSelector : 'div'

        });

        var config = {
            items : dataview,
            tbar :[
                {
                    text: 'INVITE',
                    iconCls:'icon-email'
                }
            ]
        }


        Ext.apply(this, config);

        KebabOS.applications.userManager.application.layouts.Layout.superclass.initComponent.call(this);
    }
});
