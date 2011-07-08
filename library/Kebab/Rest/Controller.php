<?php

/**
 * Kebab Project
 *
 * LICENSE
 *
 * This source file is subject to the  Dual Licensing Model that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.kebab-project.com/cms/licensing
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@lab2023.com so we can send you a copy immediately.
 *
 * @category   Kebab
 * @package    PACKAGE
 * @subpackage SUB_PACKAGE
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */

/**
 *
 * @category    Kebab
 * @package     Kebab_Rest
 * @author      Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @see         Zend_Rest_Route
 * @copyright   Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license     http://www.kebab-project.com/cms/licensing
 */
abstract class Kebab_Rest_Controller extends Zend_Rest_Controller
{
    /**
     * @var boolean
     */
    protected $_doctrineCaching;
    
    /**
     * Set layout disable and no view renderer
     */
    public function init()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->_doctrineCaching = Zend_Registry::get('config')->database->doctrine->caching->enable ? true : false;
    }
    
    public function indexAction(){}  
    public function getAction(){}
    public function postAction(){}
    public function putAction(){}
    public function deleteAction(){}
}
