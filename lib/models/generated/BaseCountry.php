<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('Country', 'doctrine');

/**
 * BaseCountry
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $title_es
 * @property string $title_en
 * @property string $title_fr
 * @property integer $continents
 * @property integer $status
 * @property integer $sort
 * @property string $code
 * @property string $currency
 * @property string $langIsocode
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseCountry extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('country');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('title_es', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('title_en', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('title_fr', 'string', 256, array(
             'type' => 'string',
             'length' => 256,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             ));
        $this->hasColumn('continents', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => true,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             ));
        $this->hasColumn('status', 'integer', 1, array(
             'type' => 'integer',
             'length' => 1,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'default' => '1',
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('sort', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'default' => '0',
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('code', 'string', 2, array(
             'type' => 'string',
             'length' => 2,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             ));
        $this->hasColumn('currency', 'string', 3, array(
             'type' => 'string',
             'length' => 3,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('langIsocode', 'string', 256, array(
             'type' => 'string',
             'length' => 256,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'default' => 'en',
             'notnull' => false,
             'autoincrement' => false,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        
    }
}