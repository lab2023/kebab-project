/**
 * roleManager Application RoleManagerDataStore class
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.roleManager.application.models
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/licensing
 */
KebabOS.applications.roleManager.application.models.RoleManagerDataStore = Ext.extend(Kebab.library.ext.RESTfulDataStore, {

    bootstrap: null,
    autoSave: true,

    restAPI: BASE_URL + '/role/manager',

    readerFields:[
        {name: 'id', type:'integer'},
        {name: 'title', type:'string'},
        {name: 'description', type:'string'},
        {name: 'active', type:'boolean'}
    ]
});