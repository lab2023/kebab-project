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
 * @package    System
 * @subpackage Controllers
 * @author     lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */


/**
 * Feedback_Feedback
 *
 * @category   Kebab (kebab-reloaded)
 * @package    Administration
 * @subpackage Controllers
 * @author     lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/licensing
 * @version    1.5.0
 */
class System_BackupController extends Kebab_Rest_Controller
{
    public function indexAction()
    {
        $dir = new DirectoryIterator(APPLICATION_PATH . '/variables/backups');
        foreach ($dir as $fileinfo) {
            if ($fileinfo->getFilename() != '.') {
                if ($fileinfo->getFilename() != '..') {
                    $files[] = $fileinfo->getFilename();
                }
            }
        }

        $this->getResponse()
                ->setHttpResponseCode(200)
                ->appendBody(
            $this->_helper->response()
                    ->setSuccess(true)
                    ->addData($files)
                    ->getResponse()
        );
    }

    public function getAction()
    {
          $params = $this->_helper->param();
          $fileName = $params['fileName'];
          $str = file_get_contents(APPLICATION_PATH . '/variables/backups'.'/'.$fileName);

          $this->_response->clearBody();
          $this->_response->clearHeaders();
          $this->_response
            ->setHeader('Content-Type', 'image/jpeg')
            ->setHeader('Content-Disposition', 'attachment; filename="2011-05-05-16-52-13.tar.gz"')
            ->setHeader("Connection", "close")
            ->setHeader("Content-Length", strlen($str))
            ->setHeader("Content-transfer-encoding", "binary")
            ->setHeader("Cache-control", "private")
            ->setBody($str);

    }

    public function postAction()
    {
        echo date('o-m-d-H-i-s');
    }

    public function deleteAction()
    {
        
    }
}