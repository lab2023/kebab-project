<?php if ( ! defined('BASE_PATH')) exit('No direct script access allowed');

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
 * Kebab System Category Manager
 *
 * @category   Kebab (kebab-reloaded)
 * @package    Services
 * @subpackage Category
 * @author	   Onur Özgür ÖZKAN
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */
class CategoryController extends Kebab_Rest_Controller
{

    public function indexAction()
    {
        // Request Helper
        $requestHelper = $this->_helper->request();
        $defaultLanguage = $auth->getIdentity()->language;
        
        // Doctrine
        $query = Doctrine_Query::create()
                    ->select('c.*, IF((c.rgt - c.lft > 1), "true", "false") as leaf, t.title as title, t.description as description')
                    ->from('Model_Entity_Category c')
                    ->innerJoin('c.Translation t')
                    ->where('t.lang = ? AND c.id = ?', array($defaultLanguage, 2))
                    ->fetchOne();
        $retVal = $query->getNode()->getChildren()->toArray();
        
        // Response
        $this->getResponse()
            ->setHttpResponseCode(200)
            ->appendBody(
                $this->_helper->response()
                    ->setSuccess(true)
                    ->addTotal(count($retVal))
                    ->addData($retVal)
                    ->getResponse()
            );
    }
    
    public function getAction(){}
    public function postAction(){}
    public function putAction(){}
    public function deleteAction(){}
}

