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
class MainController extends Kebab_Controller_Action
{
    public function init()
    {
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $this->view->identity = $auth->getIdentity();
            $this->view->applications = $this->_getAllApplicationByPermission();
            
        }
    }

    /**
     * Index action
     * @return void
     */
    public function indexAction()
    {
        
    }
    
    private function _getAllApplicationByPermission()
    {        
        $query = Doctrine_Query::create()
                 ->select('a.name')
                 ->from('Model_Application a')
                 ->leftJoin('a.StoryApplication sa')
                 ->leftJoin('sa.Story s')
                 ->leftJoin('s.Permission p')
                 ->leftJoin('p.Role r')
                 ->where('a.status = ?', array('active'))
                 ->whereIn('r.name', array('guest', 'admin', 'owner'));
        $application = $query->execute();
        
        return $application->toArray();
    }

}
