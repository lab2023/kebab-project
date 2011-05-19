/**
 * Feedback Application FeedbackDataStore
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.feedback.application.models
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/licensing
 */
KebabOS.applications.feedback.application.models.FeedbackDataStore = Ext.extend(Kebab.library.ext.RESTfulBasicDataStore, {

    // Application Bootstrap
    bootstrap: null,

    // Store ID
    id: 'feedbacks',

    // System REST API
    restAPI: BASE_URL + '/kebab/feedback',

    readerFields: [
        {name: 'title', type: 'string'},
        {name: 'description', type: 'string'},
        {name: 'status', type: 'string'}
    ]
});