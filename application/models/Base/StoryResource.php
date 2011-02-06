<?php

/**
 * Model_Base_StoryResource
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $story_id
 * @property integer $resource_id
 * @property Model_Story $Story
 * @property Model_Resource $Resource
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     lab2023 - Dev. Team <info@lab2023.com>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class Model_Base_StoryResource extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('story_resource');
        $this->hasColumn('story_id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             ));
        $this->hasColumn('resource_id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             ));

        $this->option('type', 'INNODB');
        $this->option('collate', 'utf8_bin');
        $this->option('charset', 'utf8');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Model_Story as Story', array(
             'local' => 'story_id',
             'foreign' => 'id'));

        $this->hasOne('Model_Resource as Resource', array(
             'local' => 'resource_id',
             'foreign' => 'id'));
    }
}