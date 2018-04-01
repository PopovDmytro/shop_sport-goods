<?php
require_once 'RexDBArray.class.php';
//require_once 'RexDBType/RexDBInt.class.php';

/**
 * Class RexDBLink
 *
 * @author   MAG
 * @access   public
 * @created  08.02.2012
 */
class RexDBLink extends RexDBArray
{
    protected $child_class;
    protected $value_class;

    /**
    * Creates link property for entity
    *
    * @param mixed $child_class
    * @param mixed $value_class Name of RexDBEntity class for save link value
    * @param int $cache_block_ids
    */
    public function __construct($child_class, $value_class = null, $cache_block_ids = 50)
    {
        if (!is_string($child_class)) {
            $child_class = get_class($child_class);
        }
        if (substr($child_class, -6) != 'Entity') {
            $child_class = get_class(RexFactory::entity($child_class));
        }
        if (!is_subclass_of($child_class, 'RexDBEntity')) {
            throw new Exception('Submitted class "'.$child_class.'", which is not a child of RexDBEntity');
        }
        if (!$child_class::getTable() || !$child_class::getUid()) {
            throw new Exception('Uncorrect info for db table in class "'.$child_class.'"');
        }
        if (!is_null($value_class)) {
            if (!is_string($value_class)) {
                $value_class = get_class($value_class);
            }
            if (substr($value_class, -6) != 'Entity') {
                $value_class = get_class(RexFactory::entity($value_class));
            }
            if (!is_subclass_of($value_class, 'RexDBEntity')) {
                throw new Exception('Submitted class "'.$value_class.'", which is not a child of RexDBEntity');
            }
            if (!$value_class::getTable() || !$value_class::getUid()) {
                throw new Exception('Uncorrect info for db table in class "'.$value_class.'"');
            }
        }
        $this->child_class = $child_class;
        $this->value_class = $value_class;
        $this->cache_block_ids = $cache_block_ids;
    }
    
    /**
     * Returns list child class name
     * 
     * @return string
     */
    public function getListChildClass()
    {
        return $this->child_class;
    }    

    /**
    * For set parent entity in his contructor
    *
    * @param RexDBEntity $parent_entity
    */
    public function setParent(RexDBEntity $parent_entity)
    {
        $explode_parent_table = explode('.', $parent_entity->__table);
        $parent_table = $explode_parent_table[sizeof($explode_parent_table) - 1];
        if ($parent_table{0} == '`' && $parent_table{strlen($parent_table) - 1} == '`') {
            $parent_table = substr($parent_table, 1, strlen($parent_table) - 2);
        }

        $parent_class = get_class($parent_entity);
        if (!is_subclass_of($parent_entity, 'RexDBEntity')) {
            throw new Exception('Submitted class "'.$parent_class.'", which is not a child of RexDBEntity');
        }
        if (!$parent_entity->__table || !$parent_entity->__uid) {
            throw new Exception('Uncorrect info for db table in class "'.$parent_class.'"');
        }

        $explode_parent_table = explode('.', $parent_entity->__table);
        $parent_table = $explode_parent_table[sizeof($explode_parent_table) - 1];
        if ($parent_table{0} == '`' && $parent_table{strlen($parent_table) - 1} == '`') {
            $parent_table = substr($parent_table, 1, strlen($parent_table) - 2);
        }
        $this->parent_field = $parent_entity->__uid;
        if (strcasecmp(substr($this->parent_field, 0, strlen($parent_table)), $parent_table) != 0) {
            $this->parent_field = $parent_table.'_'.$this->parent_field;
        }
        $this->parent_entity = $parent_entity;

        $child_class = $this->child_class;
        $explode_child_table = explode('.', $child_class::getTable());
        $child_table = $explode_child_table[sizeof($explode_child_table) - 1];
        if ($child_table{0} == '`' && $child_table{strlen($child_table) - 1} == '`') {
            $child_table = substr($child_table, 1, strlen($child_table) - 2);
        }
        $this->child_field = $child_class::getUid();
        if (strcasecmp(substr($this->child_field, 0, strlen($child_table)), $child_table) != 0) {
            $this->child_field = $child_table.'_'.$this->child_field;
        }

        //============= create or use link entity ========
        $entity_created = false;
        if (is_null($this->value_class)) {
            $value_table = '_link_'.$parent_table.'_'.$child_table;
            $value_class = $value_table.'Entity';
            $value_uid = 'id';
            if (!class_exists($value_class)) {
                RexDynamicDBEntity::declaration($value_class, $value_table, $value_uid);
                $value_class::add('id', new RexDBInt(REXDB_FIELD_UNSIGNED));
                $value_entity = new $value_class();
                $entity_created = true;
            }
            $this->value_class = $value_class;
        }

        $value_class = $this->value_class;
        $value_entity = new $value_class();
        $value_spec = $value_class::getFieldSpec();

        if (!isset($value_spec[$this->child_field])) {
            $child_entity = new $child_class();
            $child_spec = $child_class::getFieldSpec();
            $value_class::add($this->child_field, $child_spec[$child_entity->__uid], true);
            $value_entity->setChildSpec($this->child_field, $child_class);
        }
        parent::__construct($this->value_class, $this->cache_block_ids);
        //================================================

        $value_class = $this->entity_class;
        $value_spec = $value_class::getFieldSpec();
        if (!isset($value_spec[$this->parent_field])) {
            $parent_spec = $parent_entity::getFieldSpec();
            $value_class::add($this->parent_field, $parent_spec[$parent_entity->__uid], true);
            $value_class::setParentSpec($this->parent_field, $this->parent_entity);
        }
        
        if ($entity_created && RexConfig::isTrue('Core', 'RexDB', 'check_db_struct_on_startup') && $value_entity instanceof RexDBEntity) {
            $value_class::checkDbStruct();
        }
    }

    /**
    * Creates link
    *
    * @param RexDBEntity $child_entity
    * @param mixed $value Array or value for set in link value
    */
    public function addLink(RexDBEntity $child_entity, $value = null)
    {
        $value_class = $this->entity_class;
        $value_entity = new $value_class();
        $value_entity->set($value);
        $value_entity->setChild($child_entity);
        $this->offsetSet(null, $value_entity);
    }

    protected function checkDbStruct()
    {
        $value_class = $this->value_class;
        return $value_class::checkDbStruct() && parent::checkDbStruct();
    }
}
?>