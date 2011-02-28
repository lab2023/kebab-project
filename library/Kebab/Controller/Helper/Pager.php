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
 * @author     onur.ozgur.ozkan@lab2023.com <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */

/**
 * System_Controller_Helper_Pager
 * 
 * <p>This controller helper helps Doctrine_Pager and ExtJS Grid Companent. Normally client sents $start, $limit, 
 * $sort, $dir but Doctrine_Pager needs $resultsPerPage and $currentPage.</p>
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
class Kebab_Controller_Helper_Pager extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * Start value of list
     * @var integer
     */
    protected $_start = 0;
    
    /**
     * Offset of list
     * @var integer
     */
    protected $_limit = 25;
    
    /**
     * Default column to sort
     * @var string
     */
    protected $_sort  = 'id';
    
    /**
     * Default 'DESC' 
     * @var string
     */
    protected $_dir   = 'DESC';
    
    /**
     *
     * @var integer
     */
    protected $_resultsPerPage;
    
    /**
     *
     * @var integer
     */
    protected $_currentPage;
    
    /**
     *  Set start, limit, sort, dir, resultsPerPage andcurrentPage for Doctrine_Pager
     */
    public function init()
    {
        $this->setStart();
        $this->setLimit();
        $this->setSort();
        $this->setDir();
        $this->setResultsPerPage();
        $this->setCurrentPage();
    }

    /**
     * set start
     */
    public function setStart()
    {
        $request = $this->getRequest();
        $this->_start = $request->getParam('start', $this->_start);            
    }
    
    /**
     * get start
     * @return integer 
     */
    public function getStart()
    {
        return $this->_start;
    }
    
    /**
     * set limit
     */
    public function setLimit()
    {
        $request = $this->getRequest();
        $this->_limit = $request->getParam('limit', $this->_limit);            
    }
    
    /**
     * get limit
     * @return integer 
     */
    public function getLimit()
    {
        return $this->_limit;
    }
    
    /**
     * set sort
     */
    public function setSort()
    {
        $request = $this->getRequest();
        $this->_sort = $request->getParam('sort', $this->_sort);
    }
    
    /**
     * get sort
     * @return string
     */
    public function getSort()
    {
        return $this->_sort;
    }
    
    /**
     * set dir
     */
    public function setDir()
    {
        $request = $this->getRequest();
        $this->_dir = $request->getParam('dir', $this->_dir);            
    }
    
    /**
     * get dir
     * @return string
     */
    public function getDir() 
    {
        return $this->_dir;
    }
    
    /**
     * set resultsPerPage. This value is equal to limit
     */
    public function setResultsPerPage()
    {
        $this->_resultsPerPage = $this->_limit;           
    }
    
    /**
     * get resultsPerPage
     * @return integer
     */
    public function getResultsPerPage()
    {
        return $this->_resultsPerPage;
    }
   
    /**
     * set currentPage
     */
    public function setCurrentPage()
    {
        $this->_currentPage = ($this->_start === 0) ? 1 : ($this->_start / $this->_resultsPerPage);         
    }
    
    /**
     * get currentPage
     * @return integer
     */
    public function getCurrentPage()
    {
        return $this->_currentPage;
    }
    
    /**
     * direct() : Stragry Design Pattern
     * 
     * @return  System_Controller_Helper_Pager
     */
    public function direct()
    {
        return $this;
    }
    
}