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
 * @category   
 * @package    
 * @subpackage 
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */
 
/**
 * 
 *
 * @category   
 * @package    
 * @subpackage 
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */
class Kebab_Validate_Unique extends Zend_Validate_Abstract
{
    /**
     * 
     */
    const UNIQUE = 'Unique';

    /**
     * @var array
     */
    protected $_messageTemplates = array(
        self::UNIQUE => "Value is not a unique"
    );

    /**
     * @var
     */
    protected $_column;

    /**
     * @var 
     */
    protected $_table;

    /**
     * @param $column
     * @param $data
     */
    public function __construct($column, $data)
    {
        /**
         * Column name
         */
        $this->_column = $column;

        /**
         * Doctrine Record
         */
        $this->_table = $data;
    }

    /**
     * @param $value
     * @return bool
     */
    public function isValid($value)
    {
        $this->_setValue($value);
        $tableName = get_class($this->_table);
        $query = Doctrine_Query::create()->from("$tableName")->where("$this->_column = ?", $value);

        // Check primary key if the record is not new
        $state = $this->_table->state();
        if ( ! ($state == Doctrine_Record::STATE_TDIRTY || $state == Doctrine_Record::STATE_TCLEAN)) {
            foreach ((array) $this->_table->getIdentifierColumnNames() as $pk) {
                if (!is_null($this->_table->$pk)) {
                    $query->andWhere("$pk != ?", $this->_table->$pk);
                }
            }
        }

        if (!is_array($query->execute())) {
            $this->_error(self::UNIQUE);
            return false;
        }

        return true;
    }
}
