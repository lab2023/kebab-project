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
 * @package    Kebab
 * @subpackage Model
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */

/**
 * Kebab_Model_Role
 *
 * @category   Kebab (kebab-reloaded)
 * @package    Kebab
 * @subpackage Model
 * @author     Onur Özgür ÖZKAN <onur.ozgur.ozkan@lab2023.com>
 * @copyright  Copyright (c) 2010-2011 lab2023 - internet technologies TURKEY Inc. (http://www.lab2023.com)
 * @license    http://www.kebab-project.com/cms/licensing
 * @version    1.5.0
 */

class Kebab_Model_Role
{
    static public function getAllRoles()
    {

        $query = Doctrine_Query::create()
                    ->select('role.id,
                        roleTranslation.title as title,
                        roleTranslation.description as description,
                        role.active')
                    ->from('Model_Entity_Role role')
                    ->leftJoin('role.Translation roleTranslation')
                    ->where('roleTranslation.lang = ?', Zend_Auth::getInstance()->getIdentity()->language);
        return $query;
    }
}