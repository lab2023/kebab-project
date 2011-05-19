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
 * @package    Controller
 * @subpackage Helper
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>s
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @since      1.5.x (kebab-reloaded)
 * @version    1.5.0
 */

/**
 * System_Controller_Helper_Sort
 *
 * @category   Kebab (kebab-reloaded)
 * @package    Controller
 * @subpackage Helper
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>s
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @since      1.5.x (kebab-reloaded)
 * @version    1.5.0
 */
class Kebab_Controller_Helper_Sort extends Zend_Controller_Action_Helper_Abstract
{

    private   $_request;
    protected $_sort         = 'id';
    protected $_dir          = 'DESC';
    protected $_mapping      = array();
    protected $_sortString;

    public function init()
    {
        $this->_request = $this->getRequest();
        $this->setSort($this->_request->getParam('sort', $this->_sort));
        $this->setDir($this->_request->getParam('dir', $this->_dir));
    }
    
    public function getSortString()     
    {
        return $this->_sortString;
    }

    public function setSortString($sortString = false)
    {
        if ($sortString !== false && is_string($sortString)) {
            $this->_sortString = $sortString;
        }
        
        if (array_key_exists($this->getSort(), $this->_mapping)){
            $this->_sortString = $this->_mapping[$this->getSort()] . ' ' . $this->getDir();
        } else {
            throw new Kebab_Controller_Helper_Exception('Sort value isn\'t at mapping');
        }
        
        return $this;
    }

    public function getMapping()     
    {
        return $this->_mapping;
    }

    public function setMapping($_mapping)
    {
        $this->_mapping = $_mapping;
    }

            
    public function getSort()     
    {
        return $this->_sortValue;
    }

    public function setSort($_sortValue)
    {
        $this->_sortValue = $_sortValue;
    }

    public function getDir()
    {
        return $this->_dirValue;
    }

    public function setDir($_dirValue)
    {
        $this->_dirValue = $_dirValue;
    }
    
    /**
     * direct()
     * 
     * @return  System_Controller_Helper_Pager
     */
    public function direct($mapping)
    {
        // Check that mapping type is array
        if (!is_array($mapping)) {
            throw new Kebab_Controller_Helper_Exception('Mapping type should be array.');
        }
        
        $this->setMapping($mapping);
        $this->setSortString();
        return $this->getSortString();        
    }    
}


