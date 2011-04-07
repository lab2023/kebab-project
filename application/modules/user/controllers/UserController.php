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
class User_UserController extends Kebab_Rest_Controller
{

    public function indexAction()
    {
        $rolesWithAncestor = Zend_Auth::getInstance()->getIdentity()->rolesWithAncestor;
        if (in_array('admin', $rolesWithAncestor)) {
            // Mapping
            $mapping = array(
                'id' => 'user.id',
                'firstName' => 'user.firstName',
                'lastName' => 'user.lastName',
                'email' => 'user.email',
                'language' => 'user.language',
                'status' => 'user.status',
                'username' => 'user.username',
                'created_at' => 'user.created_at',
                'deleted_at' => 'user.deleted_at',
                'created_by' => 'user.created_by',
                'updated_by' => 'user.updated_by',
                'slug' => 'user.slug'
            );

            // Query
            $query = Doctrine_Query::create()
                        ->select('
                            user.id,
                            user.firstName,
                            user.lastName,
                            user.email,
                            user.language,
                            user.status,
                            user.username,
                            user.created_at,
                            user.deleted_at,
                            user.created_by,
                            user.updated_by,
                            user.slug
                        ')
                        ->from('Model_Entity_User user')
                        ->orderBy($this->_helper->sort($mapping));

            // Pager
            $pager = $this->_helper->pagination($query);

            // Response
            $responseData = $pager->execute()->toArray();
            $this->getResponse()
                    ->setHttpResponseCode(200)
                    ->appendBody(
                $this->_helper->response()
                        ->setSuccess(true)
                        ->addTotal($pager->getNumResults())
                        ->addData($responseData)
                        ->getResponse()
            );
        } else {
            throw new Zend_Exception('You can\'t see all user information');
        }
    }

    
    public function getOwnUserInfoAction()
    {
        // Params
        $rolesWithAncestor = Zend_Auth::getInstance()->getIdentity()->rolesWithAncestor;
        $userId = in_array('admin', $rolesWithAncestor)
                ? $this->_request->getParam('id')
                : Zend_Auth::getInstance()->getIdentity()->id;

        // Doctrine
        $query = Doctrine_Query::create()
                ->select('user.firstName, user.lastName, user.username,
                    user.email, user.language')
                ->from('Model_User user')
                ->where('user.id = ?', $userId)
                ->fetchOne();

        // Response
        $this->getResponse()
                ->setHttpResponseCode(200)
                ->appendBody(
            $this->_helper->response()
                    ->setSuccess(true)
                    ->addData($query->toArray())
                    ->getResponse()
        );
    }

    public function postAction(){}
    public function deleteAction(){}


    /**
     * updateAction()
     *
     * <p></p>
     *
     * @return json
     */
    public function putAction()
    {
        // Param
        $params = $this->_helper->param();
        
        // Convert data collection array if not
        if(array_key_exists('data', $params)) {
            $collection = $this->_helper->array()->isCollection($params['data'])
                ? $params['data']
                : $this->_helper->array()->convertRecordtoCollection($params['data']);
        } else {
            $collection = $this->_helper->array()->convertRecordtoCollection($params);
        }

        // Doctrine
        Doctrine_Manager::connection()->beginTransaction();
        try {

            $responseUserIds = array();
            foreach ($collection as $record) {
                $user = Doctrine_Core::getTable('Model_Entity_User')->find($record['id']);
                $user->fromArray($record);
                $user->save();

                $responseUserIds[] = array('id' => $user->id);
                unset($user);
            }

            // Rest Response
            Doctrine_Manager::connection()->commit();
            $this->getResponse()
                    ->setHttpResponseCode(201)
                    ->appendBody(
                $this->_helper->response()
                        ->setSuccess(true)
                        ->addNotification('INFO', 'Record was updated.')
                        ->getResponse()
            );
        } catch (Doctrine_Exception $e) {
            Doctrine_Manager::connection()->rollback();
            throw $e;
        } catch (Zend_Exception $e) {
            Doctrine_Manager::connection()->rollback();
            throw $e;
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
