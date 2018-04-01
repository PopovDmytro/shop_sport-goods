<?php
/**
 * Class RexDBList
 *
 * @author   MAG
 * @access   public
 * @created  08.02.2012
 */
class RexDBList extends RexObject implements Iterator, ArrayAccess, Countable
{
    public static $version = 1.0;
    public static $assemble = 'standart';
    public static $needed = array(
        'RexDBEntity:standart:1.0'
    );
    
    
    /**
     * Count of IDs, selected for one query in cache-list
     *
     * @var int
     */
    protected $cache_block_ids = 50;
    protected $cache_ids_limit = 1000;

    /**
     * Callback for reinit function
     */
    protected $reinit_callback;

    //SQL-code part, determines condition to select elements fron database
    /**
     * @var string
     */
    protected $sql_where;

    /**
     * @var string
     */
    protected $sql_join;
    
    /**
     * @var string
     */
    protected $sql_custom;
    
    /**
     * @var string
     */
    protected $sql_group_by;
    
    /**
     * @var string
     */
    protected $sql_order_by;

    /**
     * @var int
     */
    protected $sql_limit_from;

    /**
     * @var int
     */
    protected $sql_limit_count;

    /**
     * @var string
     */
    protected $sql_select_ids;

    /**
     * @var string
     */
    protected $sql_select_parent_ids;

    /**
     * @var string
     */
    protected $sql_select_child_ids;

    /**
     * @var string
     */
    protected $sql_delete_ids;

    /**
     * @var string
     */
    protected $sql_proc_name;

    /**
     * @var string
     */
    protected $sql_proc_params;

    /**
     * @var string
     */
    protected $sql_select_count;

    /**
     * Entities, selected from db
     *
     * @var array
     */
    protected $list_entities;

    /**
     * IDs list, selected for conditions in SQL
     *
     * @var array
     */
    protected $list_ids;
    
    /**
     * Value-keyed array, for get information about field value existing in list
     *
     * @var array
     */
    protected $map_values_exist;
    protected $map_values_notexist;
    
    /**
     * @var mixed
     */
    protected $entity_uid;

    /**
     * @var mixed
     */
    protected $entity_parent_uid;

    /**
     * @var mixed
     */
    protected $entity_child_uid;

    /**
     * Count of entities in database for current conditions
     *
     * @var int
     */
    protected $list_ids_count;

    /**
     * Number of current element in iterations
     *
     * @var int
     */
    protected $position;

    /**
     * Name of entity class, which works with database
     *
     * @var string
     */
    protected $entity_class;
    
    /**
     * @var string
     */
    protected $entity_table;

    protected function escapeName($name)
    {
        $name = addslashes(trim($name));
        if (!$name) {
            return '';
        }
        if ($name{0} != '`' || $name{strlen($name) - 1} != '`') {
            $name = '`' . $name . '`';
        }
        return $name;
    }

    protected function escapeString($string)
    {
        return '"' . addslashes($string) . '"';
    }

    /**
     * Returns list class name
     * 
     * @return string
     */
    public function getListClass()
    {
        return $this->entity_class;
    }

    /**
     * Base initialization
     *
     * @param mixed $class Entity class name or object for determine class name
     * @param int $cache_clock_ids Size of cache block
     * @return RexDBList
     */
    public function __construct($class, $cache_block_ids = 50)
    {
        $this->reinit_callback = false;
        $this->sql_where = ' WHERE TRUE';
        $this->list_entities = array();
        $this->map_values_exist = array();
        $this->map_values_notexist = array();
        $this->list_ids = array();
        $this->list_ids_count = false;
        $this->reinit_callback = array(
            'method' => 'getByWhere',
            'params' => array('')
        );

        $this->cache_block_ids = $cache_block_ids;

        if (!is_string($class)) {
            $class = get_class($class);
        }
        if (substr($class, -6) != 'Entity') {
            $class = get_class(RexFactory::entity($class));
        }
        if (!class_exists($class)) {
            throw new Exception('Class "' . $class . '" not exist');
        }
        if (!is_subclass_of($class, 'RexDBEntity')) {
            throw new Exception('Class "' . $class . '" is not child of RexDBEntity');
        }
        /**
         * @var RexDBEntity
         */
        $this->entity_class = $class;
        $this->entity_table = implode('.', array_map(array($this, 'escapeName'), explode('.', $class::getTable())));
        $this->entity_uid = $class::getUid();
        
        $this->map_values_exist[$this->entity_uid] = array();

        if (!$this->entity_table || !$this->entity_uid) {
            throw new Exception('Uncorrect table of uid values in class "' . $class . '"');
        }

        if ($class::hasParent()) {
            $parent_spec = $class::getParentSpec();
            $this->entity_parent_uid = $parent_spec['field'];
            $this->map_values_exist[$this->entity_parent_uid] = array();
        } else {
            $this->entity_parent_uid = '';
        }
        if ($class::hasChild()) {
            $child_spec = $class::getChildSpec();
            $this->entity_child_uid = $child_spec['field'];
            $this->map_values_exist[$this->entity_child_uid] = array();
        } else {
            $this->entity_child_uid = '';
        }
        
        $this->sql_select_ids = 'SELECT `_main`.' . $this->escapeName($this->entity_uid) . ' AS `id` FROM ' . $this->entity_table . ' `_main`';
        $this->sql_delete_ids = 'DELETE FROM ' . $this->entity_table;
        $this->sql_select_count = 'SELECT COUNT(`_main`.*) FROM ' . $this->entity_table . ' `_main`';
    }

    /**
     * Init condition by order by
     *
     * @param string $order_by
     */
    public function setOrderBy($order_by = '', $join = '')
    {
        $next_sql_order_by = $order_by ? ' ORDER BY ' . $order_by : '';

        if (!$this->sql_order_by || $next_sql_order_by != $this->sql_order_by) {
            $this->sql_order_by = $next_sql_order_by;
            $this->list_ids = array();
        }
        
        /*if (!$this->sql_join || $join != $this->sql_join) {
            $this->sql_join = ' ' . trim($join);
            $this->list_ids = array();
        }*/
        
        return $this;
    }

    public function setLimit($limit = '')
    {
        $limits = explode(',', $limit);
        if (sizeof($limits) == 1) {
            $limits = array(0, $limits[0]);
        }
        if (sizeof($limits) != 2) {
            throw new Exception('Uncorrect limit set');
        }
        $limit_from = intval($limits[0]);
        $limit_count = sizeof($limits) > 1 ? intval($limits[1]) : 0;

        if (!$this->sql_limit_from || !$this->sql_limit_count ||
                $this->sql_limit_from != $limit_from || $this->sql_limit_count != $limit_count) {
            $this->sql_limit_from = $limit_from;
            $this->sql_limit_count = $limit_count;
            
            $this->list_entities = array();
            $this->map_values_exist = array($this->entity_uid => array());
            if ($this->entity_parent_uid) {
                $this->map_values_exist[$this->entity_parent_uid] = array();
            }
            if ($this->entity_child_uid) {
                $this->map_values_exist[$this->entity_child_uid] = array();
            }
            $this->map_values_notexist = array();
            $this->list_ids = array();
            $this->list_ids_count = false;
        }
        
        return $this;
    }

    /**
     * Init conditions by WHERE, for selecting list of entities
     *
     * @param string $where
     * @param string $order_by
     */
    public function getByWhere($where = 'TRUE', $combine_operator = false)
    {
        $next_sql_where = $where ? ' WHERE (' . $where . ')' : '';
        
        if ($combine_operator) {
            $combine_operator = strtoupper($combine_operator);
        }
        switch ($combine_operator) {
            case 'OR':
            case 'AND':
                if ($next_sql_where && $this->sql_where) {
                    $next_sql_where = ' WHERE ('.substr($this->sql_where, 7).' '.$combine_operator.' '.substr($next_sql_where, 7).')';
                }
                break;
        }
        
        if (!$this->sql_where || $next_sql_where != $this->sql_where || $this->reinit_callback['method'] != 'getByWhere') {
            $this->sql_where = $next_sql_where;
            $this->list_entities = array();
            $this->map_values_exist = array($this->entity_uid => array());
            if ($this->entity_parent_uid) {
                $this->map_values_exist[$this->entity_parent_uid] = array();
            }
            if ($this->entity_child_uid) {
                $this->map_values_exist[$this->entity_child_uid] = array();
            }
            $this->map_values_notexist = array();
            $this->list_ids = array();
            $this->list_ids_count = false;
            
            $this->reinit_callback = array(
                'method' => 'getByWhere',
                'params' => array($where)
            );
        }
        
        return $this;
    }

    /**
     * Init sql conditions by filter
     *
     * @param mixed $filter
     */
    public function getByFields($filter = array(), $combine_operator = false)
    {
        if (is_array($filter)) {
            $ands = array();
            foreach ($filter as $field => $value) {
                $ands[] = '`_main`.'.$this->escapeName($field) . '=' . $this->escapeString($value);
            }
            $filter = implode(' AND ', $ands);
        }
        $this->getByWhere($filter, $combine_operator);
        
        return $this;
    }

    /**
     * Init conditions by procedure, which selected ids of entities
     *
     * @param string $procedure_name
     * @param array $params //params for procedure. first params, sended to procedure:
     *                        filter_field(mixed), filter_value(mixed), limit_from(int), limit_count(int), order_by(string)
     */
    public function getByProc($procedure_name, $params = array())
    {
        $next_sql_proc_params = implode(', ', array_map(array($this, 'escapeString'), $params));
        
        if ($this->sql_proc_name != $procedure_name || $this->sql_proc_params != $next_sql_proc_params || $this->reinit_callback['method'] != 'getByProc') {
            $this->sql_proc_name = $procedure_name;
            $this->sql_proc_params = $next_sql_proc_params;
            $this->list_entities = array();
            $this->map_values_exist = array($this->entity_uid => array());
            if ($this->entity_parent_uid) {
                $this->map_values_exist[$this->entity_parent_uid] = array();
            }
            if ($this->entity_child_uid) {
                $this->map_values_exist[$this->entity_child_uid] = array();
            }
            $this->map_values_notexist = array();
            $this->list_ids = array();
            $this->list_ids_count = false;
            
            $this->reinit_callback = array(
                'method' => 'getByProc',
                'params' => array($procedure_name, $params)
            );
        }
        
        return $this;
    }

    /**
     * Reinit array after change parent params
     *
     */
    public function reinit()
    {
        if ($this->reinit_callback) {
            call_user_func_array(array($this, $this->reinit_callback['method']), $this->reinit_callback['params']);
        }
        
        return $this;
    }

    /**
     * Get list of another  by path spec
     * 
     * @param mixed param
     */
    public function getList($path = array())
    {
        if (!$path) {
            return $this;
        }
        
        if (!is_array($path)) {
            $path = (array)$path;
        }
        
        $new_list = null;
        $list_field = '';
        $field_select = '';
        
        $param = array_shift($path);
        
        $entity_class = $this->entity_class;
        $field_spec = $entity_class::getFieldSpec();
        if ($param == 'parent') {
            if (!$this->entity_parent_uid) {
                throw new Exception('No parent field registered for '.$this->entity_class);
            }
            $parent_spec = $entity_class::getParentSpec();
            $parent_class = $parent_spec['class'];
            $new_list = new RexDBList($parent_class);
            $list_field = $this->escapeName($parent_class::getUid());
            $field_select = $this->escapeName($this->entity_parent_uid);
        } elseif ($param == 'child') {
            if (!$this->entity_child_uid) {
                throw new Exception('No child field registered for '.$this->entity_class);
            }
            $child_spec = $entity_class::getChildSpec();
            $child_class = $child_spec['class'];
            $new_list = new RexDBList($child_class);
            $list_field = $this->escapeName($child_class::getUid());
            $field_select = $this->escapeName($this->entity_child_uid);
        } elseif (is_string($param) && isset($field_spec[$param])) {
            if ($field_spec[$param] instanceof RexDBAncor) {
                $ancor_class = $field_spec[$param]->ancor_class;
                $new_list = new RexDBList($ancor_class);
                $list_field = $this->escapeName($ancor_class::getUid());
                $field_select = $this->escapeName($param);
            } elseif ($field_spec[$param] instanceof RexDBArray) {
                $list_class = $field_spec[$param]->getListClass();
                $new_list = new RexDBList($list_class);
                $list_field_spec = $list_class::getParentSpec();
                $list_field = $list_field_spec['field'];
                $field_select = $this->escapeName($entity_class::getUid());
            } else {
                throw new Exception('Unsupported field type');
            }    
        } elseif (is_array($param)) {
            $class = array_shift($param);
            if (!class_exists($class)) {
                $class = get_class(RexFactory::entity($class));
            }
            $new_list = new RexDBList($class);
            $list_field = $this->escapeName(array_shift($param));
            $field_select = array_shift($param);
            if (is_null($field_select)) {
                $field_select = $this->escapeName($entity_class::getUid());
            } else {
                $field_select = $this->escapeName($field_select);
            }
        } elseif (is_string($param)) {
            if (!class_exists($param)) {
                $param = get_class(RexFactory::entity($param));
            }
            if (!is_subclass_of($param, 'RexDBEntity')) {
                throw new Exception('Class "' . $param . '" is not child of RexDBEntity');
            }
            $connection_spec = RexDBEntity::getConnectionSpec($entity_class, $param);
            if (!$connection_spec) {
                throw new Exception('Connection from '.$entity_class.' to '.$param.' not exist');
            }
            return $this->getList(array_merge($connection_spec, $path));
        } else {
            var_dump($param);
            throw new Exception('Unsupported field param');
        }
        
        if (true || $this->count() > $this->cache_ids_limit) {
            $new_list->getByWhere('`_main`.'.$list_field.' IN (SELECT _main.'.$field_select.' FROM '.
                    $entity_class::getTable().' _main '.$this->sql_where.')');
            /*if ($_COOKIE['SESSIONRF'] == '6fagc7fgc6of61tffpij1ms0t0') {
                echo '-='.$list_field.' IN (SELECT '.$field_select.' FROM '.
                    $entity_class::getTable().$this->sql_where.')'.'=-';
            }*/
        } else {
            $uids = XDatabase::getOne('SELECT GROUP_CONCAT(DISTINCT CONCAT(\'"\', '.$field_select.', \'"\')) FROM 
                    '.$entity_class::getTable().$this->sql_where);
            /*if ($_COOKIE['SESSIONRF'] == '6fagc7fgc6of61tffpij1ms0t0') {
                echo '-='.'SELECT GROUP_CONCAT(DISTINCT CONCAT(\'"\', '.$field_select.', \'"\')) FROM 
                    '.$entity_class::getTable().$this->sql_where.'=-';
            }*/
            $new_list->getByWhere($this->escapeName($list_field).' IN ('.($uids ?: '""').')');
        }
        
        if ($path) {
            return $new_list->getList($path);
        }
        
        return $new_list;
    }
    
    public function setCustomSql($param)
    {
        $this->sql_custom = $param ? $param : '';
        $this->list_ids = array();
    }
    
    public function setGroupBy($group_by, $having)
    {
        $this->sql_group_by = ($group_by ? ' GROUP BY '.$group_by : '') . ($having ? ' HAVING '.$having : '');
        $this->list_ids = array();
    }
    
    public function setJoin($join)
    {
        $this->sql_join .= $join ? ' '.$join : '';
        $this->list_ids = array();
    }
    
    public function setSelectIds($select)
    {
        $this->sql_select_ids = $select ? ' '.$select : '';
        $this->list_ids = array();
    }

    /**
     * Returns ID for entity array position
     *
     * @param int $position
     * @return int
     */
    protected function getId($position)
    {
        if ($position < $this->count()) {
            if (!isset($this->list_ids[$position])) {
                if (!$this->reinit_callback) {
                    throw new Exception(__CLASS__ . ': call init function first');
                }
                
                $limit_from = 0;
                $limit_count = 0;
                    
                if ($this->reinit_callback['method'] == 'getByWhere') {
                    if (!$this->sql_custom)
                        $sql = $this->sql_select_ids . $this->sql_join . $this->sql_where . $this->sql_group_by . $this->sql_order_by;
                    else
                        $sql = $this->sql_custom;
                    
                    if ($this->sql_limit_from || $this->sql_limit_count) {
                        $limit_from = $this->sql_limit_from;
                        $limit_count = $this->sql_limit_count;
                    }
                    
                    if ($limit_from || $limit_count) {
                        $limit_from += $position;
                        $limit_count -= $position;
                    }
                    
                    if ($this->cache_block_ids) {
                        if ($limit_from || $limit_count) {
                            if ($limit_count > $this->cache_block_ids) {
                                $limit_count = $this->cache_block_ids;
                            }
                        } else {
                            $limit_from = $position;
                            $limit_count = $this->cache_block_ids;
                        }
                    }
                    
                    if ($limit_from || $limit_count) {
                        $sql .= ' LIMIT '.$limit_from.', '.$limit_count;
                    }
                    
                    $new_list_ids = XDatabase::getAll($sql);
                    
                    if (XDatabase::isError()) {
                        if (RexConfig::isTrue('Core', 'RexDB', 'check_db_struct_on_error') && $this->checkDbStruct()) {
                            $new_list_ids = XDatabase::getAll($sql);
                        }
                        if (XDatabase::isError()) {
                            throw new Exception('DBError: ' . XDatabase::getError(true));
                        }
                    }
                } elseif ($this->reinit_callback['method'] == 'getByProc') {
                    //TODO: добавить поддержку setLimit
                    $sql = $this->sql_proc_name . '(NULL, NULL, '.
                            $position . ', ' .
                            $this->cache_block_ids . ', "' .
                            $this->sql_order_by . '", ' .
                            $this->sql_proc_params . ')';
                    $new_list_ids = XDatabase::getStored($sql);
                    if (XDatabase::isError()) {
                        if (RexConfig::isTrue('Core', 'RexDB', 'check_db_struct_on_error') && $this->checkDbStruct()) {
                            $new_list_ids = XDatabase::getStored($sql);
                        }
                        if (XDatabase::isError()) {
                            throw new Exception('DBError: ' . XDatabase::getError(true));
                        }
                    }
                } else {
                    throw new Exception(__CLASS__ . ': wrong init function');
                }
                foreach ($new_list_ids as $new_list_id) {
                    $this->map_values_exist[$this->entity_uid][$new_list_id['id']] = true;
                }
                if ($new_list_ids) {
                    $this->list_ids += array_combine(range($position, $position + sizeof($new_list_ids) - 1), array_values($new_list_ids));
                }
            }
            return $this->list_ids[$position]['id'];
        }
        
        throw new Exception('Index ' . $position . ' not exist');
    }
    
    public function fieldInList($field, $value)
    {
        if (isset($this->map_values_exist[$field][$value])) {
            return true;
        }
        if (isset($this->map_values_notexist[$field][$value])) {
            return false;
        }
        if ((!isset($this->map_values_exist[$field]) || sizeof($this->map_values_exist[$field]) < $this->count()) && $this->isFieldExistInDB($field, $value)) {
            $this->map_values_exist[$field][$value] = true;
            return true;
        }
        $this->map_values_notexist[$field][$value] = true;
        return false;
    }
    
    private function isFieldExistInDB($field, $value)
    {
        $get_value = !$value;
        if ($this->reinit_callback['method'] == 'getByWhere') {                    
            if ($this->sql_limit_from || $this->sql_limit_count) {
                $sql = 'SELECT `field` FROM ('.
                            'SELECT '.$this->escapeName($field).' AS `field` FROM '.$this->entity_table.
                            $this->sql_where.' LIMIT '.$this->sql_limit_from.', '.$this->sql_limit_count.
                       ') AS tbl WHERE `field` = '.$this->escapeString($value).' LIMIT 0, 1';
            } else {
                $sql = 'SELECT '.$this->escapeName($field).' AS `field` FROM '.$this->entity_table.
                        $this->sql_where.' AND '.$this->escapeName($field).' = '.$this->escapeString($value).' LIMIT 0, 1';
            }
            $get_value = XDatabase::getOne($sql);
            if (XDatabase::isError()) {
                if (RexConfig::isTrue('Core', 'RexDB', 'check_db_struct_on_error') && $this->checkDbStruct()) {
                    $get_value = XDatabase::getOne($sql);
                }
                if (XDatabase::isError()) {
                    throw new Exception('DBError: ' . XDatabase::getError(true));
                }
            }
        } elseif ($this->reinit_callback['method'] == 'getByProc') {
            if ($this->sql_limit_from || $this->sql_limit_count) {
                $sql = $this->sql_proc_name.'('.$this->escapeString($field).', '.$this->escapeString($value).', ' .
                        '0, 1, NULL, ' . $this->sql_proc_params . ')';
            } else {
                $sql = $this->sql_proc_name.'('.$this->escapeString($field).', '.$this->escapeString($value).', ' .
                        'NULL, NULL, NULL, ' . $this->sql_proc_params . ')';
            }
            $get_value = XDatabase::setStored($sql);
            if (XDatabase::isError()) {
                if (RexConfig::isTrue('Core', 'RexDB', 'check_db_struct_on_error') && $this->checkDbStruct()) {
                    $get_value = XDatabase::setStored($sql);
                }
                if (XDatabase::isError()) {
                    throw new Exception('DBError: ' . XDatabase::getError(true));
                }
            }
        } else {
            throw new Exception(__CLASS__ . ': wrong init function');
        }
        if ($get_value == $value) {
            return true;
        }
        
        return false;
    }

    /**
     * Check id in list present
     *
     * @param mixed $id
     */
    public function idInList($id)
    {
        return $this->fieldInList($this->entity_uid, $id);
    }

    /**
     * Check parent id in list present
     *
     * @param mixed $id
     */
    public function parentIdInList($id)
    {
        if (!$this->entity_parent_uid) {
            throw new Exception(__CLASS__ . ': parent class not exist');
        }
        return $this->fieldInList($this->entity_parent_uid, $id);
    }

    /**
     * Check child id in list present
     *
     * @param mixed $id
     */
    public function childIdInList($id)
    {
        if (!$this->entity_child_uid) {
            throw new Exception(__CLASS__ . ': child class not exist');
        }
        return $this->fieldInList($this->entity_child_uid, $id);
    }

    /**
     * Used in foreach for initialization before cycle
     *
     * @return void
     */
    public function rewind($position = 0)
    {
        $this->position = $position;
        return $this;
    }

    /**
     * Returns element for current offset (array position)
     *
     * @return RexDBEntity
     */
    public function current()
    {
        return $this->offsetGet($this->key());
    }

    /**
     * Returns current offset (array fosition). Used in foreach
     *
     * @return int
     */
    public function key()
    {
        return $this->getId($this->position);
    }

    /**
     * Next offset. Used in foreach
     *
     * @return int
     */
    public function next()
    {
        ++$this->position;
        return $this;
    }

    /**
     * Check current position validity. Used in foreach
     *
     * @return bool
     */
    public function valid()
    {
        return $this->position < $this->count();
    }

    /**
     * Returns items count in db, which satisfies current condition
     *
     * @return int
     */
    public function count()
    {
        if ($this->list_ids_count === false) {
            if (!$this->reinit_callback) {
                throw new Exception(__CLASS__ . ': call init function first');
            }
            if ($this->reinit_callback['method'] == 'getByWhere') {
                if ($this->sql_limit_from || $this->sql_limit_count) {
                    $sql = $this->sql_select_ids . $this->sql_join . $this->sql_where . $this->sql_group_by;
                    $sql .= ' LIMIT '.$this->sql_limit_from.', '.$this->sql_limit_count;
                    $sql = 'SELECT COUNT(*) FROM ('.$sql.') AS tbl';
                } else {
                    $sql = $this->sql_select_ids . $this->sql_join . $this->sql_where . $this->sql_group_by;
                    $sql = 'SELECT COUNT(*) FROM ('.$sql.') AS tbl';
                    //$sql = $this->sql_select_count . $this->sql_join . $this->sql_where . $this->sql_group_by . $this->sql_order_by;
                }
                
                //echo '**'.$sql.'**';
                
                $this->list_ids_count = XDatabase::getOne($sql);
                if (XDatabase::isError()) {
                    if (RexConfig::isTrue('Core', 'RexDB', 'check_db_struct_on_error') && $this->checkDbStruct()) {
                        $this->list_ids_count = XDatabase::getOne($sql);
                    }
                    if (XDatabase::isError()) {
                        echo $sql;
                        exit;
                        throw new Exception('DBError: ' . XDatabase::getError(true));
                    }
                }
            } elseif ($this->reinit_callback['method'] == 'getByProc') {
                //TODO: поддержка setLimit
                $sql = $this->sql_proc_name . '(NULL, NULL, 0, 0, "", ' . $this->sql_proc_params . ')';
                $this->list_ids_count = XDatabase::setStored($sql);
                if (XDatabase::isError()) {
                    if (RexConfig::isTrue('Core', 'RexDB', 'check_db_struct_on_error') && $this->checkDbStruct()) {
                        $this->list_ids_count = XDatabase::setStored($sql);
                    }
                    if (XDatabase::isError()) {
                        throw new Exception('DBError: ' . XDatabase::getError(true));
                    }
                }
            } else {
                throw new Exception(__CLASS__ . ': wrong init function');
            }
        }
        return $this->list_ids_count;
    }

    /**
     * Delete all entities in list
     */
    public function clear()
    {
        if (!$this->count()) {
            return;
        }
        $reflection = new ReflectionClass($this->entity_class);
        if ($reflection->getMethod('delete')->getDeclaringClass()->name == 'RexDBEntity') {
            //method delete is not overloaded - no image delete needed and etc. - entities can be delete by one query
            if (!$this->reinit_callback) {
                throw new Exception(__CLASS__ . ': call init function first');
            }
            if ($this->reinit_callback['method'] == 'getByWhere') {
                XDatabase::query($this->sql_delete_ids . $this->sql_where);
                if (XDatabase::isError()) {
                    if (RexConfig::isTrue('Core', 'RexDB', 'check_db_struct_on_error') && $this->checkDbStruct()) {
                        XDatabase::query($this->sql_delete_ids . $this->sql_where);
                    }
                    if (XDatabase::isError()) {
                        throw new Exception('DBError: ' . XDatabase::getError(true));
                    }
                }
            } elseif ($this->reinit_callback['method'] == 'getByProc') {
                $sql = $this->sql_proc_name . '(NULL, NULL, -1, -1, "", ' . $this->sql_proc_params . ')';
                XDatabase::getStored($sql);
                if (XDatabase::isError()) {
                    if (RexConfig::isTrue('Core', 'RexDB', 'check_db_struct_on_error') && $this->checkDbStruct()) {
                        XDatabase::getStored($sql);
                    }
                    if (XDatabase::isError()) {
                        throw new Exception('DBError: ' . XDatabase::getError(true));
                    }
                }
            } else {
                throw new Exception(__CLASS__ . ': wrong init function');
            }
        } else {
            foreach ($this as $entity_id => $entity) {
                $entity->delete();
            }
        }

        $this->list_entities = array();
        $this->map_values_exist = array();
        $this->list_ids = array();
        $this->list_ids_count = 0;
        
        return $this;
    }

    /**
     * Implementation of the function set
     *
     * @param int $id
     * @param RexDBEntity $value
     */
    public function offsetSet($id, $value)
    {
        if (is_null($id)) {
            $value->create();
            $this->list_ids = array();
            $this->list_ids_count = false;
        } else {
            if (!$this->idInList($id)) {
                throw new Exception('Uid "'.$id.'" not exist or not in this list');
            }
            $value->{$value->__uid} = $id;
            $value->update();
            $this->list_ids = array();
            $this->list_ids_count = false;
            
            foreach ($value as $field => $val) {
                if (isset($this->map_values_notexist[$field])) {
                    unset($this->map_values_notexist[$field][$val]);
                }
            }
        }
        
        return $this;
    }

    /**
     * Implementation of the function isset
     *
     * @param int $id
     */
    public function offsetExists($id)
    {
        return $this->idInList($id);
    }

    /**
     * Implementation of the function unset
     *
     * @param int $id
     */
    public function offsetUnset($id)
    {
        if (!$this->idInList($id)) {
            throw new Exception('Uid "' . $id . '" not exist or not in this list');
        }
        $entity = $this->offsetGet($id);
        $entity->delete();

        $this->list_ids = array();
        $this->list_ids_count = false;
        
        return $this;
    }

    /**
     * Implementation of array access
     *
     * @param int $id
     * @return RexDBEntity
     */
    public function offsetGet($id)
    {
        if (!$this->idInList($id)) {
            throw new Exception('Uid "' . $id . '" not exist or not in this list');
        }
        if (!isset($this->list_entities[$id])) {
            $this->list_entities[$id] = new $this->entity_class($id);
            $this->list_entities[$id]->setList($this);
            
            foreach ($this->list_entities[$id] as $field => $value) {
                if (isset($this->map_values_exist[$field])) {
                    $this->map_values_exist[$field][$value] = true;
                }
                if (isset($this->map_values_notexist[$field])) {
                    unset($this->map_values_notexist[$field][$value]);
                }
            }
        }
        if ($this->list_entities[$id]->{$this->entity_uid} != $id) {
            throw new Exception('Record "' . $this->entity_class . '" with Uid "' . $id . '" not exist');
        }
        return $this->list_entities[$id];
    }

    protected function checkDbStruct()
    {
        $entity_class = $this->entity_class;
        return $entity_class::checkDbStruct();
    }

}