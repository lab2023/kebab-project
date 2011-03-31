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
 * @package    Administration
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
 * @package    Administration
 * @subpackage Controllers
 * @author	   lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */
class Resource_UserController extends Kebab_Rest_Controller
{

    /**
     * readAction()
     *
     * <p></p>
     *
     * @return json
     */
    public function readAction()
    {
        // Session
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $userId = $auth->getIdentity()->id;

            // Doctrine
            $query = Doctrine_Query::create()
                    ->select('user.firstName, user.lastName, user.username, 
                        user.email, user.language')
                    ->where('user.id = ?', $userId)
                    ->from('Model_User user');
            
            $userRecord = $query->fetchOne()->toArray();
                $this->_helper->response()
                    ->setSuccess(true)
                    ->addData($userRecord)
                    ->getResponse();
        } else {
            throw new Kebab_Controller_Helper_Exception('Please sign in.');
        }
    }

    /**
     * updateAction()
     *
     * <p></p>
     *
     * @return json
     */
    public function updateAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        // Session
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()
            && $this->_request->getPost('id') == $auth->getIdentity()->id
        ) {

            // Get Post
            $userId = $auth->getIdentity()->id;
            $firstName = $this->_request->getPost('firstName');
            $surname = $this->_request->getPost('surname');
            $email = $this->_request->getPost('email');
            $locale = $this->_request->getPost('locale');

            // Filter
            $filter = new Zend_Filter_Int();
            $userId = $filter->filter($userId);
            $filter = new Zend_Filter_Alnum($allowWhiteSpace = true);
            $firstName = $filter->filter($firstName);
            $surname = $filter->filter($surname);
            $locale = $filter->filter($locale);

            // Validate
            $validate = new Zend_Validate_EmailAddress();
            if (!$validate->isValid($email) || is_null($email)) {
                $this->_helper->response()
                    ->addNotification('ERR', 'Email is not true.')
                    ->getResponse();
            }

            // Doctrine
            $query = Doctrine_Query::create()
                    ->from('Model_User user')
                    ->select('user.email')
                    ->where('id != ?', $userId);
            $emailRecord = $query->execute();
            $emailRecord = $emailRecord->toArray();

            foreach ($emailRecord as $value) {
                $emailRecord[] = $value['email'];
            }

            if (!in_array($email, $emailRecord)) {
                $query = Doctrine_Query::create()
                        ->from('Model_User user')
                        ->where('user.id = ?', $userId)
                        ->select('user.firstName, user.surname, user.email');
                $userRecord = $query->execute();

                if (count($userRecord->toArray()) == 1) {
                    $user = new Model_User();
                    $user->assignIdentifier($userId);
                    $user->firstName = $firstName;
                    $user->surname = $surname;
                    $user->email = $email;
                    $user->locale = $locale;
                    $user->save();

                    $this->_helper->response()
                        ->setSuccess(true)
                        ->addNotification('INFO',
                                          'Profile informations is updated. Restart your choice of language to be effective.')
                        ->getResponse();
                }
            } else {
                $this->_helper->response()
                    ->addNotification('ERR', 'Email not available.')
                    ->getResponse();
            }
        } else {
            throw new Kebab_Controller_Helper_Exception('Please sign in.');
        }
    }

    /**
     * changePasswordAction()
     *
     * <p></p>
     *
     * @return json
     */
    public function changePasswordAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        // Session
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()
            && $this->_request->getPost('id') == $auth->getIdentity()->id
        ) {

            // Get Post
            $userId = $auth->getIdentity()->id;
            $userPassword = $this->_request->getPost('password');
            $userPasswordConfirm = $this->_request->getPost('confirm_password');

            // Filter
            $userPassword = is_null($userPassword) ? null : md5($userPassword);
            $userPasswordConfirm = is_null($userPasswordConfirm) 
                                 ? null 
                                 : md5($userPasswordConfirm);

            if (!is_null($userPassword)
                && !is_null($userPasswordConfirm)
                && $userPasswordConfirm == $userPassword
            ) {
                // Doctrine
                $user = new Model_User();
                $user->assignIdentifier($userId);
                $user->password = $userPassword;
                $user->save();
                $this->_helper->response()
                    ->setSuccess(true)
                    ->addNotification('INFO', 'Password successfully changed.')
                    ->getResponse();
            } else {
                $this->_helper->response()
                    ->addNotification('ERR', 'Could not change password.')
                    ->getResponse();
            }
        } else {
            throw new Kebab_Controller_Helper_Exception('Please sign in.');
        }
    }

    /**
     * isOldPasswordAction()
     *
     * <p></p>
     *
     * @return json
     */
    public function isOldPasswordAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        // Session
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()
            && $this->_request->getPost('id') == $auth->getIdentity()->id
        ) {

            // Get Post
            $userId = $auth->getIdentity()->id;
            $userPassword = $this->_request->getPost('password');

            // Filter
            $userPassword = is_null($userPassword) ? null : md5($userPassword);

            // Doctrine
            $query = Doctrine_Query::create()
                    ->from('Model_User user')
                    ->where('user.id = ?', $userId)
                    ->select('user.password');
            $passwordRecord = $query->execute();
            $dbPassword = $passwordRecord->toArray();

            if ($dbPassword[0]['password'] === $userPassword) {
                $this->_helper->response()
                    ->setSuccess(true)
                    ->getResponse();
            } else {
                $this->_helper->response()
                    ->addNotification('ERR', 'Wrong password.')
                    ->getResponse();
            }
        } else {
            throw new Kebab_Controller_Helper_Exception('Please sign in.');
        }
    }

}
