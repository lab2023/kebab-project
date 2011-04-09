/**
 * FeedbackManager Application Layout Class
 *
 * @category    Kebab (kebab-reloaded)
 * @package     Applications
 * @namespace   KebabOS.applications.feedbackManager.application.layouts
 * @author      Yunus ÖZCAN <yuns.ozcan@lab2023.com>
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/licensing
 */
KebabOS.applications.feedbackManager.application.layouts.Layout = Ext.extend(Ext.Panel, {

    // Application bootstrap
    bootstrap: null,
    layout:'fit',
    border:false,

    initComponent: function() {
        this.feedbackManagerGrid = new KebabOS.applications.feedbackManager.application.views.FeedbackManagerGrid({
            bootstrap: this.bootstrap
        });
        
        var config = {
            items:[this.feedbackManagerGrid]
        }

        Ext.apply(this, config);

        KebabOS.applications.feedbackManager.application.layouts.Layout.superclass.initComponent.call(this);
    }
});
