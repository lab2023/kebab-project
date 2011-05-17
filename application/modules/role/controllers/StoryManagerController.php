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
 * User_RoleManager
 *
 * @category   Kebab (kebab-reloaded)
 * @package    Administration
 * @subpackage Controllers
 * @author     lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */
class Role_StoryManagerController extends Kebab_Rest_Controller
{
    public function indexAction()
    {
        Doctrine_Manager::connection()->beginTransaction();
        try {
            $story = Access_Model_Story::getStory()->execute();

            $responseData = array();
            if (is_object($story)) {
                $responseData = $story->toArray();
            }
            Doctrine_Manager::connection()->commit();
            $this->getResponse()
                    ->setHttpResponseCode(200)
                    ->appendBody(
                $this->_helper->response()
                        ->setSuccess(true)
                        ->addData($responseData)
                        ->getResponse()
            );

        } catch (Zend_Exception $e) {
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
        $roleId = $params['roleId'];
        $storyId = $params['storyId'];

        // Doctrine
        Doctrine_Manager::connection()->beginTransaction();
        try {
            Doctrine_Query::create()
                    ->delete('Model_Entity_Permission permission')
                    ->where('permission.role_id = ?', $roleId)
                    ->execute();

            foreach ($storyId as $story) {
                $permission = new Model_Entity_Permission();
                $permission->role_id = $roleId;
                $permission->story_id = $story;
                $permission->save();
            }
            Doctrine_Manager::connection()->commit();
            unset($permission);

        } catch (Zend_Exception $e) {
            throw $e;
        } catch (Doctrine_Exception $e) {
            Doctrine_Manager::connection()->rollback();
            throw $e;
        }

    }
}