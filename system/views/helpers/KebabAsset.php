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
 * System_View_Helper_KebabAsset
 * Manage view paths, types, loading sytles etc.
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
 * echo $this->kebabAsset('dev-tools/javascripts/dev-tools.js')->get();
 * echo $this->kebabAsset('system/default/stylesheets/layout.css')->get();
 * echo $this->kebabAsset('wallpapers/Life_by_Paco_Espinoza.jpg')->get();
 */
class System_View_Helper_KebabAsset extends Zend_View_Helper_Abstract
{
    protected $_config;
    protected $_asset;
    protected $_clearDebug = false;

    public function  __construct()
    {
        $this->_config = Zend_Registry::get('config')->kebab->assets;
    }
    
    public function kebabAsset($asset)
    {
        $this->_asset[2] = $asset;

        return $this;
    }

    public function theme($theme)
    {
        $path = $this->_config->themes->path;
        $this->_asset[1] = $path . '/' . $theme;
        return $this;
    }

    public function get($kebab = true)
    {
        $kebab = $kebab ? '/kebab' : null;
        
        $this->_asset[0] = $this->_root() . $kebab;
        
        return $this->_generate();
    }

    // ------------------------------Utils--------------------------------------

    protected function _root()
    {
        return $this->_config->loading->mode == 'local'
                ? WEB_PATH . '/' . $this->_config->path
                : $this->_config->loading->cdn->url . '/' . $this->_config->path;
    }

    protected function _debug($file)
    {
        // KBBTODO: Check is really file
        $file = explode('.', $file);

        // Apply this only css and js files
        $isCssOrJS = ($file[1] == 'js' || $file[1] == 'css');

        return ($this->_config->debug->enable && $isCssOrJS)
                    ? str_replace('.', '-debug.', implode('.', $file))
                    : implode('.', $file);
    }

    protected function _clearDebug($asset)
    {
        return str_replace('-debug', '', $asset);
    }

    protected function _version($seperator = '?')
    {
        $asset = $this->_clearDebug
                    ? $this->_clearDebug($this->_merge())
                    : $this->_merge();

        return $this->_config->loading->cacheControl->enable
                ? file_exists(realpath($asset))
                    ? $seperator . filemtime($asset)
                    : null
                : null;
    }

    protected function _merge()
    {
        ksort($this->_asset);
        return implode('/', $this->_asset);
    }
    
    protected function _replace($asset)
    {
        return trim(str_replace(WEB_PATH, BASE_URL, $asset));
    }

    protected function _generate()
    {
        $this->_asset[2] = $this->_debug($this->_asset[2]);

        $asset = $this->_replace($this->_merge() . $this->_version());

        unset($this->_asset);

        return $asset;
    }
}
