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
 * @package    Model
 * @subpackage User Model
 * @author	   Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */

/**
 * Model_User
 *
 * @category   Kebab (kebab-reloaded)
 * @package    Model
 * @subpackage User Model
 * @author	   Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */

class Kebab_Model_User
{
    /**
     * @static
     * @param  $userId
     * @return array
     */
    public static function getUserRoles($userId)
    {
        $userId = (int) $userId;
        $query = Doctrine_Query::create()
                    ->select('role.name')
                    ->from('Model_Entity_Role role')
                    ->leftJoin('role.UserRole userrole')
                    ->where('userrole.user_id = ?', $userId);

        $rolesResult = $query->execute();
        
        $roles = array();
        foreach ($rolesResult as $role) {
           $roles[] = $role->name;
        }
        
        return $roles;
    }
}