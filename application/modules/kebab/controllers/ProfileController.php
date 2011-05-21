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
 * @package    Kebab
 * @subpackage Controllers
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */

/**
 * Kebab_ProfileController
 *
 * Member can change her/his password, first name, last name etc.
 *
 * @category   Kebab (kebab-reloaded)
 * @package    Kebab
 * @subpackage Controllers
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */
class Kebab_ProfileController extends Kebab_Rest_Controller
{
    public function getAction()
    {
        // Param
        $userSessionId = Zend_Auth::getInstance()->getIdentity()->id;

        //KBBTODO move DQL to model class
        $query = Doctrine_Query::create()
                ->select('user.id, user.firstName, user.lastName, user.email, user.language, user.userName')
                ->from('Model_Entity_User user')
                ->where('user.id = ?', array($userSessionId));

        if (is_object($query->fetchOne())) {
            $responseData = $query->fetchOne()->toArray();
        }

        $this->_helper->response(true, 200)->addData($responseData)->getResponse();
    }

    public function putAction()
    {
        // Param
        $params = $this->_helper->param();
        $userSessionId = Zend_Auth::getInstance()->getIdentity()->id;

        // Validation
        $firstName = $params['firstName'];
        $lastName = $params['lastName'];
        $email = $params['email'];
        $language = $params['language'];

        //KBBTODO move DQL to model class
        Doctrine_Manager::connection()->beginTransaction();
        try {
            $userExistsWithEmail = Doctrine_Query::create()
                    ->from('Model_Entity_User user')
                    ->where('user.email = ?', $email)
                    ->andWhere('user.id != ?', $userSessionId)->fetchOne();

            if (is_object($userExistsWithEmail)) {
                // Another User exists with entered email
                $this->_helper->response(false, 201)->set('email', 'Another User with email exists.')->getResponse();
            }

            // DQL
            $profile = new Model_Entity_User();
            $profile->assignIdentifier($userSessionId);
            $profile->firstName = $firstName;
            $profile->lastName = $lastName;
            $profile->email = $email;
            $profile->language = $language;
            $profile->save();
            Doctrine_Manager::connection()->commit();
            unset($profile);

            // Response
            $this->_helper->response(true, 201)->getResponse();

        } catch (Zend_Exception $e) {
            Doctrine_Manager::connection()->rollback();
            throw $e;
        } catch (Doctrine_Exception $e) {
            Doctrine_Manager::connection()->rollback();
            throw $e;
        }

    }
}