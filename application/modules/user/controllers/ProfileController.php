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
 * @package    User Module
 * @subpackage Controllers
 * @author	   lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */

/**
 * Preferences_AboutMeController
 *
 * @category   Kebab (kebab-reloaded)
 * @package    Profile
 * @subpackage Controllers
 * @author	   lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */
class User_ProfileController extends Kebab_Rest_Controller
{
    /**
     * @return void
     */
    public function getAction()
    {
        // Param
        $params = $this->_helper->param();
        $userSessionId = Zend_Auth::getInstance()->getIdentity()->id;

        // Validation

        // DQL
        $query = Doctrine_Query::create()
                ->select('user.id, user.firstName, user.lastName, user.email, user.language, user.username')
                ->from('User_Model_User user')
                ->where('user.id = ?', array($userSessionId));

        if (is_object($query->fetchOne())) {
            $responseData = $query->fetchOne()->toArray();
        }

        $this->getResponse()
                    ->setHttpResponseCode(200)
                    ->appendBody(
                $this->_helper->response()
                        ->setSuccess(true)
                        ->addData($responseData)
                        ->getResponse()
            );
    }

    /**
     * @return void
     */
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

        $userExistsWithEmail = Doctrine_Query::create()
        				->from('Model_Entity_User user')
        				->where('user.email = ?', $email)
        				->andWhere('user.id != ?', $userSessionId)->fetchOne();
        
		if (is_object($userExistsWithEmail)) {
			 // Another User exists with entered email
	        $this->getResponse()
	                    ->setHttpResponseCode(201)
	                    ->appendBody(
	                $this->_helper->response()
	                		->set('email', 'Another User with email exists.')
	                        ->getResponse()
	            );
		}
		
        // DQL
        $profile = new User_Model_User();
        $profile->assignIdentifier($userSessionId);
        $profile->firstName = $firstName;
        $profile->lastName = $lastName;
        $profile->email = $email;
        $profile->language = $language;
        $profile->save();
        unset($profile);

        // Response
        $this->getResponse()
                    ->setHttpResponseCode(201)
                    ->appendBody(
                $this->_helper->response()
                        ->setSuccess(true)
                        ->getResponse()
            );

    }
}