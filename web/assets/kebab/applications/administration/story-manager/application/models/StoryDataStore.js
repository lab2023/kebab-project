/**
 * storyManager Application StoryDataStore class
 *
 * @category    Kebab
 * @package     Applications
 * @namespace   KebabOS.applications.storyManager.application.models
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 */
KebabOS.applications.storyManager.application.models.StoryDataStore = Ext.extend(Kebab.library.ext.RESTfulDataStore, {

    bootstrap: null,

    restAPI: 'kebab/story',

    readerFields:[
        {name: 'id', type:'integer'},
        {name:'title', type:'string'},
        {name: 'description', type:'string'},
        {name: 'active', type:'boolean'}
    ]
});