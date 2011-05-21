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
                    ->select('role.id')
                    ->from('Model_Entity_Role role')
                    ->leftJoin('role.UserRole userRole')
                    ->where('userRole.user_id = ?', $userId);

        $rolesResult = $query->execute();
        
        $roles = array();
        foreach ($rolesResult as $role) {
           $roles[] = $role->id;
        }
        
        return $roles;
    }

    /**
     * @static
     * @throws Doctrine_Exception|Zend_Exception
     * @param string $fullName
     * @param string $email
     * @param string $language
     * @return bool|Model_Entity_User
     */
    public static function signUp($fullName, $email, $language = 'en')
    {
        $retVal = false;
        Doctrine_Manager::connection()->beginTransaction();
        try {

            $userSignUp = new Model_Entity_User();
            $userSignUp->fullName = $fullName;
            $userSignUp->email = $email;
            $userSignUp->language = $language;
            $userSignUp->activationKey = Kebab_Security::createActivationKey();
            $userSignUp->status = 'pending';
            $userSignUp->active = 0;
            $userSignUp->save();

            $retVal = $userSignUp;
            Doctrine_Manager::connection()->commit();
            unset($userSignUp);
        } catch (Zend_Exception $e) {
            Doctrine_Manager::connection()->rollback();
            throw $e;
        } catch (Doctrine_Exception $e) {
            Doctrine_Manager::connection()->rollback();
            throw $e;
        }

        return $retVal;
    }

    /**
     * @static
     * @throws Doctrine_Exception|Zend_Exception
     * @param string $fullName
     * @param string $email
     * @param string $language
     * @return bool|Model_Entity_User
     */
    public static function invite($fullName, $email, $language = 'en')
    {
        $retVal = false;
        Doctrine_Manager::connection()->beginTransaction();
        try {

            $userSignUp = new Model_Entity_User();
            $userSignUp->fullName = $fullName;
            $userSignUp->email = $email;
            $userSignUp->language = $language;
            $userSignUp->activationKey = Kebab_Security::createActivationKey();
            $userSignUp->status = 'pending';
            $userSignUp->active = 0;
            $userSignUp->save();

            $retVal = $userSignUp;
            Doctrine_Manager::connection()->commit();
            unset($userSignUp);
        } catch (Zend_Exception $e) {
            Doctrine_Manager::connection()->rollback();
            throw $e;
        } catch (Doctrine_Exception $e) {
            Doctrine_Manager::connection()->rollback();
            throw $e;
        }

        return $retVal;
    }
}