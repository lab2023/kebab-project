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
 * @author     lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */

/**
 * System_Controller_Helper_GoogleTranslate
 * 
 * <p></p>
 *
 * @category   Kebab (kebab-reloaded)
 * @package    Controller
 * @subpackage Helper
 * @author     lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @since      1.5.x (kebab-reloaded)
 * @version    1.5.0
 */
class Kebab_Controller_Helper_GoogleTranslate extends Zend_Controller_Action_Helper_Abstract
{

    /**
     *
     * @var type string
     */
    protected $_appId = 'AIzaSyCWgufJLARAHa3ZDmPQgIFJjRcRztw3cI0';
   
    /**
     *
     * @var type string
     */
    protected $_translateUrl = 'https://www.googleapis.com/language/translate/v2';
    
    /**
     *
     * @var type array
     */
    protected $_queryParameter = array('key', 'source', 'target', 'q');
    
    /**
     *
     * @var type array
     */
    protected $_languageReferenceCode = array('af', 'sq', 'ar', 'be', 'bg', 'ca', 'zh-CN', 'zh-TW', 'hr', 'cs', 'da'
        , 'nl', 'en', 'et', 'tl', 'fi', 'fr', 'gl', 'de', 'el', 'ht', 'iw', 'hi', 'hu', 'is', 'id', 'ga', 'it', 'ja'
        , 'lv', 'lt', 'mk', 'ms', 'mt', 'no', 'fa', 'pl', 'pt', 'ro', 'ru', 'sr', 'sk', 'sl', 'es', 'sw', 'sv', 'th'
        , 'tr', 'uk', 'vi', 'cy', 'yi');

    /**
     * translate()
     * 
     * <p>As an Array from the text(s) desired language and translates
     * array ('original text'=> 'translated text') in the form of returns.</p>
     * 
     * @param string or NULL $originalTextLanguage
     * @param string $translatedTextLanguage
     * @param array $text
     * @throws Kebab_Controller_Helper_Exception
     * @return array $translatedTextAndOriginalText
     */
    public function tranlate($text, $translatedTextLanguage, $originalTextLanguage = NULL)
    {
        $originalTextLanguage = ($originalTextLanguage === NULL) 
                              ? NULL 
                              : $this->_queryParameter[1] . '=' . $originalTextLanguage . '&';
        $newText = '';
        
        if (is_array($text)) {
            foreach ($text as $value) {
                $this->newText .= $this->_queryParameter[3] . '=' . $value . '&';
            }
        } else {
            throw new Kebab_Controller_Helper_Exception('$text isn\'t array');
        }
        
        if (!in_array($originalTextLanguage, $this->_languageReferenceCode) AND !is_null($originalTextLanguage)) {
            throw new Kebab_Controller_Helper_Exception(
                '$originalTextLanguage is not found in $inlanguageReferenceCode or not null');
        }
        
        if (!in_array($translatedTextLanguage, $this->_languageReferenceCode)) {
            throw new Kebab_Controller_Helper_Exception(
                '$translatedTextLanguage is not found in $inlanguageReferenceCode');
        }
        
        $translatedTextContents = file_get_contents($this->_translateUrl . '?' . $this->_queryParameter[0] . '='
            . $this->_appId . '&' . $this->newText . $originalTextLanguage . $this->_queryParameter[2] . '='
            . $translatedTextLanguage);
        $translatedTextArray = Zend_Json::decode($translatedTextContents);
        $returnArray = array();
        foreach ($translatedTextArray['data']['translations'] as $key => $value) {
            array_push($returnArray, $value['translatedText']);
        }
        return $translatedTextAndOriginalText = array_combine((array) $text, $returnArray);
    }

    /**
     * direct() : Stragry Design Pattern
     * 
     * @param string or NULL $originalTextLanguage
     * @param string $translatedTextLanguage
     * @param array $text
     * @return System_Controller_Helper_GoogleTranslate
     */
    public function direct($text, $translatedTextLanguage, $originalTextLanguage = NULL)
    {
        $this->tranlate($originalTextLanguage, $translatedTextLanguage, $text);
        return $this;
    }

}
