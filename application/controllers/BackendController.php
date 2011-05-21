<?php

/**
 * Kebab Framework
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
 * @category   Kebab (kebab-reloaded)
 * @package    Kebab
 * @subpackage Controllers
 * @author	   Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @author     Tayfun Öziş ERİKAN <tayfun.ozis.erikan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */

/**
 * Kebab Application Main Controller
 *
 * @category   Kebab (kebab-reloaded)
 * @package    Kebab
 * @subpackage Controllers
 * @author	   Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @author     Tayfun Öziş ERİKAN <tayfun.ozis.erikan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
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
     * Sign-in screen
     * 
     * @return void
     */
    public function indexAction()
    {

    }

    /**
     * Sign-up screen
     *
     * @return void
     */
    public function signUpAction()
    {

    }
    
    /**
     * Desktop screen
     * 
     * @return void
     */
    public function desktopAction()
    {
        $this->view->stories = Kebab_Model_Story::getStories();
        $this->view->applications  = Kebab_Model_Application::getApplicationsByPermission();
    }
}
