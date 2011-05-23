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
 * @package    Model
 * @subpackage User Model
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */

/**
 * Model_UserRole
 *
 * @category   Kebab (kebab-reloaded)
 * @package    Model
 * @subpackage User Model
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */

class Kebab_Model_UserRole
{
    public static function getUserRoles($userId, $searchRoleId, $sort)
    {
        $query = Doctrine_Query::create()
                ->select('role.id,
                    roleTranslation.title as title,
                    roleTranslation.description as description,
                    IF(userRole.user_id > 0, true, false) as allow')
                ->from('Model_Entity_Role role')
                ->leftJoin('role.UserRole userRole ON userRole.role_id = role.id AND userRole.user_id = ?', $userId)
                ->leftJoin('role.Translation roleTranslation')
                ->where('roleTranslation.lang = ?', Zend_Auth::getInstance()->getIdentity()->language)
                ->whereIn('role.id', $searchRoleId)
                ->orderBy($sort);

        return $query;
    }
}