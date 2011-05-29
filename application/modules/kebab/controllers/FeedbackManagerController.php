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
 * @package    System
 * @subpackage Controllers
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */


/**
 * FeedbackManager
 *
 * System admin can manage the feedback
 *
 * @category   Kebab (kebab-reloaded)
 * @package    System
 * @subpackage Controllers
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */
class Kebab_FeedbackManagerController extends Kebab_Rest_Controller
{
    public function indexAction()
    {
        // Mapping
        $mapping = array(
            'id' => 'feedback.id',
            'status' => 'feedback.status',
            'description' => 'feedback.description',
            'title' => 'applicationTranslate',
            'User' => 'user'
        );

        //KBBTODO move DQL to model class

        $query = Doctrine_Query::create()
                ->select('feedback.*,
                application.*,
                user.fullName,
                applicationTranslate.title as title')
                ->from('Model_Entity_Feedback feedback')
                ->innerJoin('feedback.Application application')
                ->leftJoin('application.Translation applicationTranslate')
                ->innerJoin('feedback.User user')
                ->where('applicationTranslate.lang = ?', Zend_Auth::getInstance()->getIdentity()->language)
                ->orderBy($this->_helper->sort($mapping));

        $pager = $this->_helper->pagination($query);
        $feedbacks = $pager->execute();

        $responseData = array();
        if (is_object($feedbacks)) {
            $responseData = $feedbacks->toArray();
        }

        $this->_helper->response(true, 200)->addData($responseData)->addTotal($pager->getNumResults())->getResponse();

    }

    /**
     * @return void
     */
    public function putAction()
    {
        // Getting parameters
        $params = $this->_helper->param();
        $id = $params['id'];
        $status = $params['status'];

        // Updating status
        Doctrine_Manager::connection()->beginTransaction();
        try {
            $feedback = new Model_Entity_Feedback();
            $feedback->assignIdentifier($id);
            $feedback->set('status', $status);
            $feedback->save();
            Doctrine_Manager::connection()->commit();
            unset($feedback);

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
}