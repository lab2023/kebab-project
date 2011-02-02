<?php

if (!defined('BASE_PATH'))
    exit('No direct script access allowed');
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
 * @author	   lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */

/**
 * System_View_Helper_KebabAssetVendor
 * Manage vendor assets on kebab style
 *
 * @category   Kebab (kebab-reloaded)
 * @package    View
 * @subpackage Helper
 * @author	   lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @since      1.5.x (kebab-reloaded)
 * @version    1.5.0
 *
 * @tutorial
 * echo $this->kebabAssetVendor('adapter/ext/ext-base.js')->get('extjs');
 * echo $this->kebabAssetVendor('resources/css/ext-all.css')->get('extjs', true);
 */
class Zend_View_Helper_KebabAssetVendor extends Zend_View_Helper_KebabAsset
{
    protected $_config;
    protected $_asset;
    protected $_clearDebug = false;

    public function kebabAssetVendor($asset)
    {
        $this->_asset[2] = $asset;
        return $this;
    }

    public function get($vendor, $clearDebug = false)
    {
        $config = $this->_config->vendors;

        $root = $config->$vendor->path;
        $cdn  = $config->$vendor->cdn 
                    ? $config->$vendor->cdn
                    : $this->_config->loading->cdn->url . '/' . $this->_config->path . '/vendors';

        $this->_asset[0] = $this->_root($root, $cdn);

        return $this->_clearDebug = $clearDebug
                ? $this->_clearDebug($this->_generate())
                : $this->_generate();
    }

    // ------------------------------Utils--------------------------------------

    protected function _root($root, $cdn)
    {
        return $this->_config->loading->mode == 'local'
                ? WEB_PATH . '/' . $this->_config->path . '/vendors/' . $root
                : $cdn . '/' . $root;
    }
}