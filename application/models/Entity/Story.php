<?php

/**
 * Model_Entity_Story
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $name
 * @property string $title
 * @property clob $description
 * @property enum $status
 * @property Doctrine_Collection $Applications
 * @property Doctrine_Collection $Roles
 * @property Doctrine_Collection $Service
 * @property Doctrine_Collection $StoryApplication
 * @property Doctrine_Collection $Permission
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     lab2023 - Dev. Team <info@lab2023.com>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Model_Entity_Story extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('system_story');
        $this->hasColumn('name', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('title', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('description', 'clob', null, array(
             'type' => 'clob',
             ));
        $this->hasColumn('status', 'enum', 7, array(
             'type' => 'enum',
             'length' => 7,
             'values' => 
             array(
              0 => 'active',
              1 => 'passive',
             ),
             'default' => 'active',
             ));

        $this->option('type', 'INNODB');
        $this->option('collate', 'utf8_bin');
        $this->option('charset', 'utf8');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Model_Entity_Application as Applications', array(
             'refClass' => 'Model_Entity_StoryApplication',
             'local' => 'story_id',
             'foreign' => 'application_id'));

        $this->hasMany('Model_Entity_Role as Roles', array(
             'refClass' => 'Model_Entity_Permission',
             'local' => 'story_id',
             'foreign' => 'role_id'));

        $this->hasMany('Model_Entity_Service as Service', array(
             'local' => 'id',
             'foreign' => 'story_id'));

        $this->hasMany('Model_Entity_StoryApplication as StoryApplication', array(
             'local' => 'id',
             'foreign' => 'story_id'));

        $this->hasMany('Model_Entity_Permission as Permission', array(
             'local' => 'id',
             'foreign' => 'story_id'));

        $sluggable0 = new Doctrine_Template_Sluggable(array(
             'fields' => 
             array(
              0 => 'name',
             ),
             ));
        $i18n0 = new Doctrine_Template_I18n(array(
             'fields' => 
             array(
              0 => 'title',
              1 => 'description',
             ),
             'className' => 'StoryTranslation',
             'length' => 5,
             ));
        $this->actAs($sluggable0);
        $this->actAs($i18n0);
    }
}