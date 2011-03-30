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
 * @author     lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */

/**
 * System_Controller_Helper_Pagination
 *
 * @category   Kebab (kebab-reloaded)
 * @package    Controller
 * @subpackage Helper
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @since      1.5.x (kebab-reloaded)
 * @version    1.5.0
 */
class Kebab_Controller_Helper_Pagination extends Zend_Controller_Action_Helper_Abstract
{
    protected $_request;
    protected $_startKey = 'start';
    protected $_startValue = 0;
    protected $_limitKey = 'limit';
    protected $_limitValue = 25;
    protected $_resultsPerPage;
    protected $_currentPage;
    protected $_pager;
    
    public function init()
    {
        $this->setRequest($this->getRequest());
        $this->setStartValue($this->_request->getParam($this->_startKey, $this->_startValue));
        $this->setLimitValue($this->_request->getParam($this->_limitKey, $this->_limitValue));
        $this->setResultsPerPage($this->_limitValue);
        $this->setCurrentPage();     
    }
    
    public function getStartKey()     
    {
        return $this->_startKey;
    }

    public function setStartKey($_startKey)
    {
        $this->_startKey = $_startKey;
    }

    public function getStartValue()
    {
        return $this->_startValue;
    }

    public function setStartValue($_startValue)
    {
        $this->_startValue = $_startValue;
    }

    public function getLimitKey()
    {
        return $this->_limitKey;
    }

    public function setLimitKey($_limitKey)
    {
        $this->_limitKey = $_limitKey;
    }

    public function getLimitValue()
    {
        return $this->_limitValue;
    }

    public function setLimitValue($_limitValue)
    {
        $this->_limitValue = $_limitValue;
    }

    public function getResultsPerPage()
    {
        return (int) $this->_resultsPerPage;
    }

    public function setResultsPerPage($_resultsPerPage)
    {
        $this->_resultsPerPage = $_resultsPerPage;
    }

    public function getCurrentPage()
    {
        return $this->_currentPage;
    }

    public function setCurrentPage()
    {
        if ($this->_startValue < $this->_limitValue) {
            $this->_currentPage = 1;
        } elseif ($this->_startValue === $this->_limitValue) {
            $this->_currentPage = 2;
        } else {
            $this->_currentPage = ceil($this->_startValue / $this->_resultsPerPage);
        }
    }    

    public function getPager()     
    {
        return $this->_pager;
    }

    public function setPager($query)
    {
        $this->_pager = new Doctrine_Pager(
            $query,
            $this->getCurrentPage(),
            $this->getResultsPerPage()            
        );
    }

        /**
     * direct() : Stragry Design Pattern
     * 
     * @return  System_Controller_Helper_Pager
     */
    public function direct($query = null)
    {
        if (is_null($query)) {
            return $this;
        }
        
        $this->setPager($query);
        return $this->_pager;
    }
    
    private function setRequest($_request)
    {
        $this->_request = $_request;
    }
}
