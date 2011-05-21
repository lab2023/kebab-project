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
 * @package    Core
 * @subpackage Plugins
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */

/**
 * Plugin_KebabDeveloperTools
 *
 * @category   Kebab (kebab-reloaded)
 * @package    Core
 * @subpackage Plugins
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */
class Plugin_KebabDeveloperTools extends Kebab_Controller_Plugin_Abstract
{

    public function __construct()
    {
        parent::__construct(__CLASS__, __FILE__);
    }

    public function postDispatch(Zend_Controller_Request_Abstract $request)
    {
        if ($request->getParam('error_handler'))
            return; // By-pass to errors

        // Access active viewRenderer helper
        $view = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer')->view;
        $view->addScriptPath($this->_pluginPath . '/views');

        // Setup data
        $data = new stdClass();
        $data->position = $this->_pluginConfig->position;
        $data->scriptExecutingTime = number_format((microtime(true) - SCRIPT_START_TIME), 5, '.', ',');
        $data->memoryPeakUsage = number_format(memory_get_peak_usage(true));
        $data->resource = $this->getAllResource();

        // Assign view data
        $view->assign('data', $data);

        // Appanend View data to response body
        $this->getResponse()->appendBody($view->render('index.phtml'));
    }

    private function getAllResource()
    {
        $front = Zend_Controller_Front::getInstance();
        $resource = array();

        foreach ($front->getControllerDirectory() as $module => $path) {

            foreach (scandir($path) as $file) {

                if (strstr($file, "Controller.php") !== false) {

                    include_once $path . DIRECTORY_SEPARATOR . $file;

                    foreach (get_declared_classes() as $class) {

                        if (is_subclass_of($class, 'Zend_Controller_Action')) {

                            $controller = strtolower(substr($class, 0, strpos($class, "Controller")));
                            $actions = array();

                            foreach (get_class_methods($class) as $action) {

                                if (strstr($action, "Action") !== false) {
                                    $actions[] = $action;
                                }
                            }
                        }
                    }

                    $resource[$module][$controller] = $actions;
                }
            }
        }

        return $resource;
    }
}