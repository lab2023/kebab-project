<?php

/**
 * Kebab Framework
 *
 * LICENSE
 *
 * This source file is subject to the  Dual Licensing Model that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.kebab-project.com/cms/licensing
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@lab2023.com so we can send you a copy immediately.
 *
 * @category   Kebab (kebab-reloaded)
 * @package    Controller Helper
 * @subpackage Array Helper
 * @author     Onur Özgür ÖZKAN
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */

/**
 * System_Controller_Helper_Array
 * 
 * <p>
 * If an array stores one object, it is record array. For example
 *      array('title' => 'Developer', 'name' => 'Onur');
 * 
 * if an array stores more objects, it is collection array. For example
 *      array(
 *          array('title' => 'Developer', 'name' => 'Onur'),
 *          arrray('title' => 'Student', 'name' => 'Ozgun')
 *      );
 * </p>
 *
 * @category   Kebab (kebab-reloaded)
 * @package    Controller
 * @subpackage Helper
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @since      1.5.x (kebab-reloaded)
 * @version    1.5.0
 */
class Kebab_Controller_Helper_Array extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * This method check the array is a collection array or not
     * 
     * @param   array $data
     * @throws  Kebab_Controller_Helper_Exception 
     * @return  boolean    
     */
    public function isCollection($data)
    {
        if (!is_array($data) && !is_object($data) && !($data instanceof Traversable)) {
            throw new Kebab_Controller_Helper_Exception('$data type must be array, object or instanceof Traversable.');
        }
        
        $retVal = true;        
        while ($retVal && list(, $value) = each($data)) {
            $retVal = is_array($value) || is_object($value) || ($value instanceof Traversable) ? true : false;
        }
        
        return $retVal;
    }
    
    /**
     * First check the array is Record array or Collection array,
     * If array is record array, convert record array to collection array
     * 
     * <code>
     * $a = array(
     *      'name' => 'Onur',
     *      'surname' => 'Ozkan'
     * );
     * 
     * $retVal = convertRecordtoCollection($a);
     * print_r($retVal);
     * 
     * //Output array(array('title'=>'onur', 'surname'=>'Ozkan'));
     * </code>
     *            
     * @param   array   $data
     * @throws  Kebab_Controller_Helper_Exception
     * @return  boolean 
     */
    public function convertRecordtoCollection($data)
    {
        if (!is_array($data)) {
            throw new Kebab_Controller_Helper_Exception('$data type must be array.');
        }
        
        $retVal[] = $data;
        
        return $retVal;
    }
    
    /**
     * Convert all type to array
     * 
     * @param  unknown $data
     * @return array 
     */
    public function convertArray($data)
    {
        if (!is_array($data) && !is_object($data) && !($data instanceof Traversable)) {
            $retVal[] = $data;
        } else {
            $retVal = $data;
        }
        
        return (array) $retVal;
    }
    
    /**
     * direct() : Stragry Design Pattern
     * 
     * @param   boolean $success
     * @return  System_Controller_Helper_Array
     */
    public function direct()
    {
        return $this;
    }
    
}
