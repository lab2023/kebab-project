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
 * Kebab_Model_Application
 * 
 * @category   Kebab (kebab-reloaded)
 * @package    Kebab
 * @subpackage Model
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */


class Kebab_Model_Application
{
    /**
     *<p>This function return applications and their stories which are allowed in ACL.</p>
     * 
     * @static
     * @return array
     */
    public static function getApplicationsByPermission()
    {

        $lang  = Zend_Auth::getInstance()->getIdentity()->language;
        $roles = Zend_Auth::getInstance()->getIdentity()->roles;
        $query = Doctrine_Query::create()
                ->from('Model_Entity_Application a')
                ->leftJoin('a.Translation at')
                ->leftJoin('a.StoryApplication sa')
                ->leftJoin('sa.Story s')
                ->leftJoin('s.Permission p')
                ->leftJoin('p.Role r')
                ->whereIn('r.id', $roles)
                ->andWhere('a.active = 1 AND s.active = 1')
                ->orderBy('a.name DESC')
                ->orderBy('a.department DESC');
        $applications = $query->execute();

        $returnData = array();
        foreach ($applications as $application) {
            $app['identity'] = $application->identity;
            $app['className'] = $application->className;
            $app['name'] = $application->name;
            $app['type'] = $application->type;
            $app['department'] = $application->department;
            $app['version'] = $application->version;
            $app['title'] = array(
                'text' => $application->Translation[$lang]->title,
                'description' => $application->Translation[$lang]->description
            );
            foreach ($application->StoryApplication as $story) {
                $app['permission'][] = $story->Story->slug;
            }
            $returnData[] = $app;
        }

        return $returnData;
    }
}