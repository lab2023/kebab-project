<?php

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
 * @package    PACKAGE
 * @subpackage SUB_PACKAGE
 * @author     lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */
class Plugin_KebabAcl_Adaptor_File extends Zend_Acl
{

    public function __construct()
    {
        $this->addAllRoles();
        $this->addAllResources();
        $this->addAllPersmissions();
    }

    public function addAllRoles()
    {
        $this->addRole(new Zend_Acl_Role('guest'));
        $this->addRole(new Zend_Acl_Role('member'), 'guest');
        $this->addRole(new Zend_Acl_Role('admin'), 'member');
        $this->addRole(new Zend_Acl_Role('owner'), 'admin');
    }

    public function addAllResources()
    {
        $this->addResource(new Zend_Acl_Resource('Administration-Index'));
        $this->addResource(new Zend_Acl_Resource('Administration-User'));
        $this->addResource(new Zend_Acl_Resource('Default-Desktop'));
    }

    public function addAllPermissions()
    {
        $this->allow('guest', 'Default-Desktop');
        $this->allow('member', 'Administration-User');
        $this->allow('admin', 'Administration-Index');
    }

}
