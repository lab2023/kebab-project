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
 * @package    Controller Helper
 * @subpackage Response Controller Helper
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @see        http://zend-framework-community.634137.n4.nabble.com/Getting-JSON-POST-data-from-Zend-td1462014.html
 * @version    1.5.0
 */

/**
 * System_Controller_Helper_Param
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
class Kebab_Controller_Helper_Param extends Zend_Controller_Action_Helper_Abstract
{

    /**
     * @var array Parameters detected in raw content body 
     */
    protected $_bodyParams = array();
    protected $_decodeJsonFieldTypeArray = true; //true is array, false is object

    /**
     * Do detection of content type, and retrieve parameters from raw body if present 
     * 
     * @return void 
     */

    public function init()
    {
        $request = $this->getRequest();
        $rawBody = $request->getRawBody();

        if (!$rawBody) {
            return;
        }

        if ($request->isPut() || $request->isDelete()) {
            parse_str($rawBody, $params);
            $this->setBodyParams($params);
        }
    }

    /**
     * Set body params 
     * 
     * @param  array $params 
     * @return Scrummer_Controller_Action 
     */
    public function setBodyParams(array $params)
    {
        $this->_bodyParams = $params;
        return $this;
    }

    /**
     * Retrieve body parameters 
     * 
     * @return array 
     */
    public function getBodyParams()
    {
        return $this->_bodyParams;
    }

    /**
     * Get body parameter 
     * 
     * @param  string $name 
     * @return mixed 
     */
    public function getBodyParam($name)
    {
        if ($this->hasBodyParam($name)) {
            return $this->_bodyParams[$name];
        }
        return null;
    }

    /**
     * Is the given body parameter set? 
     * 
     * @param  string $name 
     * @return bool 
     */
    public function hasBodyParam($name)
    {
        if (isset($this->_bodyParams[$name])) {
            return true;
        }
        return false;
    }

    /**
     * Do we have any body parameters? 
     * 
     * @return bool 
     */
    public function hasBodyParams()
    {
        if (!empty($this->_bodyParams)) {
            return true;
        }
        return false;
    }

    /**
     * Get submit parameters 
     * 
     * @return array 
     */
    public function getSubmitParams($decodeJsonFields)
    {
        $retVal = array();
        if ($this->hasBodyParams()) {
            $retVal = $this->getBodyParams();
        } else {
            $retVal = $this->getRequest()->getParams();
        }

        if ($decodeJsonFields) {
            return $this->decodeJsonFields($retVal);
        }
        return $retVal;
    }

    /**
     *
     * @param array | string
     */
    public function decodeJsonFields($params)
    {
        if (!is_array($params)) {
            throw new Kebab_Controller_Helper_Exception('$params should be array type');
        }

        $retVal = array();
        foreach ($params as $k => $v) {
            if ($this->_decodeJsonFieldTypeArray) {
                $retVal[$k] = is_null(json_decode($v)) ? $v : json_decode($v, true);
            } else {
                $retVal[$k] = is_null(json_decode($v)) ? $v : json_decode($v);
            }
        }

        return $retVal;
    }

    /**
     * direct() : Stragry Design Pattern
     * 
     */
    public function direct($decodeJsonFields = true)
    {
        return $this->getSubmitParams($decodeJsonFields);
    }

}

