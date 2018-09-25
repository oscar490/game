<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('PermissionType', 'doctrine');

/**
 * BasePermissionType
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $title
 * @property Doctrine_Collection $GroupPermission
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BasePermissionType extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('permission_type');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('title', 'string', 256, array(
             'type' => 'string',
             'length' => 256,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('GroupPermission', array(
             'local' => 'id',
             'foreign' => 'id_permission_type'));
    }
}