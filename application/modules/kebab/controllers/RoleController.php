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
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */


/**
 * Kebbab_Role
 *
 * @category   Kebab (kebab-reloaded)
 * @package    Kebab
 * @subpackage Controllers
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */
class Kebab_RoleController extends Kebab_Rest_Controller
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
            'status' => 'role.status',
            'active' => 'role.active'
        );

        //KBBTODO move dql to model
        $query = Doctrine_Query::create()
                ->select('
                    role.name,
                    roleTranslation.title as title,
                    roleTranslation.description as description,
                    role.status, role.active')
                    ->addSelect('(SELECT COUNT(permission.story_id)
                                  FROM Model_Entity_Permission permission
                                  WHERE role.id = permission.role_id) as num_story')
                    ->addSelect('(SELECT COUNT(userRole.role_id)
                                  FROM Model_Entity_UserRole userRole
                                  WHERE userRole.role_id = role.id) as num_user')
                ->from('Model_Entity_Role role')
                ->leftJoin('role.Translation roleTranslation')
                ->where('roleTranslation.lang = ?', Zend_Auth::getInstance()->getIdentity()->language)
                ->orderBy($this->_helper->sort($mapping));

        $pager = $this->_helper->pagination($query);
        $roles = $pager->execute();

        // Response
        $responseData = is_object($roles) ? $roles->toArray() : array();
        $this->_helper->response(true, 200)->addData($responseData)->addTotal($pager->getNumResults())->getResponse();

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
        $active = $params['active'];

        $lang = Zend_Auth::getInstance()->getIdentity()->language;

        // Inserting New Role
        //KBBTODO move dql to model
        Doctrine_Manager::connection()->beginTransaction();
        try {
            $role = new Role_Model_Role();
            $role->name = $name;
            $role->active = $active;
            $role->Translation[$lang]->title = $title;
            $role->Translation[$lang]->description = $description;
            $role->save();
            
            Doctrine_Manager::connection()->commit();

            // Response
            $this->_helper->response(true, 202)->getResponse();
            
        } catch (Zend_Exception $e) {
            Doctrine_Manager::connection()->rollback();
            throw $e;
        } catch (Doctrine_Exception $e) {
            Doctrine_Manager::connection()->rollback();
            throw $e;
        }
    }

    public function putAction()
    {
        // Getting parameters
        $params = $this->_helper->param();
        $id = $params['data']['id'];
        $active = $params['data']['active'];

        // Updating status
        //KBBTODO move dql to model
        Doctrine_Manager::connection()->beginTransaction();
        try {
            $role = new Model_Entity_Role();
            $role->assignIdentifier($id);
            $role->set('active', $active);
            $role->save();
            Doctrine_Manager::connection()->commit();
            unset($role);

            // Response
            $this->_helper->response(true, 202)->getResponse();
        } catch (Zend_Exception $e) {
            Doctrine_Manager::connection()->rollback();
            throw $e;
        } catch (Doctrine_Exception $e) {
            Doctrine_Manager::connection()->rollback();
            throw $e;
        }
    }

    public function deleteAction()
    {
        // Getting parameters
        $params = $this->_helper->param();
        $id = $params['roleId'];

        //KBBTODO move dql to model
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