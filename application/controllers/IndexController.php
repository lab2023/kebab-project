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
 * Kebab Application Index Controller
 *
 * @category   Kebab (kebab-reloaded)
 * @package    Controllers
 * @subpackage Default
 * @author	   lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */
class IndexController extends Kebab_Controller_Action
{
    /**
     * Home Page
     * @return void
     */
    public function indexAction()
    {
       
    }
    
    /**
     * Shop Page
     * @return void
     */
    public function shopAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        
       //KBBTODO move dql to model
        $query = Doctrine_Query::create()
                ->select('
                role.name,
                roleTranslation.title as title,
                roleTranslation.description as description,
                role.status, role.active')
                ->addSelect('(SELECT COUNT(permission.story_id) FROM Model_Entity_Permission permission WHERE role.id = permission.role_id) as num_story')
                ->addSelect('(SELECT COUNT(userRole.role_id) FROM Model_Entity_UserRole userRole WHERE userRole.role_id = role.id) as num_user')
                ->from('Model_Entity_Role role')
                ->leftJoin('role.Translation roleTranslation')
                ->where('roleTranslation.lang = ?', Zend_Auth::getInstance()->getIdentity()->language);
        Zend_Debug::dump($query->execute()->toArray());
    }
}
