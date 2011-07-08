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
 * @category   Kebab
 * @package    Controller
 * @subpackage Helper
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @since      1.5.x (kebab-reloaded)
 * @version    1.5.0
 */

/**
 * System_Controller_Helper_Search
 *
 * @category   Kebab
 * @package    Controller
 * @subpackage Helper
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @since      1.5.x (kebab-reloaded)
 * @version    1.5.0
 */
class Kebab_Controller_Helper_Search extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * @var object Zend_Request_Object
     */
    private $_request;

    /**
     * @var string default false
     */
    protected $_query = false;

    /**
     * @var Doctrine table default false
     */
    protected $_table = false;

    /**
     * @return void
     */
    public function init()
    {
        $this->_request = $this->getRequest();
        $this->setQuery($this->_request->getParam('query', $this->_query));

    }

    /**
     * @return bool|string
     */
    public function getQuery()
    {
        return $this->_query;
    }

    /**
     * @param  $_query
     * @return Kebab_Controller_Helper_Search
     */
    public function setQuery($_query)
    {
        $_query = strtolower(Doctrine_Inflector::unaccent($_query));
        if ($_query !== false && is_string($_query)) {
            $this->_query = '*' . $_query . '*';
        }

        return $this;
    }

    /**
     * @return bool|Doctrine
     */
    public function getTable()
    {
        return $this->_table;
    }

    /**
     * @param  $_table
     * @return void
     */
    public function setTable($_table)
    {
        $this->_table = $_table;
    }

    /**
     * @param  $_table
     * @param bool $i18n
     * @return array
     */
    public function direct($_table, $i18n = false)
    {
        $this->setTable($_table);

        $table = $i18n ? // if i18n is true
                Doctrine::getTable("$this->_table")
                        ->getTemplate('Doctrine_Template_I18n')
                        ->getPlugin()
                        ->getTable()
                        ->getGenerator('Doctrine_Search')
                        ->search($this->_query)
                : Doctrine_Core::getTable("$this->_table")->search($this->_query);

        $ids = array();
        foreach ($table as $result) {
            $ids[] = $result['id'];
        }

        return $ids;
    }
}

