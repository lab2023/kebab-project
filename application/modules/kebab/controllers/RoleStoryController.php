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
 * @category   Kebab (kebab-reloaded)
 * @package    Kebab
 * @subpackage Controllers
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */


/**
 * User_RoleManager
 *
 * @category   Kebab (kebab-reloaded)
 * @package    Kebab
 * @subpackage Controllers
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */
class Kebab_RoleStoryController extends Kebab_Rest_Controller
{
    public function indexAction()
    {
        $params = $this->_helper->param();
        $roleId = $params['roleId'];

        $ids = $this->_helper->search('Model_Entity_Story', true);
        $query = Kebab_Model_Story::getStory($roleId, $ids);

        $pager = $this->_helper->pagination($query);
        $story = $pager->execute();

        $responseData = is_object($story) ? $story->toArray() : array();
        $this->_helper->response(true, 200)->addData($responseData)->addTotal(count($responseData))->getResponse();
    }

    public function putAction()
    {
        // Getting parameters
        $params = $this->_helper->param();
        $roleId = $params['roleId'];

        // Convert data collection array if not
        $collection = $this->_helper->array()->isCollection($params['data'])
                    ? $params['data']
                    : $this->_helper->array()->convertRecordtoCollection($params['data']);

        // Doctrine
        //KBBTODO move dql to models
        Doctrine_Manager::connection()->beginTransaction();
        try {
            foreach ($collection as $story) {
                $recordExist = is_object(Doctrine_Core::getTable('Model_Entity_Permission')->findOneBystory_idAndrole_id($story['id'], $roleId));
                if ($story['allow'] && !$recordExist) {
                    $permission = new Model_Entity_Permission();
                    $permission->story_id = $story['id'];
                    $permission->role_id = $roleId;
                    $permission->save();
                } elseif (!$story['allow'] && $recordExist) {
                    Doctrine_Query::create()
                        ->delete('Model_Entity_Permission p')
                        ->where('p.role_id = ? AND p.story_id = ?', array($roleId, $story['id']))
                        ->execute();
                }
            }
            Doctrine_Manager::connection()->commit();
            $this->_helper->response(true, 201)->getResponse();
            unset($permission);

        } catch (Zend_Exception $e) {
            Doctrine_Manager::connection()->rollback();
            throw $e;
        } catch (Doctrine_Exception $e) {
            Doctrine_Manager::connection()->rollback();
            throw $e;
        }

    }
}