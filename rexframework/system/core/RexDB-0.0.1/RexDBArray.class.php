<?php
require_once 'RexDBList.class.php';

/**
 * Class RexDBArray
 *
 * @author   MAG
 * @access   public
 * @created  08.02.2012
 */
class RexDBArray extends RexDBList
{
    /**
    * Name of DB column which point to id in parent table
    *
    * @var string
    */
    protected $parent_field;

    /**
    * Reference to parent entity
    *
    * @var RexDBEntity
    */
    protected $parent_entity;

    /**
    * Creates array property for entity
    *
    * @param mixed $child_class
    * @param int $cache_block_ids
    */
    public function __construct($child_class, $cache_block_ids = 50)
    {
        $this->reinit_callback = false;
        $this->link_property = '';

        parent::__construct($child_class, $cache_block_ids);
    }

    /**
    * For set parent entity in his contructor
    *
    * @param RexDBEntity $parent_entity
    */
    public function setParent(RexDBEntity $parent_entity)
    {
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

        $child_class = $this->entity_class;
        $child_spec = $child_class::getFieldSpec();
        if (!isset($child_spec[$this->parent_field])) {
            $parent_spec = $parent_entity::getFieldSpec();
            $child_class::add($this->parent_field, $parent_spec[$parent_entity->__uid], true);
            $child_class::setParentSpec($this->parent_field, $this->parent_entity);
        }
    }

    /**
    * Set WHERE condition for get elements list
    *
    * @param string $where
    * @param string $order_by
    */
    public function getByWhere($where = '', $order_by = '')
    {
        if (!$this->parent_field) {
            throw new Exception(__CLASS__.': parent entity not set');
        }
        if (!$this->parent_entity->{$this->parent_entity->__uid}) {
            throw new Exception(__CLASS__.': get parent entity from DB!');
        }

        $where = ($where ? '('.$where.') AND ' : '').$this->escapeName($this->parent_field).'="'.
            addslashes($this->parent_entity->{$this->parent_entity->__uid}).'"';
        parent::getByWhere($where, $order_by);
    }

    /**
    * Init conditions by procedure, which selected ids of entities
    *
    * @param string $procedure_name
    * @param array $params //params for procedure. first params, sended to procedure - 2 limit params, order by and parent uid
    */
    public function getByProc($procedure_name, $params = array())
    {
        if (!$this->parent_field) {
            throw new Exception(__CLASS__.': parent entity not set');
        }
        if (!$this->parent_entity->{$this->parent_entity->__uid}) {
            throw new Exception(__CLASS__.': get parent entity! (or set id)');
        }
        parent::getByProc($procedure_name, array_merge(array($this->parent_entity->{$this->parent_entity->__uid}), $params));
    }

    public function offsetSet($id, $value)
    {
        if (!$this->parent_entity->{$this->parent_entity->__uid}) {
            throw new Exception(__CLASS__.': get parent entity! (or set id)');
        }
        $value->setParent($this->parent_entity);
        parent::offsetSet($id, $value);
    }

    protected function checkDbStruct()
    {
        if (!$this->parent_entity) {
            return false;
        }
        return $this->parent_entity->checkDbStruct() && parent::checkDbStruct();
    }

    public function offsetGet($id)
    {
        $child_entity = parent::offsetGet($id);
        $child_entity->setParent($this->parent_entity);
        return $child_entity;
    }
}
?>