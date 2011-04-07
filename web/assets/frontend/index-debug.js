/* -----------------------------------------------------------------------------
 Kebab Project 1.5.x (Kebab Reloaded)
 http://kebab-project.com
 Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc.
 http://www.lab2023.com

    * LICENSE
    * 
    * This source file is subject to the  Dual Licensing Model that is bundled
    * with this package in the file LICENSE.txt.
    * It is also available through the world-wide-web at this URL:
    * http://www.kebab-project.com/licensing
    * If you did not receive a copy of the license and are unable to
    * obtain it through the world-wide-web, please send an email
    * to info@lab2023.com so we can send you a copy immediately.
----------------------------------------------------------------------------- */
Ext.onReady(function() {
    // BEGIN PRELOADER---------------------------
    var preLoader = Ext.get('kebab-loading-mask');
    var preLoaderClicked = false;
    preLoader.on('click', function() {removePreLoader(0); preLoaderClicked = true;});
    new Ext.util.DelayedTask(function(){if (!preLoaderClicked)removePreLoader(2);}).delay(800);
    var removePreLoader = function(duration) {preLoader.slideOut('t', {remove: false, duration:duration});}
    var showPreLoader = function(duration) {preLoader.slideIn('t', {duration:duration});}
    // END OF PRELOADER--------------------------
    Ext.QuickTips.init();
});
