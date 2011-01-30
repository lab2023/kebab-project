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
 * @package    PACKAGE
 * @subpackage SUB_PACKAGE
 * @author	   lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */

/**
 * System_Controller_Helper_KebabResponse
 *
 * @category   Kebab (kebab-reloaded)
 * @package    Controller
 * @subpackage Helper
 * @author	   lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @since      1.5.x (kebab-reloaded)
 * @version    1.5.0
 */
class System_Controller_Helper_KebabResponse extends Zend_Controller_Action_Helper_Abstract
{
    protected $_response = array();

    public function setSuccess($success)
    {
        if (is_bool($success)) {
            $this->_response['success'] = $success ? 'true' : 'false';
        } else {
            throw new Kebab_Controller_Helper_Exception('$success type must be boolean.');
        }

        return $this;
    }

    public function setResults($results, $addTotal = true, $name = 'results')
    {
        if (!is_array($results)
            && (!is_object($results) || !($results instanceof Traversable))
        ) {
            throw new Kebab_Controller_Helper_Exception('Only arrays and Traversable objects may be added to $response[\'data\']');
        }

        if ($addTotal) {
            $this->_response['total'] = count($results);
        }
        $this->_response[$name] = $results;

        return $this;
    }

    public function setErrors($errors, $name = 'errors')
    {
        if (!is_array($errors)
            && (!is_object($errors) || !($errors instanceof Traversable))
        ) {
            throw new Kebab_Controller_Helper_Exception('Only arrays and objects may be added to $response[\'errors\']');
        }

        foreach ($errors as $key => $value) {
            $this->_response[$name][$key] = Zend_Registry::get('translate')->_($value);
        }

        return $this;
    }

    public function addError($id, $value)
    {
        if (is_null($id) || is_null($value)) {
            throw new Kebab_Controller_Helper_Exception('$id and $value can\'t be  $response[\'errors\']');
        }

        foreach ($errors as $key => $value) {
            $this->_response['errors'][] = array(
                $id => Zend_Registry::get('translate')->_($value)
            );
        }

        return $this;
    }

    public function addNotification($notificationType, $notification,
        $autoHide = TRUE)
    {
        $type = array('ALERT', 'CRIT', 'ERR', 'WARN', 'NOTICE', 'INFO');

        if (!in_array($notificationType, $type)) {
            throw new Kebab_Controller_Helper_Exception('Invalid notification type');
        }

        if (!is_string($notification)) {
            throw new Kebab_Controller_Helper_Exception('Invalid notification string');
        }

        if (!is_bool($autoHide)) {
            throw new Kebab_Controller_Helper_Exception('Invalid autoHide type');
        }

        if (!is_string($group)) {
            throw new Kebab_Controller_Helper_Exception('Invalid $group type');
        }

        $this->_response['notifications'][] = array(
            $notificationType,
            Zend_Registry::get('translate')->_($notification),
            $autoHide
        );

        return $this;
    }

    public function getResponse()
    {
        $jsonHelper = new Zend_Controller_Action_Helper_Json();
        $jsonHelper->direct($this->_response);
    }

    public function direct($success = false)
    {
        $this->setSuccess($success);
        return $this;
    }

}