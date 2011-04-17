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
            'description' => 'role.description'
        );

        $query = Doctrine_Query::create()
                ->select('role.name, 
                    roleTranslation.title as title, 
                    roleTranslation.description as description')
                ->from('Model_Entity_Role role')
                ->leftJoin('role.Translation roleTranslation')
                ->where('roleTranslation.lang = ?', Zend_Auth::getInstance()->getIdentity()->language)
                ->orderBy($this->_helper->sort($mapping));

        $pager = $this->_helper->pagination($query);
        $roles = $pager->execute();

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
            }
           
            
            // Returning response
            $this->getResponse()
                        ->setHttpResponseCode(202)
                        ->appendBody(
                    $this->_helper->response()
                            ->setSuccess(true)
                            ->getResponse()
                );
        } catch (Zend_Exception $e) {
            die('ZE');
        } catch (Doctrine_Exception $e) {
            throw $e;
        }
    	/*
		$param = $this->_helper->param();
        if (array_key_exists('parentId', $param) && array_key_exists('title', $param)) {

            // Create a new cats
            $parentCat = Doctrine_Core::getTable('Product_Model_Category')->find($param['parentId']);
            $cat = new Product_Model_Category();
            $cat->Translation['tr']->title = $param['title']; //KBBTODO get lang by session or config
            $cat->getNode()->insertAsLastChildOf($parentCat);
            $responseData = $cat->toArray();

            // Unset
            unset($cat);

            // Response
            $this->getResponse()
             ->setHttpResponseCode(200)
             ->appendBody(
                 $this->_helper->response()
                      ->setSuccess(true)
                      ->addData($responseData)
                      ->addNotification('INFO', 'Record was created.')
                      ->getResponse()
             );
        }
    	 */


    }
}