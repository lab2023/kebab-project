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
 * Kebab_UserController
 *
 * @category   Kebab
 * @package    Kebab
 * @subpackage Controllers
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */
class Kebab_UserController extends Kebab_Rest_Controller
{
    public function indexAction()
    {
        // Mapping
        $mapping = array(
            'id' => 'user.id',
            'fullName' => 'user.fullName',
            'userName' => 'user.userName',
            'email' => 'user.email',
            'language' => 'user.language',
            'status' => 'user.status',
            'active' => 'user.active'
        );

        $searchUser = $this->_helper->search('Model_Entity_User');
        $order = $this->_helper->sort($mapping);
        $query = Kebab_Model_User::getAll($searchUser, $order);
        $pager = $this->_helper->pagination($query);
        $user = $pager->execute();

        // Response
        $responseData = is_object($user) ? $user->toArray() : array();
        $this->_helper->response(true)->addData($responseData)->addTotal($pager->getNumResults())->getResponse();
    }

    public function putAction()
    {
        // Getting parameters
        $params = $this->_helper->param();

        // Convert data collection array if not
        $collection = $this->_helper->array()->isCollection($params['data'])
                ? $params['data']
                : $this->_helper->array()->convertRecordtoCollection($params['data']);

        // Updating status
        Doctrine_Manager::connection()->beginTransaction();
        try {
            // Doctrine
            foreach ($collection as $record) {
                $user = new Model_Entity_User();
                $user->assignIdentifier($record['id']);
                if (array_key_exists('active', $record)) {
                    $user->set('active', $record['active']);
                }

                if (array_key_exists('status', $record)) {
                    $user->set('status', $record['status']);
                }
                $user->save();
            }
            Doctrine_Manager::connection()->commit();
            unset($user);

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

        // Convert data collection array if not
        $ids = $this->_helper->array()->convertArray($params['data']);

        // Updating status
        Doctrine_Manager::connection()->beginTransaction();
        try {

            Doctrine_Query::create()->delete()->from('Model_Entity_User user')->whereIn('user.id', $ids)->useQueryCache(Kebab_Cache_Query::isEnable())->execute();
            Doctrine_Manager::connection()->commit();
            // Delete Record and Return REST Response
            $this->_helper->response(true, 204)->addNotification('INFO', 'Record was deleted.')->getResponse();

        } catch (Zend_Exception $e) {
            Doctrine_Manager::connection()->rollback();
            throw $e;
        } catch (Doctrine_Exception $e) {
            Doctrine_Manager::connection()->rollback();
            throw $e;
        }
    }
}