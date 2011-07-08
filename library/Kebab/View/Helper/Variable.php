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
 * @package    View
 * @subpackage Helper
 * @author     Tayfun Öziş ERİKAN <tayfun.ozis.erikan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */

/**
 * Kebab_View_Helper_Variable
 * Return and convert to var any specific config item
 *
 * @category   Kebab
 * @package    View
 * @subpackage Helper
 * @author     Tayfun Öziş ERİKAN <tayfun.ozis.erikan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @since      1.5.x (kebab-reloaded)
 * @version    1.5.0
 */
class Kebab_View_Helper_Variable extends Zend_View_Helper_Abstract
{
    protected $_config;
    
    public function  __construct()
    {
        $this->_config = Zend_Registry::get('config')->kebab;
    }
    
    public function variable($var = null)
    {
        if (!is_null($var)) {
            
            $vars = new stdClass();
            $vars->os = $this->_config->os;
            $vars->assets = $this->_config->assets;
            $vars->mapping = $this->_config->mapping; // KBBTODO what is this ?

            return $vars->$var;
        } else {
            return $var;
        }
    }
}
