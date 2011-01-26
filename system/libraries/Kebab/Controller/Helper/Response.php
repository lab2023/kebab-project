<?php

class Kebab_Controller_Helper_Response extends Zend_Controller_Action_Helper_Abstract
{
    protected $_response = array();

    public function setSuccess($success)
    {
        if (is_bool($success)) {
            $this->_response['success'] = $success ? 'true' : 'false';
        } else {
            throw new Kebab_Controller_Helper_Exception('$success type must be boolean.');
        }

        return $this;
    }

    public function setResults($results, $addTotal = true, $name = 'results')
    {
        if (!is_array($results)
            && (!is_object($results) || !($results instanceof Traversable))
        ) {
            throw new Kebab_Controller_Helper_Exception('Only arrays and Traversable objects may be added to $response[\'data\']');
        }

        if ($addTotal) {
            $this->_response['total'] = count($results);
        }
        $this->_response[$name] = $results;

        return $this;
    }

    public function setErrors($errors, $name = 'errors')
    {
        if (!is_array($errors)
            && (!is_object($errors) || !($errors instanceof Traversable))
        ) {
            throw new Kebab_Controller_Helper_Exception('Only arrays and objects may be added to $response[\'errors\']');
        }

        foreach ($errors as $key => $value) {
            $this->_response[$name][$key] = Zend_Registry::get('translate')->_($value);
        }

        return $this;
    }

    public function getResponse()
    {
        $jsonHelper = new Zend_Controller_Action_Helper_Json();
        $jsonHelper->direct($this->_response);
    }

    public function direct($success = false)
    {
        $this->setSuccess($success);
        return $this;
    }

}