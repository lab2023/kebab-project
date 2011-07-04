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
 * @package    Action Helper
 * @subpackage Filter
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @author     Ali BAKAN
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 * @since      1.0.0
 */


/**
 * System_Controller_Helper_Filter
 *
 * This helper add andWhere query to DQL from client filter. 
 *
 * @category   Kebab (kebab-reloaded)
 * @package    Controller
 * @subpackage Helper
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @author     Ali BAKAN
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @since      1.5.x (kebab-reloaded)
 * @version    1.5.0
 */
class Kebab_Controller_Helper_Filter extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * @var
     */
    private   $_request;

    /**
     * @var 
     */
    protected $filters;

    /**
     * @return void
     */
    public function init()
    {
        $this->_request = $this->getRequest();
        $this->filters = json_decode($this->_request->filter);
    }

    /**
     * @param  $query
     * @param  $mapping
     * @return $query
     */
    public function populateCriteria($query, $mapping)
    {
        // initial dql
        $query->where('TRUE = TRUE');

        // loop through filters sent by client
        if (is_array($this->filters)) {
            for ($i = 0; $i < count($this->filters); $i++) {
                $filter = $this->filters[$i];

                $field = $filter->field;
                $value = $filter->value;
                $compare = isset($filter->comparison) ? $filter->comparison : null;
                $filterType = $filter->type;

                switch ($filterType) {
                    case 'string' :
                        $query->andWhere("$mapping[$field] LIKE ?", "%$value%");
                        break;
                    case 'list' :
                        if (strstr($value, ',')) {
                            $fi = explode(',', $value);
                            for ($q = 0; $q < count($fi); $q++) {
                                $fi[$q] = "'" . $fi[$q] . "'";
                            }
                            
                            $query->andWhereIn("$mapping[$field] IN $fi");
                        } else {
                            $query->andWhere("$mapping[$field] = '$value'");
                        }
                        break;
                    case 'boolean' :
                        $query->andWhere("$mapping[$field] = '$value'");
                        break;
                    case 'numeric' :
                        switch ($compare) {
                            case 'eq' :
                                $query->andWhere("$mapping[$field] = $value"); 
                                break;
                            case 'lt' :
                                $query->andWhere("$mapping[$field] < $value");
                                break;
                            case 'gt' :
                                $query->andWhere("$mapping[$field] > $value");
                                break;
                        }
                        break;
                    case 'date' :
                        $date = date('Y-m-d', strtotime($value));
                        switch ($compare) {
                            case 'eq' :
                                $query->andWhere("$mapping[$field]  = $date");
                                break;
                            case 'lt' :
                                $query->andWhere("$mapping[$field]  < $date");
                                break;
                            case 'gt' :
                                $query->andWhere("$mapping[$field]  > $date");
                                break;
                        }
                        break;
                }
            }
        }

        return $query;
    }
    
    /**
     * direct() : Strategy Design Pattern
     */
    public function direct($query, $mapping)
    {
        return $this->populateCriteria($query, $mapping);
    }
}

