<?php
/**
 * Kebab Project
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
 * User_RoleManager
 *
 * @category   Kebab
 * @package    Kebab
 * @subpackage Controllers
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */
class Kebab_UserRoleController extends Kebab_Rest_Controller
{
    public function indexAction()
    {
        $userId = $this->_request->userId;

        // Mapping
        $mapping = array(
            'id' => 'role.id',
            'title' => 'roleTranslation.title',
            'description' => 'roleTranslation.description',
            'allow' => 'allow'
        );

        $searchRole = $this->_helper->search('Model_Entity_Role', true);
        $order = $this->_helper->sort($mapping);
        $query = Kebab_Model_UserRole::getUserRoles($userId, $searchRole, $order);
        $pager = $this->_helper->pagination($query);
        $userRole = $pager->execute();


        $responseData = is_object($userRole) ? $userRole->toArray() : array();
        $this->_helper->response(true)->addData($responseData)->getResponse();
    }

    public function putAction()
    {

        Doctrine_Manager::connection()->beginTransaction();
        try {

            // Get Data and convert them array
            $params = $this->_helper->param();
            $userId = $params['userId'];

            // Convert data collection array if not
            $collection = $this->_helper->array()->isCollection($params['data'])
                    ? $params['data']
                    : $this->_helper->array()->convertRecordtoCollection($params['data']);

            Kebab_Model_UserRole::update($userId, $collection);

            Doctrine_Manager::connection()->commit();

            // Rest Response
            $this->_helper->response(true, 201)->addNotification('INFO', 'Record was updated.')->getResponse();

            unset($collection);
            unset($responseData);
        } catch (Zend_Exception $e) {
            // Doctrine_Manager::connection()->rollback();
            throw $e;
        } catch (Doctrine_Exception $e) {
            Doctrine_Manager::connection()->rollback();
            throw $e;
        }
    }
}