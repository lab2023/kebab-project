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
 * @package    Kebab_Controller
 * @subpackage Kebab_Controller_Action_Helper
 * @author	   lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies
 *             TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */
/**
 * @see Zend_Controller_Action_Helper_Abstract
 */
require_once 'Zend/Controller/Action/Helper/Abstract.php';

/**
 * Notification - implement session-based messages
 *
 * @uses       Zend_Controller_Action_Helper_Abstract
 * @uses       Kebab_Notification
 * @category   Kebab
 * @package    Kebab_Controller
 * @subpackage Kebab_Controller_Action_Helper
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies
 *             TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 */
class Kebab_Controller_Action_Helper_Notification
    extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * add() - add a new notification
     *
     * @param  Kebab_Notification $notificationType
     * @param  string $notification
     * @return Kebab_Controller_Action_Helper_Notification 
     */
    public function add($notificationType, $notification)
    {
        Kebab_Notification::addNotification($notificationType, $notification);
        return $this;
    }

    /**
     * getNotifications() - Get all notifications
     *
     * @return array()
     */
    public function get()
    {
        return Kebab_Notification::getNotifications();
    }

    /**
     * count()
     *
     * @return int
     */
    public function count()
    {
        return Kebab_Notification::count();
    }

    /**
     * has() - Are there any messages?
     *
     * @return boolean
     */
    public function has()
    {
        return Kebab_Notification::hasNotifications();
    }

    /**
     * clearNotifications() - Clear all notifications
     *
     * @return boolean
     */
    public function clear()
    {
        return Kebab_Notification::clearNotifications();
    }

    /**
     * postDispatch() - Clear all notifications after dispatch
     *
     * @return Kebab_Controller_Action_Helper_Notification
     */
    public function postDispatch()
    {
        self::clear();
        return $this;
    }

    /**
     * Strategy pattern: proxy to addNotification()
     *
     * @param  Kebab_Notification $notificationType
     * @param  string $notification
     * @return void
     */
    public function direct($notificationType, $notification)
    {
        return $this->add($notificationType, $notification);
    }

}
