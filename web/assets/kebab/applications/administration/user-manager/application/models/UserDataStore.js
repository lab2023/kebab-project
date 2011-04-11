/**
 * userManager Application UserDataStore
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.userManager.application.models
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/licensing
 */
KebabOS.applications.userManager.application.models.UserDataStore = Ext.extend(Kebab.library.ext.RESTfulBasicDataStore, {

    // Application Bootstrap
    bootstrap: null,

    // System REST API
    restAPI: BASE_URL + '/user/manager',

    readerFields: [
        {name: 'id', type:'integer'},
        {name: 'firstName', type:'string'},
        {name: 'lastName', type:'string'},
        {name: 'email', type:'string'},
        {name:'username', type:'string'},
        {name: 'Roles', type:'string'},
        {name: 'status', type:'string'}
    ]
});