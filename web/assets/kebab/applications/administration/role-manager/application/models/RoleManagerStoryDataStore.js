/**
 * roleManager Application RoleManagerStoryDataStore class
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.roleManager.application.models
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @author      Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 */
KebabOS.applications.roleManager.application.models.RoleManagerStoryDataStore = Ext.extend(Kebab.library.ext.RESTfulBasicDataStore, {

    bootstrap: null,

    restAPI: BASE_URL + '/kebab/role-story',

    readerFields:[
        {name: 'id', type:'integer'},
        {name: 'Permission',type:'object'},
        {name: 'title', type:'string'},
        {name: 'description', type:'string'}
    ]
});