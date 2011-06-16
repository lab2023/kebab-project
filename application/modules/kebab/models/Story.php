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
 * @subpackage Model
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */

/**
 * Kebab_Model_Story
 *
 * @category   Kebab (kebab-reloaded)
 * @package    Kebab
 * @subpackage Model
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */

class Kebab_Model_Story
{
    static public function getStory(Array $whereInIds = array(), $roleId = '')
    {
        $lang = Zend_Auth::getInstance()->getIdentity()->language;
        $query = Doctrine_Query::create()
                ->select('story.*,
                    storyTranslation.title as title,
                    storyTranslation.description as description,
                    permission.role_id')
                ->from('Model_Entity_Story story')
                ->leftJoin('story.Permission permission')
                ->leftJoin('story.Translation storyTranslation')
                ->where('storyTranslation.lang = ?', $lang)
                ->whereIn('story.id', $whereInIds)
                ->andWhere('story.active = 1')
                ->useQueryCache(Kebab_Cache_Query::isEnable());
        if ($roleId) {
            $query->addSelect('(SELECT COUNT(p.role_id) FROM Model_Entity_Permission p
                                WHERE p.role_id = ' . $roleId . ' and p.story_id = story.id) as allow');
        }
        return $query;
    }

    /**
     * Return user's role's stories's slug.
     *
     * @static
     * @return array
     */
    static public function getStoriesSlug()
    {
        $roles = Zend_Auth::getInstance()->getIdentity()->roles;
        $query = Doctrine_Query::create()
                ->select('s.slug')
                ->from('Model_Entity_Story s')
                ->leftJoin('s.Permission p')
                ->andWhere('s.active = 1')
                ->andWhereIn('p.role_id', $roles)
                ->useQueryCache(Kebab_Cache_Query::isEnable());

        $retVal = array();
        foreach ($query->execute()->toArray() as $story) {
            $retVal[] = $story['slug'];
        }

        return $retVal;
    }
}