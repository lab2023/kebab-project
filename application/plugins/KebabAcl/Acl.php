<?php

/**
 * Kebab Framework
 *
 * LICENSE
 *
 * This source file is subject to the  Dual Licensing Model that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.kebab-project.com/licensing
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@lab2023.com so we can send you a copy immediately.
 *
 * @category   Kebab (kebab-reloaded)
 * @package    PACKAGE
 * @subpackage SUB_PACKAGE
 * @author     lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */
class Plugin_KebabAcl_Acl
{
    static public function getAdaptor()
    {
        if (file_exists( __DIR__ . '/config.ini')) {
            $config = new Zend_Config_Ini(
                    __DIR__ . '/config.ini',
                    APPLICATION_ENV
            );
        } else {
            throw new Zend_Exception($class . ' config file not found!');
        }
        
        return  'Plugin_KebabAcl_Adaptor_' .$config->adaptor;
    }

}
