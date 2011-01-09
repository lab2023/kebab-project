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
 * Kebab_Extjs_Form_Load - Return object with ExtJS Standard
 *
 * <p>
 * If the client wanna to load form, server return an array.
 * $data = array('
 *          'formInputName' => 'value',
 *          'formInoutNameTwo' => 'valueTwo'
 *         );
 *
 * For more information see the Ext.form.Action.Load 
 * </p>
 *
 * @category   Kebab
 * @package    Kebab_Extjs
 * @subpackage Kebab_Extjs_Load
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies
 *             TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 */
class Kebab_Extjs_Form_Load extends ArrayObject
{

    /**
     * @access protected
     * @var array
     */
    protected $data = array();

    /**
     * getData() - return data
     *
     * @return array
     * @return void
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * setData() - set data property
     *
     * <p>When you use this function you reset previous data!</p>
     *
     * @param  array $data
     * @throws Kebab_ExtJs_Form_Exception
     * @return Kebab_Extjs_Form_Data
     */
    public function setData($data)
    {
        if (is_array($data)) {
            $this->data = $data;
        } else {
            throw new Kebab_Extjs_Form_Exception('$data type must be array.');
        }

        return $this;
    }

    /**
     * addFieldValue() - add a new field and value to data stack
     *
     * @param string $fieldName
     * @param string $fieldValue
     * @throws Kebab_Extjs_Form_Exception
     * @return Kebab_Extjs_Form_Errors
     */
    public function addField($fieldName, $fieldValue)
    {
        //KBBTODO check the $id is unique
        if (!is_null($fieldName)
            && !is_null($fieldValue)
            && is_string($fieldName)
            && is_string($fieldValue)
        ) {
            $this->errors[] = array($fieldName => $fieldValue);
        } else {
            throw new Kebab_Extjs_Form_Exception('Invalid $fieldName or $fieldValue type.');
        }

        return $this;
    }

    /**
     * hasData() - Are there any form field at data stack?
     *
     * @return boolean
     */
    public function hasData()
    {
        if (count($this->data) > 0) {
            return true;
        }

        return false;
    }

}