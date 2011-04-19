/**
 * Kebab Application Bootstrap Class
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.feedback.application.layouts
 * @author      Yunus ÖZCAN <yunus.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/licensing
 */
KebabOS.applications.feedback.application.layouts.Layout = Ext.extend(Ext.Panel, {

    // Application bootstrap
    bootstrap: null,
    layout: 'border',
    border: false,

    initComponent: function() {

        // panels are defined here
        this.feedbackForm = new KebabOS.applications.feedback.application.views.FeedbackForm({
            bootstrap: this.bootstrap
        });

        this.feedbackGrid = new KebabOS.applications.feedback.application.views.FeedbackGrid({
            bootstrap: this.bootstrap
        });

        Ext.apply(this, {
            items: [this.feedbackForm, this.feedbackGrid]
        });

        KebabOS.applications.feedback.application.layouts.Layout.superclass.initComponent.call(this);
    }
});
