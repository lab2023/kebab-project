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
 * @package    Kebab_Ajax
 * @author	   lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies
 *             TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */

/**
 * Kebab Ajax Response - Return object with ExtJS Standard
 *
 * <p> A object with success /p>
 *
 * @category   Kebab
 * @package    Kebab_Ajax
 * @subpackage Kebab_Ajax_Response
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies
 *             TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 */
class Kebab_Ajax_Response
{
    /**
     * default false
     *
     * @var string
     */
    public $success = 'false';

    /**
     * getSuccess()
     *
     * @return string
     */
    public function getSuccess()
    {
        return $this->success;
    }

    /**
     * setSuccess()
     *
     * @param  boolean $success
     * @throws Kebab_Ajax_Exception
     * @return Kebab_Ajax_Response
     */
    public function setSuccess($success)
    {
        if (is_bool($success)) {
            $this->success = $success ? 'true' : 'false';
        } else {
            throw new Kebab_Ajax_Exception('$success type must be boolean.');
        }

        return $this;
    }

    /**
     * getErrors() - get all errors
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * setErrors() - set errors
     *
     * @param  Kebab_Extjs_Form_Errors $errors
     * @return Kebab_Ajax_Response
     */
    public function setErrors(Kebab_Extjs_Form_Errors $errors)
    {
        $this->errors = $errors->getErrors();
        return $this;
    }

    /**
     * setNotifications - set notifications
     * 
     * @param  Kebab_Notification $notifications
     * @return Kebab_Ajax_Response 
     */
    public function setNotifications(Kebab_Notification $notifications)
    {
        $this->notifications = $notifications->getNotifications();
        return $this;
    }
}