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
 * @package    PACKAGE
 * @subpackage SUB_PACKAGE
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
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
    protected $_mapping      = array();
    protected $_sortKey      = 'sort';
    protected $_sortValue    = 'id';
    protected $_dirKey       = 'dir';
    protected $_dirValue     = 'DESC';
    protected $_orderString;

    public function init()
    {
        $this->setRequest($this->getRequest());
        $this->setSortValue($this->_request->getParam($this->_sortKey, $this->_sortValue));
        $this->setDirValue($this->_request->getParam($this->_dirKey, $this->_dirValue));
        $this->setOrderString();
    }
    
    public function getMapping()     
    {
        return $this->_mapping;
    }

    public function setMapping($_mapping)
    {
        $this->_mapping = $_mapping;
    }

        
    public function setOrderString()
    {
        if (array_key_exists($this->_sortValue, $this->_mapping)) {
            $this->_orderString = $this->_mapping[$this->_orderString] . ' ' . $this->_dirValue; 
        } else {
            return false;
        }
    }
    
    public function getOrderString()
    {
        return $this->_orderString;
    }
    
    public function getSortValue()     
    {
        return $this->_sortValue;
    }

    public function setSortValue($_sortValue)
    {
        $this->_sortValue = $_sortValue;
    }

    public function getSortKey()
    {
        return $this->_sortKey;
    }

    public function setSortKey($_sortKey)
    {
        $this->_sortKey = $_sortKey;
    }

    public function getDirValue()
    {
        return $this->_dirValue;
    }

    public function setDirValue($_dirValue)
    {
        $this->_dirValue = $_dirValue;
    }

    public function getDirKey()
    {
        return $this->_dirKey;
    }

    public function setDirKey($_dirKey)
    {
        $this->_dirKey = $_dirKey;
    }
    
    /**
     * direct() : Stragry Design Pattern
     * 
     * @return  System_Controller_Helper_Pager
     */
    public function direct($mapping = false)
    {
        
        if ($mapping !== false && is_array($mapping)) {            
            $this->setMapping($mapping);
            $this->setOrderString();
            return $this->getOrderString();
        } 
        
        return false;        
    }    
    
    private function setRequest($_request)
    {
        $this->_request = $_request;
    }
}


