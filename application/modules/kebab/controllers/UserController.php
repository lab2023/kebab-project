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
 * User_Manager
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
            'id' => 'user.id'
        );
        $params = $this->_helper->param();
        $roleId = array_key_exists('roleId', $params) ? $params['roleId'] : null;

        $ids = $this->_helper->search('Model_Entity_User');

        //KBBTODO Move dql to models
        $query = Doctrine_Query::create()
                ->select('user.fullName, user.email, user.userName, role.name, user.active')
                ->from('Model_Entity_User user')
                ->leftJoin('user.Roles role')
                ->whereIn('user.id', $ids);
        if(!empty($roleId) && $roleId != ''){
            $query = $query->where('role.id = ?', $roleId);
        }
        $query =  $query->orderBy($this->_helper->sort($mapping));
        $pager = $this->_helper->pagination($query);
        $users = $pager->execute();

        // Response
        $responseData = is_object($users) ? $users->toArray() : array();
        $this->_helper->response(true)->addData($responseData)->addTotal($pager->getNumResults())->getResponse();
    }

    public function putAction()
    {
        // Getting parameters
        $params = $this->_helper->param();
        $id = $params['id'];
        $status = $params['status'];

        // Updating status
        //KBBTODO move doctrine to model
        Doctrine_Manager::connection()->beginTransaction();
        try {
            $user = new User_Model_User();
            $user->assignIdentifier($id);
            $user->status = $status;
            $user->save();
            Doctrine_Manager::connection()->commit();
            unset($user);

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

    public function deleteAction()
    {
        // Getting parameters
        $params = $this->_helper->param();
        $id = $params['id'];

        // delete
        Doctrine_Manager::connection()->beginTransaction();
        try {

            $user = new User_Model_User();
            $user->assignIdentifier($id);
            $user->delete();

            // Doctrine
            Doctrine_Query::create()
                    ->delete('Model_Entity_Invitation invitation')
                    ->where('invitation.user_id = ?', $id)
                    ->execute();

            Doctrine_Manager::connection()->commit();
            unset($user);

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

    /**
     * User can invited or sign-up
     * @return void
     */
    public function postAction()
    {
        // Getting parameters
        $params = $this->_helper->param();

        switch ($params['type']) {
            case 'signUp':
                $user = Kebab_Model_User::signUp($params['fullName'], $params['email']);
                if (is_object($user)) {
                    $this->sendSignUpMail($user);
                    $this->_helper->response(true, 200);
                }
            default:
        }


    }

    private function sendSignUpMail($user)
    {
        $configParam = Zend_Registry::get('config')->kebab->mail;
        $smtpServer = $configParam->smtpServer;
        $config = $configParam->config->toArray();

        // Mail phtml
        $view = new Zend_View;
        $view->setScriptPath(APPLICATION_PATH . '/views/mails/');

        //KBBTODO use language file
        $view->assign('fullName', $user->fullName);
        $view->assign('activationKey', $user->activationKey);

        $transport = new Zend_Mail_Transport_Smtp($smtpServer, $config);
        $mail = new Zend_Mail('UTF-8');
        $mail->setFrom($configParam->from, 'Kebab Project');
        $mail->addTo($user->email, $user->fullName);
        $mail->setSubject('Welcome to Kebab Project');
        $mail->setBodyHtml($view->render('sign-up.phtml'));
        $mail->send($transport);
    }
}