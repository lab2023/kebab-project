<?php

if (!defined('BASE_PATH'))
    exit('No direct script access allowed');
/**
 * Kebab Framework
 *
 * LICENSE
 *
 * This source file is subject to the  Dual Licensing Model that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.kebab-project.com/licensing
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@lab2023.com so we can send you a copy immediately.
 *
 * @category   Kebab (kebab-reloaded)
 * @package    Controllers
 * @subpackage Default
 * @author	   lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */

/**
 * Kebab Application Main Controller
 *
 * @category   Kebab (kebab-reloaded)
 * @package    Controllers
 * @subpackage Default
 * @author	   lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */
class BackendController extends Kebab_Controller_Action
{
    /**
     * Controller Initializer
     * @return void
     */
    public function init()
    {
        // Set backend layout
        $this->_helper->layout->setLayout('backend');

        // Access zend auth and set user identity
        $this->_auth = Zend_Auth::getInstance();
        $this->view->user = $this->_auth->getIdentity();
    }
    
    /**
     * Backend login screen
     * 
     * @return void
     */
    public function indexAction()
    {

    }
    
    /**
     * Desktop 
     * 
     * @throws Zend_Exception
     * @return void
     */
    public function desktopAction()
    {
        if (Zend_Registry::get('config')->plugins->kebabAcl) {
            $rolesWithAncestor = Zend_Auth::getInstance()->getIdentity()->rolesWithAncestor;
            $this->view->applications  = Model_Application::getApplicationsByPermission(
                $rolesWithAncestor, $this->_auth->getIdentity()->language
            );
        } else {
            throw new Zend_Exception('ACL plugin is disable');
        }
    }
}
