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
 * @package    Core
 * @subpackage Plugins
 * @author	   lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */

/**
 * Plugin_KebabDeveloperTools
 *
 * @category   Kebab (kebab-reloaded)
 * @package    Application
 * @subpackage Plugins
 * @author	   lab2023 Dev Team
 */
class Plugin_KebabDeveloperTools extends Kebab_Controller_Plugin_Abstract
{

    public function __construct()
    {
        parent::__construct(__CLASS__, __FILE__);
    }

    public function postDispatch(Zend_Controller_Request_Abstract $request)
    {
        // Access active viewRenderer helper
        $view = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer')->view;
        $view->addScriptPath($this->_pluginPath . '/views');

        // Setup data
        $data = new stdClass();
        $data->position = $this->_pluginConfig->position;
        $data->scriptExecutingTime = number_format((microtime(true) - SCRIPT_START_TIME), 5, '.', ',');
        $data->memoryPeakUsage = number_format(memory_get_peak_usage(true));

        // Assign view data
        $view->assign('data', $data);

        // Appanend View data to response body
        $this->getResponse()->appendBody($view->render('index.phtml'));
    }

}