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
 * @package
 * @subpackage Controllers
 * @author	   lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */

/**
 * Preferences_AboutMeController
 *
 * @category   Kebab (kebab-reloaded)
 * @package
 * @subpackage Controllers
 * @author	   lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */
class Access_StoryManagerController extends Kebab_Rest_Controller
{
    public function indexAction()
    {
         // Mapping
        $mapping = array(
            'id' => 'story.id',
            'status' => 'story.status',
            'description' => 'storyTranslation.description',
            'title' =>'storyTranslation.title',
        );

        $query = Doctrine_Query::create()
                ->select('story.id, storyTranslation.title as title,
                    storyTranslation.description as description, story.status')
                ->from('Model_Entity_Story story')
                ->leftJoin('story.Translation storyTranslation')
                ->where('storyTranslation.lang = ?', Zend_Auth::getInstance()->getIdentity()->language)
                ->orderBy($this->_helper->sort($mapping));

        $pager = $this->_helper->pagination($query);
        $story = $pager->execute();

        $responseData = array();
        if (is_object($story)) {
            $responseData = $story->toArray();
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

    public function putAction()
    {
        // Getting parameters
        $params = $this->_helper->param();
        $id = $params['id'];
        $status = $params['status'];

        // Updating status
        try {
            $story = new Access_Model_Story();
            $story->assignIdentifier($id);
            $story->set('status', $status);
            $story->save();

            // Returning response
            $this->getResponse()
                        ->setHttpResponseCode(202)
                        ->appendBody(
                    $this->_helper->response()
                            ->setSuccess(true)
                            ->getResponse()
                );
        } catch (Zend_Exception $e) {
            throw $e;
        } catch (Doctrine_Exception $e) {
            throw $e;
        }
    }
}