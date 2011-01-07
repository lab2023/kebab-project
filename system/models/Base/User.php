<?php

/**
 * System_Model_Base_User
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $userId
 * @property string $firstName
 * @property string $surname
 * @property string $userName
 * @property string $email
 * @property string $password
 * @property Doctrine_Collection $Roles
 * @property Doctrine_Collection $UserRole
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     lab2023 - Dev. Team <info@lab2023.com>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class System_Model_Base_User extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('user');
        $this->hasColumn('userId', 'integer', null, array(
             'type' => 'integer',
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('firstName', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('surname', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('userName', 'string', 16, array(
             'type' => 'string',
             'length' => '16',
             ));
        $this->hasColumn('email', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('password', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));

        $this->option('type', 'INNODB');
        $this->option('collate', 'utf8_bin');
        $this->option('charset', 'utf8');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('System_Model_Role as Roles', array(
             'refClass' => 'System_Model_UserRole',
             'local' => 'userId',
             'foreign' => 'roleId'));

        $this->hasMany('System_Model_UserRole as UserRole', array(
             'local' => 'userId',
             'foreign' => 'userId'));
    }
}