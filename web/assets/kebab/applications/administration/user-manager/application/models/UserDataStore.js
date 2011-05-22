/**
 * userManager Application UserDataStore class
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.userManager.application.models
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @author      Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 */
KebabOS.applications.userManager.application.models.UserDataStore = Ext.extend(Kebab.library.ext.RESTfulDataStore, {

    bootstrap: null,

    restAPI: Kebab.OS.generateUrl('kebab/user'),

    readerFields:[
        {name: 'id', type:'integer'},
        {name: 'fullName', type:'string'},
        {name: 'userName', type:'string'},
        {name: 'email', type:'string'},
        {name: 'status', type:'string'},
        {name: 'active', type:'boolean'}
    ]
});