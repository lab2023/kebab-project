<?php
/**
 * Kebab Project
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
 * @category   Kebab
 * @package    Library
 * @subpackage Helper
 * @author     lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */

/**
 * System_Controller_Helper_Pagination
 *
 * @category   Kebab
 * @package    Helper
 * @subpackage Library
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @since      1.5.x (kebab-reloaded)
 * @version    1.5.0
 */
class Kebab_Controller_Helper_Pagination extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * @var 
     */
    protected $_request;

    /**
     * @var string
     */
    protected $_startKey = 'start';

    /**
     * @var int
     */
    protected $_startValue = 0;

    /**
     * @var string
     */
    protected $_limitKey = 'limit';

    /**
     * @var int
     */
    protected $_limitValue = 25;

    /**
     * @var
     */
    protected $_resultsPerPage;

    /**
     * @var
     */
    protected $_currentPage;

    /**
     * @var
     */
    protected $_pager;

    /**
     * @return void
     */
    public function init()
    {
        $this->setRequest($this->getRequest());
        $this->setStartValue($this->_request->getParam($this->_startKey, $this->_startValue));
        $this->setLimitValue($this->_request->getParam($this->_limitKey, $this->_limitValue));
        $this->setResultsPerPage($this->_limitValue);
        $this->setCurrentPage();     
    }

    /**
     * @return string
     */
    public function getStartKey()     
    {
        return $this->_startKey;
    }

    /**
     * @param $_startKey
     * @return void
     */
    public function setStartKey($_startKey)
    {
        $this->_startKey = $_startKey;
    }

    /**
     * @return int
     */
    public function getStartValue()
    {
        return $this->_startValue;
    }

    /**
     * @param $_startValue
     * @return void
     */
    public function setStartValue($_startValue)
    {
        $this->_startValue = $_startValue;
    }

    /**
     * @return string
     */
    public function getLimitKey()
    {
        return $this->_limitKey;
    }

    /**
     * @param $_limitKey
     * @return void
     */
    public function setLimitKey($_limitKey)
    {
        $this->_limitKey = $_limitKey;
    }

    /**
     * @return int
     */
    public function getLimitValue()
    {
        return $this->_limitValue;
    }

    /**
     * @param $_limitValue
     * @return void
     */
    public function setLimitValue($_limitValue)
    {
        $this->_limitValue = $_limitValue;
    }

    /**
     * @return int
     */
    public function getResultsPerPage()
    {
        return (int) $this->_resultsPerPage;
    }

    /**
     * @param $_resultsPerPage
     * @return void
     */
    public function setResultsPerPage($_resultsPerPage)
    {
        $this->_resultsPerPage = $_resultsPerPage;
    }

    /**
     * @return 
     */
    public function getCurrentPage()
    {
        return $this->_currentPage;
    }

    /**
     * @return void
     */
    public function setCurrentPage()
    {
        if ($this->_startValue < $this->_resultsPerPage) {
            $this->_currentPage = 1;
        } elseif ($this->_startValue === $this->_resultsPerPage) {
            $this->_currentPage = 2;
        } else {
            $this->_currentPage = ceil($this->_startValue / $this->_resultsPerPage) + 1;
        }
    }

    /**
     * @return
     */
    public function getPager()     
    {
        return $this->_pager;
    }

    /**
     * @param $query
     * @return void
     */
    public function setPager($query)
    {
        $this->_pager = new Doctrine_Pager(
            $query,
            $this->getCurrentPage(),
            $this->getResultsPerPage()            
        );
    }

    /**
     * @param null $query
     * @return Kebab_Controller_Helper_Pagination
     */
    public function direct($query = null)
    {
        if (is_null($query)) {
            return $this;
        }
        
        $this->setPager($query);
        return $this->_pager;
    }

    /**
     * @param $_request
     * @return void
     */
    private function setRequest($_request)
    {
        $this->_request = $_request;
    }
}
