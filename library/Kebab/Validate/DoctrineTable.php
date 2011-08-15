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
 * @package    Validate
 * @subpackage Doctrine_Table
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */

/**
 *
 *
 * @category   Kebab
 * @package    Validate
 * @subpackage Doctrine_Table
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */
class Kebab_Validate_DoctrineTable
{
    /**
     * @var Doctrine_Table
     */
    protected $_table;

    /**
     * @var Doctrine_Record|Doctrine_Collection
     */
    protected $_data;

    /**
     * @var array
     */
    protected $_errors = array();

    /**
     * @param $data
     * @param null $table
     */
    public function __construct($data, $table = null)
    {
        $this->_setTable($data, $table);
        $this->_data = $data;
    }

    /**
     * @param Doctrine_Record|Doctrine_Collection|Array $data
     * @param null|Doctrine_Table $table
     * @return Kebab_Validate_Doctrine_Table
     */
    protected function _setTable($data, $table = null)
    {
        if (is_null($table) && (($data instanceof Doctrine_Record) || ($data instanceof Doctrine_Collection))) {
            $this->_table = $data->getTable();
        }

        if (!is_null($table) && ($table instanceof Doctrine_Table)) {
            $this->_table = $table;
        }

        if (is_string($table) && !($table instanceof Doctrine_Table)) {
            $this->_table = Doctrine_Core::getTable($table);
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        $tableColumns = $this->_table->getColumns();
        $dirtyColumns = $this->_data->getModified();

        foreach ($dirtyColumns as $key => $value) {
            unset($dirtyColumns[$key]);
            $dirtyColumns[strtolower($key)] = $value;
        }

        foreach ($tableColumns as $columnName => $columnStructure) {
            if (array_key_exists($columnName, $dirtyColumns)) {

                $validatorChain = new Kebab_Validate();

                // Notnull
                if (array_key_exists('notnull', $columnStructure)) {
                    $validatorChain->addValidator(
                        new Zend_Validate_NotEmpty(array('string' => true, 'empty_array' => true, 'null' => true, 'space' => true))
                    );
                }

                // Email

                // Notblank

                // Nospace

                // Past

                // Future

                // Min Length
                if (array_key_exists('minlength', $columnStructure)) {
                    $validatorChain->addValidator(
                        new Zend_Validate_GreaterThan(array('min' => $columnStructure['minlength']))
                    );
                }

                // Country

                // Ip

                // HtmlColor

                // Range

                // Unique

                // Regex

                // Digits

                // Date

                // CC

                // Unsigned

                // Check All
                if (!$validatorChain->isValid($dirtyColumns[$columnName])) {
                    $translator = Zend_Registry::get('Zend_Translate');
                    Zend_Validate_Abstract::setDefaultTranslator($translator);
                    $this->_errors[$columnName] = $validatorChain->getMessages();
                }
                unset($validatorChain);
            }
        }

        if (count($this->_errors) > 0) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->_errors;
    }
}