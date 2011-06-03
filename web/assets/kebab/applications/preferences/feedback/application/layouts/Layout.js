/**
 * Kebab Application Bootstrap Class
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.feedback.application.layouts
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 */
KebabOS.applications.feedback.application.layouts.Layout = Ext.extend(Ext.Panel, {

    // Application bootstrap
    bootstrap: null,
    layout: {
        type: 'vbox',
        pack: 'start',
        align: 'stretch'
    },
    border: false,

    initComponent: function() {

        // panels are defined here
        this.feedbackForm = new KebabOS.applications.feedback.application.views.FeedbackForm({
            bootstrap: this.bootstrap,
            border:false,
            height:270
        });

        this.feedbackGrid = new KebabOS.applications.feedback.application.views.FeedbackGrid({
            title: Kebab.helper.translate('Your Feedback History'),
            bootstrap: this.bootstrap,
            flex:2,
            layout:'fit'
        });

        Ext.apply(this, {
            items: [this.feedbackForm, this.feedbackGrid]
        });

        KebabOS.applications.feedback.application.layouts.Layout.superclass.initComponent.call(this);
    }
});
