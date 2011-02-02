<?php
/**
 * 
 * Imind Library
 *
 * @category   Imind
 * @package    Imind_Profiler
 * @copyright  Copyright (c) iMind Ltd. (http://www.imind.hu)
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

/**
 * @see Doctrine_Connection_Profiler
 */
require_once 'Doctrine/Connection/Profiler.php';

/**
 * @see Zend_Wildfire_Plugin_FirePhp
 */
require_once 'Zend/Wildfire/Plugin/FirePhp.php';

/**
 * @see Zend_Wildfire_Plugin_FirePhp_TableMessage
 */
require_once 'Zend/Wildfire/Plugin/FirePhp/TableMessage.php';

/**
 * @category   Imind
 * @package    Imind_Profiler
 * @copyright  Copyright (c) iMind Ltd. (http://www.imind.hu)
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Imind_Profiler_Doctrine_Firebug extends Doctrine_Connection_Profiler {

    /**
     * @param array $_events an array containing all listened events
     */
    private $_events     = array();
    
    /**
     * The original label for this profiler.
     * @var string
     */
    protected $_label = null;
  
    /**
     * The message envelope holding the profiling summary
     * @var Zend_Wildfire_Plugin_FirePhp_TableMessage
     */
    protected $_message = null;
  
    /**
     * The total time taken for all profiled queries.
     * @var float
     */
    protected $_totalElapsedTime = 0;
  
    /**
     * Constructor
     *
     * @param string $label OPTIONAL Label for the profiling info.
     * @return void
     */
    public function __construct($label = null)
    {
        $this->_label = $label;
        if(!$this->_label) {
            $this->_label = 'Imind_Profiler_Doctrine_Firebug';
        }
    
        if (!$this->_message) {
            $this->_message = new Zend_Wildfire_Plugin_FirePhp_TableMessage($this->_label);
            $this->_message->setBuffered(true);
            $this->_message->setHeader(array('Name','Time','Event','Parameters'));
            $this->_message->setDestroy(true);
            Zend_Wildfire_Plugin_FirePhp::getInstance()->send($this->_message);
        }
    }
    
    /**
     * method overloader
     * this method is used for invoking different listeners, for the full
     * list of availible listeners, see Doctrine_EventListener
     *
     * @param string $m     the name of the method
     * @param array $a      method arguments
     * @see Doctrine_EventListener
     * @return boolean
     */
    public function __call($methodName, $arguments) {
        if (!($arguments[0] instanceof Doctrine_Event)) {
            throw new Doctrine_Connection_Profiler_Exception("Couldn't listen event. Event should be an instance of Doctrine_Event.");
        }
        if (substr($methodName, 0, 3) == 'pre') {
            $arguments[0]->start();
            if (!in_array($arguments[0], $this->_events, true)) { 
                $this->_events[] = $arguments[0]; 
            }
        } else {
            // we need after-event listeners
            $arguments[0]->end();
            $this->_message->setDestroy(false);
            $this->_totalElapsedTime += $arguments[0]->getElapsedSecs();
            $row = array($arguments[0]->getName(),
                        round($arguments[0]->getElapsedSecs(),5),
                        $arguments[0]->getQuery(),
                        ($params=$arguments[0]->getParams())?$params:null);
            $this->_message->addRow($row);
            $this->updateMessageLabel();                
        }

    }
    
    /**
     * Update the label of the message holding the profile info.
     * 
     * @return void
     */
    protected function updateMessageLabel()
    {
        if (!$this->_message) {
            return;
        }
        $this->_message->setLabel($this->_label . ' (' . round($this->_totalElapsedTime,5) . ' sec)');
    }
}
