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
 * System_View_Helper_KebabAsset
 * Manage view paths, types, loading sytles etc.
 *
 * @category   Kebab
 * @package    View
 * @subpackage Helper
 * @author     Tayfun Öziş ERİKAN <tayfun.ozis.erikan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @since      1.5.x (kebab-reloaded)
 * @version    1.5.0
 *
 * @tutorial
 * echo $this->asset('dev-tools/javascripts/dev-tools.js')->get();
 * echo $this->asset('system/default/stylesheets/layout.css')->get();
 * echo $this->asset('wallpapers/vladstudio_underwater2.jpg')->get();
 */
class Kebab_View_Helper_Asset extends Zend_View_Helper_Abstract
{
    protected $_config;
    protected $_asset;
    protected $_clearDebug = false;

    public function  __construct()
    {
        $this->_config = Zend_Registry::get('config')->kebab;
    }
    
    public function asset($asset)
    {
        $this->_asset[2] = $asset;

        return $this;
    }

    public function distribution($distro = null)
    {
        $path = $this->_config->os->distributions->path;
        
        $distro = is_null($distro)
                ? $this->_config->os->distributions->current
                : $distro;
                
        $this->_asset[1] = $path . DIRECTORY_SEPARATOR . $distro;
        
        return $this;
    }
    
    public function theme($theme = null)
    {   
        $theme = is_null($theme)
               ? $this->_config->os->distributions->theme
               : $theme;

        $this->_asset[1] = isset($this->_asset[1]) 
                         ? $this->_asset[1] . DIRECTORY_SEPARATOR . 'themes' . DIRECTORY_SEPARATOR . $theme
                         : 'themes' . DIRECTORY_SEPARATOR . $theme;
            
        return $this;
    }

    public function get($kebab = true, $clearDebug = false)
    {
        $kebab = $kebab ? DIRECTORY_SEPARATOR . 'kebab' . DIRECTORY_SEPARATOR . 'system' : null;
        
        $this->_asset[0] = $this->_root() . $kebab;
        
        return $this->_generate();
    }

    public function getWeb()
    {
        $this->_asset[0] = WEB_PATH;

        return $this->_generate();
    }

    // ------------------------------Utils--------------------------------------

    protected function _root()
    {
        return $this->_config->assets->loading->mode == 'local'
                ? WEB_PATH . DIRECTORY_SEPARATOR . $this->_config->assets->path
                : $this->_config->assets->loading->cdn->url . DIRECTORY_SEPARATOR . $this->_config->assets->path;
    }

    protected function _debug($file)
    {
        // KBBTODO: Check is really file
        $pathInfo = pathinfo($file);
        $isCssOrJS = (in_array(@$pathInfo['extension'], array('js','css')));

        return ($this->_config->assets->debug->enable && $isCssOrJS)
                    ? $pathInfo['dirname'] . DIRECTORY_SEPARATOR . $pathInfo['filename'] . '-debug.' . @$pathInfo['extension']
                    : $file ;
    }

    protected function _clearDebug($asset)
    {
        return str_replace('-debug', '', $asset);
    }

    protected function _version($seperator = '?version=')
    {
        $asset = $this->_clearDebug
                    ? $this->_clearDebug($this->_merge())
                    : $this->_merge();

        return $this->_config->assets->loading->cacheControl->enable
                ? file_exists(realpath($asset))
                    ? $seperator . filemtime($asset)
                    : null
                : null;
    }

    protected function _merge()
    {
        ksort($this->_asset);
        return implode(DIRECTORY_SEPARATOR, $this->_asset);
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
