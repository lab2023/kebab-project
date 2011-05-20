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
 * @subpackage Model
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
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
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */

class Kebab_Model_Story
{
    static public function getStory()
    {
        $lang = Zend_Auth::getInstance()->getIdentity()->language;
        $query = Doctrine_Query::create()
                ->select('story.*, storyTranslation.title as title,
                    storyTranslation.description as description, permission.*')
                ->from('Model_Entity_Story story')
                ->leftJoin('story.Permission permission')
                ->leftJoin('story.Translation storyTranslation')
                ->where('storyTranslation.lang = ?', $lang)
                ->where('story.active = 1');

        return $query;
    }

    static public function getStories()
    {
        $lang  = Zend_Auth::getInstance()->getIdentity()->language;
        $roles = Zend_Auth::getInstance()->getIdentity()->roles;
        $query = Doctrine_Query::create()
                    ->select('s.*')
                    ->from('Model_Entity_Story s')
                    ->leftJoin('s.Permission p')
                    ->leftJoin('s.Translation st')
                    ->where('st.lang = ?', $lang)
                    ->andWhere('s.active = 1')
                    ->whereIn('p.role_id', $roles);
        return $query->execute();
    }
}