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
 * @package    Kebab_Extjs
 * @author	   lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies
 *             TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */

/**
 * Kebab_Extjs_Form_Errors - Return object with ExtJS Standard
 *
 * <p> A object with success /p>
 *
 * @category   Kebab
 * @package    Kebab_Extjs
 * @subpackage Kebab_Extjs_Form
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies
 *             TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 */
class Kebab_Extjs_Form_Errors
{

    /**
     * @access protected
     * @var array
     */
    protected $errors = array();

    /**
     * getErrors() - return errrors 
     * @return array
     * @return void
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * setErrrors() - set all errors
     *
     * <p>When you use this function you reset previous errors!</p>
     *
     * @param  array $errors
     * @throws Kebab_ExtJs_Form_Exception
     * @return Kebab_Extjs_Form_Errors
     */
    public function setErrors($errors)
    {
        if (is_array($errors)) {
            $this->errors = $errors;
        } else {
            throw new Kebab_Extjs_Form_Exception('$errors type must be array.');
        }

        return $this;
    }

    /**
     * addError() - add a new error to errors stack
     * 
     * @param string $fieldId
     * @param string $errorMessage
     * @throws Kebab_Extjs_Form_Exception
     * @return Kebab_Extjs_Form_Errors
     */
    public function addError($fieldId, $errorMessage)
    {
        //KBBTODO check the $id is unique
        if (!is_null($fieldId) && !is_null($errorMessage)) {
            $this->errors[] = array($fieldId => $errorMessage);
        } else {
            throw new Kebab_Extjs_Form_Exception('$fieldId and $errorMessage can\'t be null.');
        }

        return $this;
    }

    /**
     * hasErrors() - Are there any errors?
     *
     * @return boolean
     */
    public function hasErrors()
    {
        if (count($this->errors) > 0) {
            return true;
        }

        return false;
    }

}