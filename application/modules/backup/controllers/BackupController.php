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
 * @category   Kebab (kebab-reloaded)
 * @package    Backup
 * @subpackage Controllers
 * @author     lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */


/**
 * Backup_BackupController
 *
 * @category   Kebab (kebab-reloaded)
 * @package    Backup
 * @subpackage Controllers
 * @author     lab2023 Dev Team
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */
class Backup_BackupController extends Kebab_Rest_Controller
{
    /**
     * @throws Zend_Exception
     * @return void
     */
    public function indexAction()
    {
        //KBBTODO pagination
        $dir = new DirectoryIterator(APPLICATION_PATH . '/variables/backups');
        foreach ($dir as $fileinfo) {
            if ($fileinfo->getFilename() != '.' && $fileinfo->getFilename() != '..') {
                $data['name'] = $fileinfo->getFilename();
                $data['size'] = $fileinfo->getSize() / 1024 / 1024 . ' Mb';
                $response[] = $data;
            }
        }
        if (@is_null($response)) {
            $response = array();
            $this->_helper->response(true, 200)->addTotal(0)->addData($response)->getResponse();
        } else {
            $this->_helper->response(true, 200)->addTotal(count($response))->addData($response)->getResponse();
        }
    }

    /**
     * @throws Zend_Exception
     * @return void
     */
    public function getAction()
    {
        $params = $this->_helper->param();
        $fileName = $params['fileName'];
        header('Content-Type: text/plain');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        readfile(APPLICATION_PATH . '/variables/backups/' . $fileName);
    }

    /**
     * @throws Zend_Exception
     * @return void
     */
    public function postAction()
    {
        $database = Zend_Registry::get('config')->database->doctrine->connections->master->dsn;

        $database = explode("://", $database);
        if ($database[0] == 'mysql') {
            $database = explode(":", $database[1]);
            $user = $database[0];
            $database = explode("@", $database[1]);
            $pass = $database[0];
            $database = explode("/", $database[1]);
            $dbName = $database[1];
            $command = "mysqldump --user " . $user . " --password=" . $pass . " -C " . $dbName . " > " . APPLICATION_PATH . '/variables/backups/' . date('o-m-d-H-i-s') . ".sql";
            system($command);
            $this->_helper->response(true, 200)
                    ->getResponse();
        } else {
            throw new Zend_Exception('backup only works with mysql databases.');
        }
    }

    /**
     * @throws Zend_Exception
     * @return void
     */
    public function deleteAction()
    {
        $params = $this->_helper->param();
        $fileName = $params['fileName'];
        if (unlink(APPLICATION_PATH . '/variables/backups/' . $fileName)) {
            $this->_helper->response(true, 204)->getResponse();
        } else {
            throw new Zend_Exception('Delete a file failed.');
        }
    }
}