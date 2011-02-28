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
 * @package    Controllers
 * @subpackage Default
 * @author	   lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */

/**
 * Kebab Application Main Controller
 *
 * @category   Kebab (kebab-reloaded)
 * @package    Controllers
 * @subpackage Default
 * @author	   lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */
class MainController extends Kebab_Controller_Action
{

    /**
     * indexAction
     */
    public function indexAction()
    {
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            
            // Get default language
            $defaultLanguage     = $auth->getIdentity()->language;
            $languagesFromConfig = Zend_Registry::get('config')->languages->translations->toArray();
            foreach ($languagesFromConfig as $k => $v) {
                $v['active'] = $defaultLanguage == $v['language'] ? true : false;
                $languages[] = $v; 
            }
            
            //die(Zend_Debug::dump($this->_getApplicationsByPermission()));
            $this->view->user           = $auth->getIdentity();
            $this->view->applications   = $this->_getApplicationsByPermission();
            $this->view->languages      = array_values($languages);
        }
    }

    /**
     * _getApplicationsByPermission()
     * 
     * <p>This function return applications and their stories which are allowed in ACL.</p>
     * 
     * @return array
     */
    private function _getApplicationsByPermission()
    {
        //KBBTODO Move this function Model_Application
        //KBBTODO If ACL system is off, should return all applications
        if (Zend_Registry::get('config')->plugins->kebabAcl) {
            $rolesWithAncestor = Zend_Auth::getInstance()->getIdentity()->rolesWithAncestor;
            $query = Doctrine_Query::create()
                    ->from('Model_Application a')
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
        } else {
            throw new Zend_Exception('ACL plugin is disable');
        }
    }

}
