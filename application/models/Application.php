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
 * @subpackage Application Model
 * @author	   Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */

/**
 * Model_Application
 * 
 * @package    Model
 * @subpackage Application Model
 * @author	   Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @version    1.5.0
 */


class Model_Application extends Model_Entity_Application
{
    /**
     *<p>This function return applications and their stories which are allowed in ACL.</p>
     * 
     * @param  array  $rolesWithAncestor
     * @param  string $defaultLanguage
     * @return array
     */
    public static function getApplicationsByPermission($rolesWithAncestor, $defaultLanguage)
    {
        $query = Doctrine_Query::create()
                ->from('Model_Application a')
                ->leftJoin('a.Translation at')
                ->leftJoin('a.StoryApplication sa')
                ->leftJoin('sa.Story s')
                ->leftJoin('s.Permission p')
                ->leftJoin('p.Role r')
                ->whereIn('r.name', $rolesWithAncestor)
                ->andWhere('a.status = ?', array('active'))
                ->andWhere('s.status = ?', array('active'));
        $applications = $query->execute();

        $returnData = array();
        foreach ($applications as $application) {
            $app['identity'] = $application->identity;
            $app['class'] = $application->class;
            $app['name'] = $application->name;
            $app['title'] = $application->Translation[$defaultLanguage]->title;
            $app['description'] = $application->Translation[$defaultLanguage]->description;
            $app['type'] = $application->type;
            $app['department'] = $application->department;
            $app['version'] = $application->version;
            $app['type'] = $application->type;
            foreach ($application->StoryApplication as $story) {
                $app['permission'][] = $story->Story->slug;
            }
            $returnData[] = $app;
        }

        return $returnData;
    }
}