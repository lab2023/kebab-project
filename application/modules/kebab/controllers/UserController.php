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
 * Kebab_UserController
 *
 * @category   Kebab (kebab-reloaded)
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
            'id'        => 'user.id',
            'fullName'  => 'user.fullName',
            'userName'  => 'user.userName',
            'email'     => 'user.email',
            'language'  => 'user.language',
            'status'    => 'user.status',
            'active'    => 'user.active'
        );

        $searchUser = $this->_helper->search('Model_Entity_User');
        $order      = $this->_helper->sort($mapping);
        $query      = Kebab_Model_User::getAll($searchUser, $order);
        $pager      = $this->_helper->pagination($query);
        $user       = $pager->execute();

        // Response
        $responseData = is_object($user) ? $user->toArray() : array();
        $this->_helper->response(true)->addData($responseData)->addTotal($pager->getNumResults())->getResponse();
    }
}