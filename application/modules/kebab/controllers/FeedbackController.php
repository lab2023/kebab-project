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
 * Feedback
 *
 * Member can open and list their own feedback
 *
 * @category   Kebab
 * @package    Kebab
 * @subpackage Controllers
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */
class Kebab_FeedbackController extends Kebab_Rest_Controller
{
    public function indexAction()
    {
        // Params
        $userSessionId = Zend_Auth::getInstance()->getIdentity()->id;
        $mapping       = array(
            'id' => 'feedback.id',
            'title' => 'applicationTranslate.title',
            'status' => 'feedback.status',
            'description' => 'feedback.description'
        );
        $mapping       = $this->_helper->sort($mapping);
        $options       = array('sort' => $mapping);

        // Model
        $query         = Kebab_Model_Feedback::getFeedbackByUserId($userSessionId, $options);
        $pager         = $this->_helper->pagination($query);
        $retData       = $pager->execute(array(), Doctrine::HYDRATE_ARRAY);

        // Response
        $this->_helper->response(true, 200)->addData($retData)->addTotal($pager->getNumResults())->getResponse();
    }

    public function postAction()
    {
        // Params
        $params = $this->_helper->param();
        $userSessionId = Zend_Auth::getInstance()->getIdentity()->id;
        $applicationIdentity = $params['applicationIdentity'];
        $description = $params['description'];

        // Model
        $feedback = Kebab_Model_Feedback::insert($userSessionId, $applicationIdentity, $description);

        // Response
        if ($feedback instanceof Doctrine_Record) {
            $this->_helper->response(true, 200)
                    ->addNotification(Kebab_Notification::NOTICE, 'Feedback is created')
                    ->addData($feedback->toArray())
                    ->getResponse();
        } else {
            $this->_helper->response(true)
                ->addNotification(Kebab_Notification::ERR, 'Feedback is not created')
                ->getResponse();
        }
    }
}