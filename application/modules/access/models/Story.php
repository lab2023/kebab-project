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
 * @package    Model
 * @subpackage Feedback Model
 * @author	   lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */

/**
 * Model_Feedback
 *
 * @package    Model
 * @subpackage Feedback Model
 * @author	   lab2023 Dev Team
 * @version    1.5.0
 */

class Access_Model_Story extends Model_Entity_Role
{
    static public function getStory($roleId)
    {
        $lang = Zend_Auth::getInstance()->getIdentity()->language;
        return $query = Doctrine_Query::create()
                ->select('permission.story_id, storyTranslation.title as title,
                    storyTranslation.description as description, permission.role_id, story.id')
                ->from('Model_Entity_Permission permission')
                ->leftJoin('permission.Story story')
                ->leftJoin('story.Translation storyTranslation')
                ->where('storyTranslation.lang = ?', $lang);
    }
}