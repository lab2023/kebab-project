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
 * @package    Kebab_Notification
 * @author	   lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies
 *             TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */

/**
 * Notification - implement session-based messages
 *
 * @uses       ReflectionClass
 * @category   Kebab
 * @package    Kebab_Notification
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies
 *             TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 */
class Kebab_Notification implements IteratorAggregate, Countable
{
    const ALERT     = "ALERT";      // Alert: action must be taken immediately
    const CRIT      = "CRIT";       // Critical: critical conditions
    const ERR       = "ERR";        // Error: error conditions
    const WARN      = "WARN";       // Warning: warning conditions
    const NOTICE    = "NOTICE";     // Notice: normal but significant condition
    const INFO      = "INFO";       // Informational: informational messages

    /**
     * $_notifications - All notifications from current request
     * 
     * @access protected
     * @var    array
     */
    static protected $_notifications = array();

    /**
     * addNotification() - Add a new notification
     *
     * @param string $notificationType
     * @param string $notification
     * @return void
     */
    static public function addNotification($notificationType, $notification)
    {

        $type = array('ALERT', 'CRIT', 'ERR', 'WARN', 'NOTICE', 'INFO');

        if (!in_array($notificationType, $type)) {
            throw new Kebab_Notification_Exception('Invalid notification type');
        }

        if (!is_string($notification)) {
            throw new Kebab_Notification_Exception('Invalid notification string');
        }
        
        self::$_notifications[] = array(
            $notificationType,
            Zend_Registry::get('translate')->_($notification)
        );
    }

    /**
     * hasNotifications() - Are there any notifications?
     *
     * @return boolean
     */
    public function hasNotifications()
    {
        if (count(self::$_notifications) > 0) {
            return true;
        }

        return false;
    }

    /**
     * getNotifications() - Get all notifications
     *
     * @return array()
     */
    static public function getNotifications()
    {
        if (self::hasNotifications()) {
            return self::$_notifications;
        }

        return array();
    }

    /**
     * clearNotifications() - Clear all notifications
     *
     * @return boolean
     */
    static public function clearNotifications()
    {
        if (self::hasNotifications()) {
            self::$_notifications = array();
            return true;
        }

        return false;
    }

    /**
     * getIterator() - Complete the IteratorAggregate interface, for iterating
     *
     * @return ArrayObject
     */
    public function getIterator()
    {
        if ($this->hasNotifications()) {
            return new ArrayObject($this->getNotifications());
        }

        return new ArrayObject();
    }

    /**
     * count() - Complete the countable interface
     *
     * @return int
     */
    public function count()
    {
        if ($this->hasNotifications()) {
            return count($this->getNotifications());
        }

        return 0;
    }

}
