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
 * @category   Kebab
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
 * @category   Kebab
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
            'title' => 'title',
            'description' => 'description',
            'active' => 'role.active'
        );

        //KBBTODO move dql to model
        $ids = $this->_helper->search('Model_Entity_Role', true);
        $query = Doctrine_Query::create()
                ->select('
                    roleTranslation.title as title,
                    roleTranslation.description as description,
                    role.active')
                ->addSelect('(SELECT COUNT(permission.story_id)
                                  FROM Model_Entity_Permission permission
                                  WHERE role.id = permission.role_id) as num_story')
                ->addSelect('(SELECT COUNT(userRole.role_id)
                                  FROM Model_Entity_UserRole userRole
                                  WHERE userRole.role_id = role.id) as num_user')
                ->from('Model_Entity_Role role')
                ->leftJoin('role.Translation roleTranslation')
                ->where('roleTranslation.lang = ?', Zend_Auth::getInstance()->getIdentity()->language)
                ->whereIn('role.id', $ids)
                ->orderBy($this->_helper->sort($mapping))
                ->useQueryCache(Kebab_Cache_Query::isEnable());

        $pager = $this->_helper->pagination($query);
        $roles = $pager->execute();

        // Response
        $responseData = is_object($roles) ? $roles->toArray() : array();
        $this->_helper->response(true)->addData($responseData)->addTotal($pager->getNumResults())->getResponse();

    }

    /**
     * @return void
     */
    public function postAction()
    {
        // Get Data and convert them array
        $params = $this->_helper->param();
        $lang = Zend_Auth::getInstance()->getIdentity()->language;

        // Convert data collection array if not
        $collection = $this->_helper->array()->isCollection($params['data'])
                ? $params['data']
                : $this->_helper->array()->convertRecordtoCollection($params['data']);

        // Updating status
        //KBBTODO move dql to model
        Doctrine_Manager::connection()->beginTransaction();
        try {
            // Doctrine
            foreach ($collection as $record) {
                $role = new Model_Entity_Role();
                if (array_key_exists('active', $record)) {
                    $role->active = $record['active'];
                }

                if (array_key_exists('title', $record)) {
                    $role->Translation[$lang]->title = $record['title'];
                }

                if (array_key_exists('description', $record)) {
                    $role->Translation[$lang]->description = $record['description'];
                }

                $role->save();
            }
            Doctrine_Manager::connection()->commit();
            // Response
            $this->_helper->response(true, 200)->addNotification('INFO', 'Record was created.')->getResponse();
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
        // Get Data and convert them array
        $params = $this->_helper->param();
        $lang = Zend_Auth::getInstance()->getIdentity()->language;

        // Convert data collection array if not
        $collection = $this->_helper->array()->isCollection($params['data'])
                ? $params['data']
                : $this->_helper->array()->convertRecordtoCollection($params['data']);

        // Updating status
        //KBBTODO move dql to model
        Doctrine_Manager::connection()->beginTransaction();
        try {
            // Doctrine
            foreach ($collection as $record) {
                $role = new Model_Entity_Role();
                $role->assignIdentifier($record['id']);
                unset($record['id']);

                if (array_key_exists('active', $record)) {
                    $role->active = $record['active'];
                }

                if (array_key_exists('title', $record)) {
                    $role->Translation[$lang]->title = $record['title'];
                }

                if (array_key_exists('description', $record)) {
                    $role->Translation[$lang]->description = $record['description'];
                }

                $role->save();
            }
            Doctrine_Manager::connection()->commit();
            // Response
            $this->_helper->response(true, 201)->addNotification('INFO', 'Record was updated.')->getResponse();
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
        $ids = $params['data'];

        //KBBTODO move dql to model
        Doctrine_Manager::connection()->beginTransaction();
        try {

            // Delete permission
            Doctrine_Query::create()
                    ->delete('Model_Entity_Permission permission')
                    ->whereIn('permission.role_id', $ids)
                    ->useQueryCache(Kebab_Cache_Query::isEnable())
                    ->execute();

            // Delete permission
            Doctrine_Query::create()
                    ->delete('Model_Entity_UserRole userRole')
                    ->whereIn('userRole.role_id', $ids)
                    ->useQueryCache(Kebab_Cache_Query::isEnable())
                    ->execute();

            // Delete Role
            Doctrine_Query::create()
                    ->delete('Model_Entity_Role role')
                    ->whereIn('role.id', $ids)
                    ->useQueryCache(Kebab_Cache_Query::isEnable())
                    ->execute();

            Doctrine_Manager::connection()->commit();

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