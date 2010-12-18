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
 * @category   KEBAB
 * @package    Core
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */
class MainController extends Kebab_Controller_Action
{

    private $_fc;

    public function init()
    {
        $this->_fc = Zend_Controller_Front::getInstance();
        $this->_fc->setParam('noViewRenderer', true);
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $this->view->identity = $auth->getIdentity();
        }
    }

    public function indexAction()
    {
        echo "index - member";
    }

    public function memberAction()
    {
        echo "member";
    }

    public function adminAction()
    {
        echo "admin";
    }

    public function ownerAction()
    {
        echo "owner";
    }

}