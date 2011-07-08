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
 * @package    View
 * @subpackage Helper
 * @author     Tayfun Öziş ERİKAN <tayfun.ozis.erikan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */

/**
 * Kebab_View_Helper_Language
 * Return user or system default language
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
class Kebab_View_Helper_Language extends Zend_View_Helper_Abstract
{
    protected $_config;
    
    public function  __construct()
    {
        $this->_config = Zend_Registry::get('config')->kebab;
    }
    
    public function language()
    {
        // Access to zend auth
        $auth = Zend_Auth::getInstance();

        // Get default language
        $defaultLanguage = $auth->hasIdentity()
                         ? $auth->getIdentity()->language
                         : Zend_Registry::get('config')->languages->default;

        $languagesFromConfig = Zend_Registry::get('config')->languages->translations->toArray();
        $languages = array();
        foreach ($languagesFromConfig as $k => $v) {
            $v['active'] = $defaultLanguage == $v['language'] ? true : false;
            $languages[] = $v;
        }

        return $languages;
    }
}
