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
 * @package    System
 * @subpackage Controllers
 * @author     lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */


/**
 * Role_Manager
 *
 * @category   Kebab (kebab-reloaded)
 * @package    Administration
 * @subpackage Controllers
 * @author     lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */
class Role_ManagerController extends Kebab_Rest_Controller
{
    /**
     * @return void
     */
    public function indexAction()
    {
        // Mapping
        $mapping = array(
            'id' => 'role.id',
            'name' => 'role.name',
            'title' => 'role.title',
            'description' => 'role.description',
            'status' => 'role.status'
        );

        Doctrine_Manager::connection()->beginTransaction();
        try {
            $query = Doctrine_Query::create()
                    ->select('role.name,
                    roleTranslation.title as title, 
                    roleTranslation.description as description,
                    role.status')
                    ->from('Model_Entity_Role role')
                    ->leftJoin('role.Translation roleTranslation')
                    ->where('roleTranslation.lang = ?', Zend_Auth::getInstance()->getIdentity()->language)
                    ->orderBy($this->_helper->sort($mapping));

            $pager = $this->_helper->pagination($query);
            $roles = $pager->execute();

            Doctrine_Manager::connection()->commit();

            $responseData = array();
            if (is_object($roles)) {
                $responseData = $roles->toArray();
            }

            $this->getResponse()
                    ->setHttpResponseCode(200)
                    ->appendBody(
                $this->_helper->response()
                        ->setSuccess(true)
                        ->addData($responseData)
                        ->addTotal($pager->getNumResults())
                        ->getResponse()
            );

        } catch (Zend_Exception $e) {
            throw $e;
        } catch (Doctrine_Exception $e) {
            throw $e;
        }
    }

    /**
     * @return void
     */
    public function postAction()
    {
        // Getting parameters
        $params = $this->_helper->param();
        $name = $params['name'];
        $title = $params['title'];
        $description = $params['description'];

        $lang = Zend_Auth::getInstance()->getIdentity()->language;

        // Inserting New Role
        Doctrine_Manager::connection()->beginTransaction();
        try {
            $role = new Role_Model_Role();
            $role->name = $name;
            $role->Translation[$lang]->title = $title;
            $role->Translation[$lang]->description = $description;

            if (array_key_exists('parentRoleId', $params)) {
                $parentRoleId = $params['parentRoleId'];
                $parentRole = Doctrine_Core::getTable('Model_Entity_Role')->find($parentRoleId);
                $role->getNode()->insertAsLastChildOf($parentRole);
            } else {
                $role->save();
                $treeObject = Doctrine_Core::getTable('Model_Entity_Role')->getTree();
                $treeObject->createRoot($role);
                unset($role);
            }
            Doctrine_Manager::connection()->commit();

            // Returning response
            $this->getResponse()
                    ->setHttpResponseCode(202)
                    ->appendBody(
                $this->_helper->response()
                        ->setSuccess(true)
                        ->getResponse()
            );
        } catch (Zend_Exception $e) {
            throw $e;
        } catch (Doctrine_Exception $e) {
            throw $e;
        }

    }

    public function putAction()
    {
        // Getting parameters
        $params = $this->_helper->param();
        $id = $params['id'];
        $status = $params['status'];

        // Updating status
        Doctrine_Manager::connection()->beginTransaction();
        try {
            $role = new Role_Model_Role();
            $role->assignIdentifier($id);
            $role->set('status', $status);
            $role->save();
            Doctrine_Manager::connection()->commit();
            unset($role);
            // Returning response
            $this->getResponse()
                    ->setHttpResponseCode(202)
                    ->appendBody(
                $this->_helper->response()
                        ->setSuccess(true)
                        ->getResponse()
            );
        } catch (Zend_Exception $e) {
            throw $e;
        } catch (Doctrine_Exception $e) {
            throw $e;
        }
    }

    public function deleteAction()
    {
        // Getting parameters
        $params = $this->_helper->param();
        $id = $params['id'];

        // Doctrine
        Doctrine_Manager::connection()->beginTransaction();
        try {
            Doctrine_Query::create()
                    ->delete('Model_Entity_Permission permission')
                    ->where('permission.role_id = ?', $id)
                    ->execute();

            // delete role
            $role = new Role_Model_Role();
            $role->assignIdentifier($id);
            $role->delete();
            Doctrine_Manager::connection()->commit();
            unset($role);
            // Returning response
            $this->getResponse()
                    ->setHttpResponseCode(201)
                    ->appendBody(
                $this->_helper->response()
                        ->setSuccess(true)
                        ->getResponse()
            );
        } catch (Zend_Exception $e) {
            throw $e;
        } catch (Doctrine_Exception $e) {
            throw $e;
        }
    }


}