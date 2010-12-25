<?php if ( ! defined('BASE_PATH')) exit('No direct script access allowed');
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
 * @package    Kebab
 * @author	   lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */

/**
 * Kebab ACL
 * This class setup and all acl resources
 *
 * @category   Kebab (kebab-reloaded)
 * @package    Kebab
 * @author	   lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */
class Kebab_Acl extends Zend_Acl
{
    /**
     * Call all private methods this class
     * @return void
     */
    function __construct()
    {
        self::addAllRoles();
        self::addAllResources();
        self::addAllAllow();
    }

    /**
     * Add kebab roles
     * @return void
     */
    private function addAllRoles()
    {
        //Add Role
        parent::addRole(new Zend_Acl_Role('guest'));
        parent::addRole(new Zend_Acl_Role('member'));
        parent::addRole(new Zend_Acl_Role('admin'));
        parent::addRole(new Zend_Acl_Role('owner'));
    }

    /**
     * Add kebab resources
     * @return void
     */
    private function addAllResources()
    {
        //Add Resource
        parent::add(new Zend_Acl_Resource('Default_Index'));
        parent::add(new Zend_Acl_Resource('Default_Auth'));
        parent::add(new Zend_Acl_Resource('Default_Error'));
        parent::add(new Zend_Acl_Resource('Default_Main'));
    }

    /**
     * Add kebab acl allows
     * @return void
     */
    private function addAllAllow()
    {
        // Rules
        parent::allow('guest', 'Default_Index');
        parent::allow('guest', 'Default_Auth');
        parent::allow('guest', 'Default_Error');
        parent::allow('member', 'Default_Main', array('member', 'index'));
        parent::allow('admin', 'Default_Main', array('admin'));
        parent::allow('owner', 'Default_Main', array('owner'));
    }

}
