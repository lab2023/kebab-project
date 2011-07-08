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
 * @package    Controller
 * @subpackage Helper
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */


/**
 * System_Controller_Helper_KebabResponse
 *
 * <p>This controller helper aim is preparing response for the ajax request.
 * You can set the success, total, data, errors, notifications. Also you can add new
 * node to response object.</p>
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
class Kebab_Controller_Helper_Response extends Zend_Controller_Action_Helper_Abstract
{

    /**
     * Response array which will return
     *
     * @var type array
     */
    protected $_response = array();

    /**
     * setSuccess
     *
     * <p>Set the success node at $_response array.</p>
     *
     * @param  boolean $success
     * @throws Kebab_Controller_Helper_Exception
     * @return System_Controller_Helper_KebabResponse
     */
    public function setSuccess($success)
    {
        if (is_bool($success)) {
            $this->_response['success'] = $success ? true : false;
        } else {
            throw new Kebab_Controller_Helper_Exception('$success type must be boolean.');
        }

        return $this;
    }

    /**
     * addData
     *
     * <p>Add result data to $_result array</p>
     *
     * @param   Traversable   $data
     * @param   string        $name
     * @throws  Kebab_Controller_Helper_Exception
     * @return  System_Controller_Helper_KebabResponse
     */
    public function addData($data, $name = 'data')
    {
        if (!is_array($data)
            && (!is_object($data) || !($data instanceof Traversable))
        ) {
            throw new Kebab_Controller_Helper_Exception('Only arrays and Traversable objects may be added to $response[\'data\']');
        }
        $this->_response[$name] = $data;

        return $this;
    }

    /**
     * add Total
     *
     * <p>Add total value of the data $_result array</p>
     *
     * @param   integer   $data
     * @param   string    $name
     * @throws  Kebab_Controller_Helper_Exception
     * @return  System _Controller_Helper_KebabResponse
     */
    public function addTotal($data, $name = 'total')
    {
        if (is_nan($data)) {
            throw new Kebab_Controller_Helper_Exception('Only integers may be add to $response[\'total\']');
        }
        $this->_response[$name] = $data;

        return $this;
    }

    /**
     * setErrors
     *
     * <p>Set $_result[errors] elements</p>
     *
     * @param   traversable  $errors
     * @param   string       $name
     * @throws  Kebab_Controller_Helper_Exception
     * @return  System_Controller_Helper_KebabResponse
     */
    public function setErrors($errors, $name = 'errors')
    {
        if (!is_array($errors)
            && (!is_object($errors) || !($errors instanceof Traversable))
        ) {
            throw new Kebab_Controller_Helper_Exception('Only arrays and objects may be added to $response[\'errors\']');
        }

        foreach ($errors as $key => $value) {
            $this->_response[$name][$key] = Zend_Registry::get('Zend_Translate')->_($value);
        }

        return $this;
    }

    /**
     * addError
     *
     * <p>Add a new error to $_response[errors] elements</p>
     *
     * @param   string  $id
     * @param   string  $value
     * @throws  Kebab_Controller_Helper_Exception
     * @return  System_Controller_Helper_KebabResponse
     */
    public function addError($id, $value)
    {
        if (is_null($id) || is_null($value)) {
            throw new Kebab_Controller_Helper_Exception('$id and $value can\'t be  $response[\'errors\']');
        }

        $this->_response['errors'][] = array(
            'id' => $id,
            'msg' => Zend_Registry::get('Zend_Translate')->_($value)
        );

        return $this;
    }

    /**
     * addNotification
     *
     * <p>Add a new notification at $_response[notifications] array</p>
     *
     * @param   enum    $notificationType
     * @param   string  $message
     * @param   boolean $autoHide
     * @param   string  $group
     * @throws  Kebab_Controller_Helper_Exception
     * @return  System_Controller_Helper_KebabResponse
     */
    public function addNotification($notificationType, $message, $autoHide = true, $group = null)
    {
        $type = array('ALERT', 'CRIT', 'ERR', 'WARN', 'NOTICE', 'INFO');

        if (!in_array($notificationType, $type)) {
            throw new Kebab_Controller_Helper_Exception('Invalid notification type');
        }

        if (!is_string($message)) {
            throw new Kebab_Controller_Helper_Exception('Invalid notification string');
        }

        if (!is_bool($autoHide)) {
            throw new Kebab_Controller_Helper_Exception('Invalid autoHide type');
        }

        $notification = array(
            $notificationType,
            Zend_Registry::get('Zend_Translate')->_($message),
            $autoHide
        );

        if($group !== null) {
            $notification[] = (string) $group;
        }

        $this->_response['notifications'][] = $notification;

        return $this;
    }

    /**
     * add
     *
     * <p>Add a unknow element to $_response like $_response[$name] = $data</p>
     *
     * @param   string  $name
     * @param   mixed   $data
     * @throws  Kebab_Controller_Helper_Exception
     * @return  System_Controller_Helper_KebabResponse
     */
    public function add($name, $data)
    {
        if (!is_string($name)) {
            throw new Kebab_Controller_Helper_Exception('Invalid $name type');
        }

        $this->_response[$name] = $data;
        return $this;
    }

    /**
     * setHttpResponseCode
     *
     * @param  $code
     * @return Kebab_Controller_Helper_Response
     */
    public function setHttpResponseCode($code)
    {
        $response = parent::getResponse();
        $response->setHttpResponseCode($code);
    }

    /**
     * getResponse
     *
     * <p>convert the $_response array to json and set the application mine type json</p>
     */
    public function getResponse()
    {
        //KBBTODO We should write an adapter for array, xml in future
        $jsonHelper = new Zend_Controller_Action_Helper_Json();
        $jsonHelper->direct($this->_response);
    }

    /**
     * direct
     *
     * @param bool $success
     * @param int $code
     * @return Kebab_Controller_Helper_Response
     */
    public function direct($success = false, $code = 200)
    {
        $this->setSuccess($success);
        $this->setHttpResponseCode($code);
        return $this;
    }

}
