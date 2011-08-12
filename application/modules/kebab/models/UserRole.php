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
 * @package    Kebab
 * @subpackage Model
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */

/**
 * Model_UserRole
 *
 * @category   Kebab
 * @package    Kebab
 * @subpackage Model
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */

class Kebab_Model_UserRole
{
    /**
     * @static
     * @param  $userId
     * @param  $collection
     * @return void
     * @return void
     */
    public static function update($userId, $collection)
    {
        foreach ($collection as $item) {

            if ($item['allow'] == 1 && !self::has($userId, $item['id'])) {
                self::insert($userId, $item['id']);
            }

            if ($item['allow'] == 0 && self::has($userId, $item['id'])) {
                self::delete($userId, $item['id']);
            }
        }
    }

    /**
     * @static
     * @param  $userId
     * @param  $roleId
     * @return bool
     */
    public static function has($userId, $roleId)
    {
        return Doctrine_Core::getTable('Model_Entity_UserRole')->findByuser_idAndrole_id($userId, $roleId)->count() > 0;
    }

    /**
     * @static
     * @param  $userId
     * @param  $roleId
     * @return bool|Model_Entity_UserRole
     */
    public static function insert($userId, $roleId)
    {
        $retVal = false;
        if (!self::has($userId, $roleId)) {
            $userRole = new Model_Entity_UserRole();
            $userRole->user_id = $userId;
            $userRole->role_id = $roleId;
            $userRole->save();
            $retVal = $userRole;
            unset($userRole);
        }

        return $retVal;
    }

    /**
     * @static
     * @param  $userId
     * @param  $roleId
     * @return bool
     */
    public static function delete($userId, $roleId)
    {
        $retVal = false;
        if (self::has($userId, $roleId)) {
            Doctrine_Query::create()
                    ->delete('Model_Entity_UserRole userRole')
                    ->where('userRole.user_id = ? AND userRole.role_id = ?', array($userId, $roleId))
                    ->useQueryCache(Kebab_Cache_Query::isEnable())
                    ->execute();
            $retVal = true;
        }

        return $retVal;
    }
}