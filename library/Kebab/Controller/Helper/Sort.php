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
 * @package    Librar
 * @subpackage Helper
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>s
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @since      1.5.x (kebab-reloaded)
 * @version    1.5.0
 */

/**
 * System_Controller_Helper_Sort
 *
 * @category   Kebab
 * @package    Library
 * @subpackage Helper
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>s
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @since      1.5.x (kebab-reloaded)
 * @version    1.5.0
 */
class Kebab_Controller_Helper_Sort extends Zend_Controller_Action_Helper_Abstract
{

    /**
     * @var Zend_Controller_Request_Abstract
     */
    private $_request;

    /**
     * @var string
     */
    protected $_sort = 'id';

    /**
     * @var string
     */
    protected $_dir = 'DESC';

    /**
     * @var array
     */
    protected $_mapping = array();

    /**
     * @var
     */
    protected $_sortString;

    /**
     * @return void
     */
    public function init()
    {
        $this->_request = $this->getRequest();
        $this->setSort($this->_request->getParam('sort', $this->_sort));
        $this->setDir($this->_request->getParam('dir', $this->_dir));
    }

    /**
     * @return string
     */
    public function getSortString()     
    {
        return $this->_sortString;
    }

    /**
     * @throws Kebab_Controller_Helper_Exception
     * @param bool $sortString
     * @return Kebab_Controller_Helper_Sort
     */
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

    /**
     * @return array
     */
    public function getMapping()     
    {
        return $this->_mapping;
    }

    /**
     * @param $_mapping
     * @return void
     */
    public function setMapping($_mapping)
    {
        $this->_mapping = $_mapping;
    }

    /**
     * @return string
     */
    public function getSort()     
    {
        return $this->_sortValue;
    }

    /**
     * @param $_sortValue
     * @return void
     */
    public function setSort($_sortValue)
    {
        $this->_sortValue = $_sortValue;
    }

    /**
     * @return string
     */
    public function getDir()
    {
        return $this->_dirValue;
    }

    /**
     * @param $_dirValue
     * @return void
     */
    public function setDir($_dirValue)
    {
        $this->_dirValue = $_dirValue;
    }
    
    /**
     * @throws Kebab_Controller_Helper_Exception
     * @param $mapping
     * @return string
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


