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
        try {
            $body = '';
            $dir = new DirectoryIterator(APPLICATION_PATH . '/variables/backups');
            foreach ($dir as $fileinfo) {
                if ($fileinfo->getFilename() != '.') {
                    if ($fileinfo->getFilename() != '..') {
                        $data['name'] = $fileinfo->getFilename();
                        $data['size'] = $fileinfo->getSize() / 1024 / 1024 . ' Mb';
                        $response[] = $data;
                        if (is_null($response)) {
                            $response = array();
                            $body = $this->_helper->response()
                                    ->setSuccess(true)
                                    ->addData($response)
                                    ->getResponse()
                                    ->addTotal(0);
                        } else {
                            $body = $this->_helper->response()
                                    ->setSuccess(true)
                                    ->addData($response)
                                    ->getResponse();
                        }
                    }
                }
            }
            $this->getResponse()
                    ->setHttpResponseCode(200)
                    ->appendBody($body);
        } catch (Exception $e) {
            throw $e;
        } catch (Zend_Exception $e) {
            throw $e;
        }
    }

    public function getAction()
    {
        try {
            $params = $this->_helper->param();
            $fileName = $params['fileName'];
            header('Content-Type: text/plain');
            header('Content-Disposition: attachment; filename="' . $fileName . '"');
            readfile(APPLICATION_PATH . '/variables/backups/' . $fileName);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function postAction()
    {
        try {
            $command = "mysqldump --user root --password=root -C kebab_development > " . APPLICATION_PATH . '/variables/backups/' . date('o-m-d-H-i-s') . ".sql";
            system($command);
            $this->getResponse()
                    ->setHttpResponseCode(200)
                    ->appendBody(
                $this->_helper->response()
                        ->setSuccess(true)
                        ->getResponse()
            );
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function deleteAction()
    {

        try {
            $params = $this->_helper->param();
            $fileName = $params['fileName'];
            if (unlink(APPLICATION_PATH . '/variables/backups/' . $fileName)) {
                $this->getResponse()
                        ->setHttpResponseCode(200)
                        ->appendBody(
                    $this->_helper->response()
                            ->setSuccess(true)
                            ->getResponse()
                );
            } else {
                throw new Zend_Exception('Delete a file failed.');
            }
        } catch (Exception $e) {
            throw $e;
        }

    }
}