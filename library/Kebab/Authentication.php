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
 * @package    Kebab
 * @subpackage Library
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */

/**
 * Kebab_Authentication
 *
 * @category   Kebab
 * @package    Kebab
 * @subpackage Library
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */

final class Kebab_Authentication
{
    public static function signIn($userName, $password, $rememberMe = false, $md5 = true)
    {
        $retVal = false;
        
        // set ZendX_Doctrine_Auth_Adapter
        $auth = Zend_Auth::getInstance();
        $authAdapter = new ZendX_Doctrine_Auth_Adapter(Doctrine::getConnectionByTableName('Model_Entity_User'));

        $password = $md5 ? md5($password) : $password;

        $authAdapter->setTableName('Model_Entity_User u')
                ->setIdentityColumn('userName')
                ->setCredentialColumn('password')
                ->setCredentialTreatment('? AND active = 1')
                ->setIdentity($userName)
                ->setCredential($password);

        // set Zend_Auth
        $result = $auth->authenticate($authAdapter); 

        // Check Auth Validation
        if ($result->isValid()) {

            // Remove some fields which are secure!
            $omitColumns = array('password', 'activationKey', 'created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by');
            $identity = $authAdapter->getResultRowObject(null, $omitColumns);
            $identity->roles = Kebab_Model_User::getUserRoles($identity->id);
            $identity->acl = new Kebab_Access_Acl();
            $identity->stories = Kebab_Model_Story::getUserStoriesName($identity->roles);

            $auth->getStorage()->write($identity);
            if ($rememberMe) {
                Zend_Session::rememberMe(604800);
            }
            $retVal = true;

        }
        return $retVal;
    }

    public static function signOut()
    {
        Zend_Auth::getInstance()->clearIdentity();
        Zend_Session::forgetMe();
    }
}