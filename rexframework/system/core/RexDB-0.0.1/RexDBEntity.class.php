<?php
/**
 * Class RexDBEntity
 *
 * @author   MAG
 * @access   public
 * @created  Fri Mar 23 11:25:33 EET 2007
 *
 * @property-read string $__table
 * @property-read string $__uid
 */
class RexDBEntity extends \RexFramework\DBEntity implements Iterator, ArrayAccess, Countable
{
    public static $assemble = 'standart';
    public static $version = 1.0;
    public static $needed = array(
        'RexFramework\DBEntity:standart:1.0'
    );
    
    /**
    * Table in DB
    *
    * @var string
    */
    protected static $__table;

    /**
    * Method for return DB table for class
    */
    public static function getTable()
    {
        if (static::$__table{0} != '`') {
            static::$__table = static::escapeTable(static::$__table);
        }
        return static::$__table;
    }

    /**
    * Key column name in DB table
    *
    * @var string
    */
    protected static $__uid;

    /**
    * Method for return DB uid for class
    *
    * @return string
    */
    public static function getUid()
    {
        return static::$__uid;
    }

    /**
    * Dynamic "invisible" fields for links. For internal usage
    *
    * @var array
    */
    protected static $entity_spec = array();

    /**
    * Method for return DB field spec for class
    */
    public static function getFieldSpec()
    {
        if (isset(static::$entity_spec[static::getTable()]['fields'])) {
            return static::$entity_spec[static::getTable()]['fields'];
        } else {
            return array();
        }
    }

    /**
     * Array for entity connections describe. Used by list
     *
     * @var array
     */
    public static $entity_connections = array('connections' => array());

    /**
     * Returns connection spec, or false if connection not exist
     *
     * @param mixed from_entityty
     * @param mixed from_entity
     * @return mixed
     */
    public static function getConnectionSpec($from_entity, $to_entity)
    {
        if (!isset(static::$entity_connections['parsed'])) {
            static::parseConnectionSpec();
        }
        if (!is_string($from_entity)) {
            $from_entity = get_class($from_entity);
        }
        if (!is_string($to_entity)) {
            $to_entity = get_class($to_entity);
        }
        if (isset(static::$entity_connections['parsed'][$from_entity]) &&
            isset(static::$entity_connections['parsed'][$from_entity][$to_entity]))
        {
            return static::$entity_connections['parsed'][$from_entity][$to_entity];
        }
        return false;
    }

    protected static function registerConnection($type, $from_entity, $from_field, $to_entity, $to_field)
    {
        static::$entity_connections['connections'][$type][] = array(
                'from' => $from_entity,
                'to' => $to_entity,
                'connection' => array($to_entity, $to_field, $from_field));
    }

    public static function parseConnectionSpec()
    {
        static::$entity_connections['parsed'] = array();
        $queue = array();
        if (isset(static::$entity_connections['connections']['parent'])) {
            $queue = array_merge($queue, static::$entity_connections['connections']['parent']);
        }
        if (isset(static::$entity_connections['connections']['child'])) {
            $queue = array_merge($queue, static::$entity_connections['connections']['child']);
        }
        if (isset(static::$entity_connections['connections']['ancor'])) {
            $queue = array_merge($queue, static::$entity_connections['connections']['ancor']);
        }
        while (!is_null($connection = array_shift($queue))) {
            $next = array();
            
            $double_connection = array($connection, array(
                    'from' => $connection['to'],
                    'to' => $connection['from'],
                    'connection' => array(
                        $connection['from'], 
                        $connection['connection'][2], 
                        $connection['connection'][1])));
                    
            /*if ($_COOKIE['SESSIONRF'] == 's5sjt84pfc8p41nbu9okgekkg0') {
                print_r($double_connection);
                echo "\n====\n";
            }*/
            foreach ($double_connection as $connection) {
                static::$entity_connections['parsed'][$connection['from']][$connection['to']] = array($connection['connection']);
                
                
                foreach (static::$entity_connections['parsed'] as $from => $to_connection) {
                    if (isset($to_connection[$connection['from']])) {
                        if (!isset(static::$entity_connections['parsed'][$from][$connection['to']]) ||
                            sizeof(static::$entity_connections['parsed'][$from][$connection['to']]) > sizeof($to_connection[$connection['from']]) + 1)
                        {
                            static::$entity_connections['parsed'][$from][$connection['to']] = array_merge(
                                $to_connection[$connection['from']],
                                array($connection['connection']));
                                
                            //var_dump($from, $connection['to'], static::$entity_connections['parsed'][$from][$connection['to']]);
                        }
                    }
                }
                
                if (isset(static::$entity_connections['parsed'][$connection['to']])) {
                    foreach (static::$entity_connections['parsed'][$connection['to']] as $to => $from_connection) {
                        if (!isset(static::$entity_connections['parsed'][$connection['from']][$to]) ||
                            sizeof(static::$entity_connections['parsed'][$connection['from']][$to]) > sizeof($from_connection) + 1)
                        {
                            static::$entity_connections['parsed'][$connection['from']][$to] = array_merge(
                                array($connection['connection']),
                                $from_connection);
                                
                            //var_dump($connection['from'], $to, static::$entity_connections['parsed'][$connection['from']][$to]);
                        }
                    }
                }
            }
        }
    }

    /**
     * Set class name for entity
     *
     * @param string $class_name
     */
    public static function setClass($class_name)
    {
        static::$entity_spec[static::getTable()]['class'] = $class_name;
    }

    /**
     * Parent entity
     *
     * @var RexDBEntity
     */
    protected $parent_entity;
    /**
     * Parent entity class;
     *
     * @var string
     */
    protected $parent_entity_class;
    /**
     * Parent uid representation field
     *
     * @var string
     */
    protected $parent_field;

    /**
     * Child entity
     *
     * @var RexDBEntity
     */
    protected $child_entity;
    /**
     * Child entity class
     *
     * @var string
     */
    protected $child_entity_class;
    /**
     * Child uid representation field
     *
     * @var string
     */
    protected $child_field;

    /**
     * Entities List object
     *
     * @var RexDBList
     */
    protected $list;

    /**
    * Array for save values from DB
    *
    * @var array
    */
    protected $values;
    /**
    * Keys array
    *
    * @var array
    */
    private $fields;
    /**
    * Current position in keys array (for foreach)
    *
    * @var int
    */
    private $position;

    /**
     * Id for feature getFromDB call
     *
     * @var mixed
     */
    private $get_uid;

    /**
    * Function for type initialization
    */
    protected static function initTypes()
    {
    }

    /**
    * If set id then function get will be called after initialization
    *
    * @param int $id
    * @param boot $no_init for inner needs (in lists)
    */
    public function __construct($id = null)
    {
        if (!$this::$__table) {
            throw new Exception(__CLASS__.': set table property');
        }
        if (!$this::$__uid) {
            throw new Exception(__CLASS__.': set uid property');
        }
        static::setClass(get_class($this));

        $this->parent_entity = null;
        $this->parent_field = '';
        $this->child_entity = null;
        $this->child_field = '';
        $this->list = null;

        static $init_tables = array();
        if ($this::$__table{0} != '`') {
            $this::$__table = $this::escapeTable($this::$__table);
        }
        if (!isset($init_tables[$this::$__table])) {
            $this::initTypes();

            foreach ($this::$entity_spec[$this::$__table]['fields'] as $field => $type) {
                if ($type instanceof RexDBAncor) {
                    if (!isset(static::$entity_spec[static::$__table]['class'])) {
                        throw new Exception('Set entity class first (Check that entity class name and file name are the same)');
                    }
                    $ancor_class = $type->ancor_class;
                    static::registerConnection('ancor',
                            $ancor_class, $ancor_class::getUid(),
                            static::$entity_spec[static::$__table]['class'], $field);
                }
            }

            $init_tables[$this::$__table] = true;
        }

        $this->resetFields();

        if (!is_null($id)) {
            $this->get($id);
        }
    }

    /**
     * Reset fields for default state
     */
    protected function resetFields()
    {
        $this->get_uid = null;
        $this->fields = array();
        $this->values = array();
        foreach ($this::$entity_spec[$this::$__table]['fields'] as $field => $type) {
            $this->values[$field] = clone $type;
            if ($type instanceof RexDBType) {
                $this->fields[] = $field;
            } elseif ($type instanceof RexDBArray) {
                $this->values[$field]->setParent($this);
            }
        }
        return $this;
    }

    /**
    * Escape table or column name for DB usage
    *
    * @param string $name
    * @return string
    */
    protected static function escapeName($name)
    {
        $name = addslashes(trim($name));
        if (!$name) {
            return '';
        }
        if ($name{0} != '`' || $name{strlen($name) - 1} != '`') {
            $name = '`'.$name.'`';
        }
        return $name;
    }

    /**
    * Escape table name for DB usage
    *
    * @param string $table
    * @return string
    */
    protected static function escapeTable($table)
    {
        return implode('.', array_map(array('RexDBEntity', 'escapeName'), explode('.', $table)));
    }

    /**
    * Dynamic entity extend
    *
    * @param string $name
    * @param object $type
    * @param bool $high_priority
    *
    * @return void
    */
    public static function add($name, $type, $high_priority = false)
    {
        if (static::$__table{0} != '`') {
            static::$__table = static::escapeTable(static::$__table);
        }
        if (isset(static::$entity_spec[static::$__table]['fields'][$name])) {
            throw new Exception(__CLASS__.': property "'.$name.'" already exist');
        }
        if (!$type instanceof RexDBType && !$type instanceof RexDBArray) {
            throw new Exception(__CLASS__.': property "'.$name.'" type incorrect');
        }
        if (!isset(static::$entity_spec[static::$__table]['fields'])) {
            static::$entity_spec[static::$__table]['fields'] = array();
        }
        if ($high_priority) {
            static::$entity_spec[static::$__table]['fields'] = array_merge(array($name => $type), static::$entity_spec[static::$__table]['fields']);
        } else {
            static::$entity_spec[static::$__table]['fields'][$name] = $type;
        }
        return true;
    }

    /**
    * Returns value of class propertie
    *
    * @param mixed $name
    * @return mixed
    */
    public function __get ($name)
    {
        if ($name == '__table' || $name == '__uid') {
            return static::${$name};
        }

        if (!isset($this->values[$name])) {
            throw new Exception('Property "'.$name.'" not exist in class "'.__CLASS__.'"');
        }

        if ($this->values[$name] instanceof RexDBType) {
            if (!is_null($this->get_uid) && $this->values[$name]->source == REXDB_FIELD_DEFAULT) {
                $this->getFromDb();
            }
            if ($this->values[$name] instanceof RexDBAncor) {
                return $this->values[$name];
            } else {
                return $this->values[$name]->get();
            }
        } elseif ($this->values[$name] instanceof RexDBArray) {
            $this->values[$name]->reinit();
            return $this->values[$name];
        } else {
            return $this->values[$name];
        }
    }

    /**
    * Set value of class propertie, for interface RexDBType only, read-only others
    *
    * @param string $name
    * @param mixed $value
    * @return void
    */
    public function __set ($name, $value)
    {
        if (!isset($this->values[$name])) {
            throw new Exception('Property "'.$name.'" not exist in class "'.__CLASS__.'"');
        }

        if (!$this->values[$name] instanceof RexDBType) {
            throw new Exception(__CLASS__.': set allow only for RexDBType');
        }

        return $this->values[$name]->set($value, REXDB_FIELD_SET);
    }

    /**
     * Returns true if property has Ancor entity
     *
     * @param string $name
     */
    public static function hasAncor($name)
    {
        if (!isset($this->values[$name])) {
            return false;
        }
        if (!$this->values[$name] instanceof RexDBAncor) {
            return false;
        }
        return true;
    }

    /**
    * Get ancored entity
    *
    * @param string $name ancor field
    */
    public function getAncor($name)
    {
        if (!isset($this->values[$name])) {
            throw new Exception('Property "'.$name.'" not exist in class "'.__CLASS__.'"');
        }

        if (!$this->values[$name] instanceof RexDBAncor) {
            throw new Exception(__CLASS__.': get ancor allow only for RexDBAncor');
        }

        return $this->values[$name]->entity;
    }

    /**
    * Set entity uid to ancor field value
    *
    * @param string $name
    * @param RexDBEntity $entity
    */
    public function setAncor($name, RexDBEntity $entity)
    {
        if (!isset($this->values[$name])) {
            throw new Exception('Property "'.$name.'" not exist in class "'.__CLASS__.'"');
        }

        if (!$this->values[$name] instanceof RexDBAncor) {
            throw new Exception(__CLASS__.': set ancor allow only for RexDBAncor');
        }

        return $this->values[$name]->set($entity, REXDB_FIELD_SET);
    }

    /**
    * Set list, which contain entity
    *
    * @param RexDBList $list
    */
    public function setList(RexDBList $list)
    {
        $this->list = $list;
        return $this;
    }

    /**
    * Returns object for work with entities list
    *
    * @param int $cache_clock_ids Size of cache block, used in list creating
    * @return RexDBList
    */
    public function getList($path = array(), $cache_block_ids = 50)
    {
        if (!$path && !is_null($this->list)) {
            return $this->list;
        }
        
        $list = new RexDBList($this, $cache_block_ids);
        $list->getByFields(array($this->__uid => $this->values[$this->__uid]));
        if (is_null($this->list)) {
            $list->list = $list;
        }
        
        if ($path) {
            return $list->getList($path);
        }
        return $list;
    }

    /**
     * Set parent entity specification - field in current entity with parent uid and parent entity class
     *
     * @param string $parent_field
     * @param mixed $parent_class
     */
    public static function setParentSpec($parent_field, $parent_class)
    {
        if (!is_string($parent_class)) {
            $parent_class = get_class($parent_class);
        }
        if (substr($parent_class, -6) != 'Entity') {
            $parent_class = get_class(RexFactory::entity($parent_class));
        }
        if (static::$__table{0} != '`') {
            static::$__table = static::escapeTable(static::$__table);
        }
        static::$entity_spec[static::$__table]['parent'] = array(
            'class' => $parent_class,
            'field' => $parent_field
        );
        if (!isset(static::$entity_spec[static::$__table]['class'])) {
            throw new Exception('Set entity class first (Check that entity class name and file name are the same)');
        }
        static::registerConnection('parent',
                $parent_class, $parent_class::getUid(),
                static::$entity_spec[static::$__table]['class'], $parent_field);
    }

    /**
     * Set child entity specification - field in current entity with child uid and child entity class
     *
     * @param string $child_field
     * @param mixed $child_class
     */
    public function setChildSpec($child_field, $child_class)
    {
        if (!is_string($child_class)) {
            $child_class = get_class($child_class);
        }
        if (substr($child_class, -6) != 'Entity') {
            $child_class = get_class(RexFactory::entity($child_class));
        }
        if (static::$__table{0} != '`') {
            static::$__table = static::escapeTable(static::$__table);
        }
        static::$entity_spec[static::$__table]['child'] = array(
            'class' => $child_class,
            'field' => $child_field
        );
        if (!isset(static::$entity_spec[static::$__table]['class'])) {
            throw new Exception('Set entity class first (Check that entity class name and file name are the same)');
        }
        static::registerConnection('child',
                $child_class, $child_class::getUid(),
                static::$entity_spec[static::$__table]['class'], $child_field);
                
        return $this;
    }

    /**
     * True if is set parent spec
     *
     * @return bool
     */
    public static function hasParent()
    {
        return isset(static::$entity_spec[static::$__table]['parent']);
    }

    /**
     * True if is set child spec
     *
     * @return bool
     */
    public static function hasChild()
    {
        return isset(static::$entity_spec[static::$__table]['child']);
    }

    /**
     * Returns parent spec
     *
     * @return array
     */
    public static function getParentSpec()
    {
        if (!static::hasParent()) {
            throw new Exception(__CLASS__.': parent entity not exist');
        }
        return static::$entity_spec[static::$__table]['parent'];
    }

    /**
     * Returns child spec
     *
     * @return array
     */
    public static function getChildSpec()
    {
        if (!static::hasChild()) {
            throw new Exception(__CLASS__.': child entity not exist');
        }
        return static::$entity_spec[static::$__table]['child'];
    }

    /**
     * Set parent entity for current entity
     *
     * @param RexDBEntity $entity
     */
    public function setParent(RexDBEntity $entity)
    {
        if (!$this->hasParent()) {
            throw new Exception(__CLASS__.': parent entity not exist');
        }
        $parent_class = $this::$entity_spec[$this::$__table]['parent']['class'];
        $parent_field = $this::$entity_spec[$this::$__table]['parent']['field'];
        if (!is_object($entity) || get_class($entity) != $parent_class) {
            throw new Exception(__CLASS__.': class of parent entity must be '.$parent_class);
        }
        $this->parent_entity = $entity;
        $parent_id = $this->parent_entity->{$this->parent_entity->__uid};
        $this->values[$parent_field]->set($parent_id, REXDB_FIELD_SET);
        
        return $this;
    }

    /**
     * Set child entity for current entity
     *
     * @param RexDBEntity $entity
     */
    public function setChild(RexDBEntity $entity)
    {
        if (!$this->hasChild()) {
            throw new Exception(__CLASS__.': child entity not exist');
        }
        $child_class = $this::$entity_spec[$this::$__table]['child']['class'];
        $child_field = $this::$entity_spec[$this::$__table]['child']['field'];
        if (!is_object($entity) || get_class($entity) != $child_class) {
            throw new Exception(__CLASS__.': class of child entity must be '.$child_class);
        }
        $this->child_entity = $entity;
        $child_id = $this->child_entity->{$this->child_entity->__uid};
        $this->values[$child_field]->set($child_id, REXDB_FIELD_SET);
        
        return $this;
    }

    /**
     * Returns parent object for current entity
     *
     * @return RexDBEntity
     */
    public function getParent()
    {
        if (!isset($this::$entity_spec[$this::$__table]['parent'])) {
            throw new Exception(__CLASS__.': parent entity not exist');
        }
        if (is_null($this->parent_entity)) {
            $parent_class = $this::$entity_spec[$this::$__table]['parent']['class'];
            $parent_field = $this::$entity_spec[$this::$__table]['parent']['field'];

            $parent_id = $this->__get($parent_field);
            $this->parent_entity = new $parent_class($parent_id);
        }
        return $this->parent_entity;
    }

    /**
     * Returns child object for current entity
     *
     * @return RexDBEntity
     */
    public function getChild()
    {
        if (!isset($this::$entity_spec[$this::$__table]['child'])) {
            throw new Exception(__CLASS__.': child entity not exist');
        }
        if (is_null($this->child_entity)) {
            $child_class = $this::$entity_spec[$this::$__table]['child']['class'];
            $child_field = $this::$entity_spec[$this::$__table]['child']['field'];

            $child_id = $this->__get($child_field);
            $this->child_entity = new $child_class($child_id);
        }
        return $this->child_entity;
    }

    /**
     * Returns parent uid for current entity
     *
     * @return RexDBEntity
     */
    public function getParentId()
    {
        if (!isset($this::$entity_spec[$this::$__table]['parent'])) {
            throw new Exception(__CLASS__.': parent entity not exist');
        }
        $parent_field = $this::$entity_spec[$this::$__table]['parent']['field'];
        return $this->__get($parent_field);
    }

    /**
     * Returns child uid for current entity
     *
     * @return RexDBEntity
     */
    public function getChildId()
    {
        if (!isset($this::$entity_spec[$this::$__table]['child'])) {
            throw new Exception(__CLASS__.': child entity not exist');
        }
        $child_field = $this::$entity_spec[$this::$__table]['child']['field'];
        return $this->__get($child_field);
    }


    /**
    * Get db row corresponded to key propertie value
    *
    * @param mixed $id
    * @return bool
    */
    public function get($id = null)
    {
        foreach ($this->fields as $field) {
            $this->values[$field]->source = REXDB_FIELD_DEFAULT;
        }
        
        if (!$this->getByFields(array($this::$__uid => $id))) {
            return $this;
        }
        
        $this->values[$this::$__uid]->set($id, REXDB_FIELD_GET);
        $this->get_uid = $id;

        foreach ($this::$entity_spec[$this::$__table]['fields'] as $field => $type) {
            if ($type instanceof RexDBArray) {
                $this->values[$field]->getByWhere('TRUE');
            }
        }

        return $this;
    }

    protected function getFromDb()
    {
        if (is_null($this->get_uid)) {
            throw new Exception(__CLASS__.': Set uid first');
        }
        $id = $this->get_uid;
        $get_fields = array();
        foreach ($this->fields as $field) {
            if ($field != $this::$__uid && $this->values[$field]->source == REXDB_FIELD_DEFAULT) {
                $get_fields[] = $this::escapeName($field);
            }
        }
        if ($get_fields) {
            $sql = 'SELECT '.implode(', ', $get_fields).' FROM '.$this::$__table.' WHERE '.$this::escapeName($this::$__uid).' = "'.$id.'" LIMIT 1';
            $result = XDatabase::getRow($sql);
            if (XDatabase::isError()) {
                if (RexConfig::isTrue('Core', 'RexDB', 'check_db_struct_on_error') && $this->checkDbStruct()) {
                    $result = XDatabase::getRow($sql);
                }
                if (XDatabase::isError()) {
                    echo $sql;
                    throw new Exception('DBError: '.XDatabase::getError(true));
                }
            }
            if (!$result) {
                throw new Exception(__CLASS__.': Element with id "'.$id.'" not exist');
            }
            $this->values[$this::$__uid]->source = REXDB_FIELD_GET;
            $this->setFromArray($result, REXDB_FIELD_GET);
        }
        $this->get_uid = null;
    }

    /**
    * Update db row corresponded to key propertie value
    *
    * @return bool
    */
    public function update()
    {
        $values = array();
        foreach ($this->fields as $field) {
            if ((!is_null($this->get_uid) && $this->values[$field]->source == REXDB_FIELD_SET)
                || (is_null($this->get_uid) && $this->values[$field]->source != REXDB_FIELD_GET))
            {
                $values[$field] = $this::escapeName($field).'='.$this->values[$field];
            }
        }
        if ($values) {
            $sql = 'UPDATE '.$this::$__table.'
                    SET '.implode(', ', $values).'
                    WHERE '.$this::escapeName($this::$__uid).' = '.$this->values{$this::$__uid};
            XDatabase::query($sql);
            if (XDatabase::isError()) {
                if (RexConfig::isTrue('Core', 'RexDB', 'check_db_struct_on_error') && $this->checkDbStruct()) {
                    XDatabase::query($sql);
                }
                if (XDatabase::isError()) {
                    throw new Exception('DBError: '.XDatabase::getError(true));
                }
            }
            foreach ($values as $field => $set) {
                $this->values[$field]->source = REXDB_FIELD_GET;
            }
        }
        return $this;
    }

    /**
    * Create db row from object properties
    *
    * @return bool
    */
    public function create()
    {
        $values = array();
        foreach ($this->fields as $field) {
            if ($field != $this::$__uid || !empty($this->values[$field])) {
                $values[] = $this::escapeName($field).'='.$this->values[$field];
            }
        }
        $sql = 'INSERT
                INTO '.$this->__table.'
                SET '.implode(', ', $values);
                
        XDatabase::query($sql);

        if (XDatabase::isError()) {
            if (RexConfig::isTrue('Core', 'RexDB', 'check_db_struct_on_error') && $this->checkDbStruct()) {
                XDatabase::query($sql);
            }
            if (XDatabase::isError()) {
                throw new Exception('DBError: '.XDatabase::getError(true));
            }
        }
        $this->values[$this->__uid]->set(XDatabase::getLastInsertID(), REXDB_FIELD_GET);
        foreach ($this->fields as $field) {
            $this->values[$field]->source = REXDB_FIELD_GET;
        }
        return $this;
    }

    /**
    * Remove corresponding row in db table. After delete reset fields to default state
    *
    */
    public function delete()
    {
        foreach ($this::$entity_spec[$this::$__table]['fields'] as $field => $type) {
            if ($type instanceof RexDBArray) {
                $this->values[$field]->getByWhere('TRUE');
                $this->values[$field]->clear();
            }
        }
        $sql = 'DELETE
                FROM '.$this::$__table.'
                WHERE '.$this::escapeName($this::$__uid).'='.$this->values[$this::$__uid];
        XDatabase::query($sql);
        if (XDatabase::isError()) {
            if (RexConfig::isTrue('Core', 'RexDB', 'check_db_struct_on_error') && $this->checkDbStruct()) {
                XDatabase::query($sql);
            } 
            if (XDatabase::isError()) {
                throw new Exception('DBError: '.XDatabase::getError(true));
            }
        }
        $this->resetFields();
        return $this;
    }

    /**
    * Record array data to inner properties
    *
    * @param mixed $values
    * @return void
    */
    public function set($values = array())
    {
        $this->setFromArray($values, REXDB_FIELD_SET);
        return $this;
    }

    /**
     * Set array data to inner properties, with properties types
     *
     * @param mixed $values
     * @param int $type
     */
    protected function setFromArray($values = array(), $type = REXDB_FIELD_SET)
    {
        if (!is_array($values) && !is_object($values)) {
            $values = array($values);
        }
        if (sizeof($values)) {
            list($first_key) = array_slice(array_keys($values), 0, 1);
            if ($first_key == (string)intval($first_key)) {
                $values = array_values($values);
                foreach ($this->fields as $key => $field) {
                    if ($field{0} != '_' && $field != $this::$__uid) {
                        $value = array_slice($values, $key, 1);
                        if (!$value) {
                            break;
                        }
                        $this->values[$field]->set($value[0], $type);
                    }
                }
            } else {
                foreach ($values as $name => $value) {
                    if (isset($this::$entity_spec[$this::$__table]['fields'][$name]) && $this::$entity_spec[$this::$__table]['fields'][$name] instanceof RexDBType) {
                        $this->values[$name]->set($value, $type);
                    }
                }
            }
        }
    }

    /**
    * Returns array representation of inner data, without lists
    *
    * @return array
    */
    public function getArray()
    {
        $result = array();
        foreach ($this->fields as $field) {
            $result[$field] = $this->__get($field);
        }
        return $result;
    }

    /**
    * Update DB table struct to described in entity
    *
    * @return bool
    */
    public static function checkDbStruct($table = null, $uid = null, $field_spec = null)
    {
        if (is_null($table)) {
            $table = static::$__table;
        } else {
            $table = implode('.', array_map(array('RexDBEntity', 'escapeName'), explode('.', static::$__table)));
        }
        if (is_null($uid)) {
            $uid = static::$__uid;
        }
        if (is_null($field_spec)) {
            $field_spec = static::$entity_spec[$table]['fields'];
        }
        if (!$table || !$uid || !$field_spec) {
            throw new Exception(__CLASS__.': wrong parameters');
        }

        XDatabase::resetError();
        $table_explode = explode('.', $table);
        $sql = 'SHOW TABLES';
        if (sizeof($table_explode) > 1) {
            $db_name = static::escapeName($table_explode[0]);
            $sql .= ' FROM '.$db_name;
        }
        $table_name = $table_explode{sizeof($table_explode) - 1};
        if ($table_name{0} == '`' && $table_name{strlen($table_name) - 1} == '`') {
            $table_name = substr($table_name, 1, strlen($table_name) - 2);
        }
        $sql .= ' LIKE "'.$table_name.'"';
        $table_exist = XDatabase::getOne($sql);
        if (XDatabase::isError()) {
            throw new Exception('DBError: '.XDatabase::getError(true));
        }
        //TODO: Add table find for column specification (rename table)
        if (!$table_exist) {
            $sql = 'CREATE TABLE '.$table." (\n";
            foreach ($field_spec as $field => $propertie) {
                if ($propertie instanceof RexDBType) {
                    $sql .= static::escapeName($field).' '.$propertie->type.' ';
                    if (($propertie->flags & REXDB_FIELD_NOTNULL && !$propertie instanceof RexDBText) || $field == $uid) {
                        $sql .= 'NOT NULL';
                    } else {
                        $sql .= 'NULL';
                    }
                    if ($field == $uid && $propertie instanceof RexDBInt) {
                        $sql .= ' AUTO_INCREMENT';
                    }
                    $sql .= ",\n";
                }
            }
            $sql .= 'PRIMARY KEY ('.static::escapeName($uid).")\n";
            $sql .= ') ENGINE=MYISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8';
            XDatabase::query($sql);
            if (XDatabase::isError()) {
                echo $sql;
                throw new Exception('DBError: '.XDatabase::getError(true));
            }
            return true;
        }

        $sql = 'SHOW COLUMNS FROM '.$table;
        $column_data = XDatabase::getAll($sql);

        $columns = array();
        $last_name = false;

        $for_add = array();
        $for_delete = array();

        foreach ($field_spec as $field => $propertie) {
            if ($propertie instanceof RexDBType) {
                $escape_field = static::escapeName($field);
                $columns[$field] = array(
                    'Field' => $escape_field,
                    'New_name' => $escape_field,
                    'Type' => $propertie->type,
                    'Null' => $propertie->flags & REXDB_FIELD_NOTNULL || $field == $uid ? 'NO' : 'YES',
                    'Key' => $field == $uid ? 'PRI' : '',
                    'Autoincrement' => $field == $uid && $propertie instanceof RexDBInt,
                    'After' => $last_name ? 'AFTER '.$last_name : 'FIRST'
                );
                $last_name = $escape_field;

                $for_add[$field] = true;
            }
        }

        foreach ($column_data as $field_data) {
            if (isset($columns[$field_data['Field']])) {
                unset($for_add[$field_data['Field']]);
            } else {
                $for_delete[$field_data['Field']] = $field_data;
            }
        }

        //if type in add and delete array is equal - just rename field
        $for_unset_add = array();
        $for_unset_delete = array();
        foreach ($for_add as $name_add => $add_value) {
            foreach ($for_delete as $name_delete => $delete_value) {
                if ($delete_value['Type'] == $columns[$name_add]['Type']) {
                    $columns[$name_add]['Field'] = $name_delete;
                    $for_unset_add[$name_add] = $name_add;
                    unset($for_delete[$name_delete]);
                    break;
                }
            }
        }

        foreach ($for_unset_add as $unset_add) {
            unset($for_add[$unset_add]);
        }

        $sql = 'ALTER TABLE '.$table."\n";
        $commands = array();
        foreach ($for_delete as $delete_name => $delete_value) {
            if ($delete_name{0} != '_') {
                $commands[] = 'DROP '.static::escapeName($delete_name);
            }
        }
        foreach ($columns as $name => $column) {
            $is_add = isset($for_add[$name]);
            $commands[] = ($is_add ? 'ADD' : 'CHANGE').' '.
                static::escapeName($column['Field']).' '.
                ($is_add ? '' : static::escapeName($column['New_name']).' ').
                $column['Type'].' '.
                ($column['Null'] == 'NO' ? 'NOT NULL' : 'NULL').' '.
                (isset($column['Autoincrement']) &&  $column['Autoincrement'] ? 'AUTO_INCREMENT' : '').' '.
                $column['After'];
            if ($is_add && $column['Key'] == 'PRI') {
                $commands[] = 'ADD PRIMARY KEY ('.static::escapeName($column['Field']).')';
            }
        }
        $sql .= implode(",\n", $commands);
        XDatabase::query($sql);
        if (XDatabase::isError()) {
            echo $sql;
            throw new Exception('DBError: '.XDatabase::getError(true));
        }
        return true;
    }

    /**
    * Used in foreach for initialization before cycle
    *
    * @return void
    */
    public function rewind()
    {
        $this->position = 0;
        return $this;
    }

    /**
    * Returns element for current offset (array position)
    *
    * @return mixed
    */
    public function current()
    {
        return $this->offsetGet($this->fields[$this->position]);
    }

    /**
    * Returns current offset (array fosition). Used in foreach
    *
    * @return mixed
    */
    public function key()
    {
        return $this->fields[$this->position];
    }

    /**
    * Next offset. Used in foreach
    *
    * @return mixed
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
        return $this->position >= 0 && $this->position < sizeof($this->fields);
    }

    /**
    * Returns fields count
    *
    * @return int
    */
    public function count()
    {
        return sizeof($this->fields);
    }

    /**
    * Implementation of the function set
    *
    * @param string $offset
    * @param mixed $value
    */
    public function offsetSet($offset, $value)
    {
        if (!$this->offsetExists($offset)) {
            throw new Exception(__CLASS__.': offset "'.$offset.'" not exist');
        }
        $this->values[$offset]->set($value, REXDB_FIELD_SET);
        return $this;
    }

    /**
    * Implementation of the function isset
    *
    * @param int $offset
    */
    public function offsetExists($offset)
    {
        return $offset && isset($this->values[$offset]);
    }

    /**
    * Implementation of the function unset
    *
    * @param int $offset
    */
    public function offsetUnset($offset)
    {
        if (!$offset || !isset($this->values[$offset])) {
            throw new Exception(__CLASS__.': offset "'.$offset.'" not exist');
        }
        if ($this->values[$offset] instanceof RexDBType) {
            $this->values[$offset] = clone $this::$entity_spec[$this::$__table]['fields'][$offset];
            $this->values[$offset]->status = REXDB_FIELD_SET;
        } elseif ($this->values[$offset] instanceof RexDBArray) {
            $this->values[$offset]->clear();
        } else {
            throw new Exception(__CLASS__.': unsupported field type');
        }
        return $this;
    }

    /**
    * Implementation of array access
    *
    * @param int $offset
    */
    public function offsetGet($offset)
    {
        return $this->__get($offset);
        /*if (!$this->offsetExists($offset)) {
            throw new Exception(__CLASS__.': index "'.$offset.'" not exist');
        }
        if (!is_null($this->get_uid) && $this->values[$offset]->source == REXDB_FIELD_DEFAULT) {
            $this->getFromDb();
        }
        return $this->values[$offset]->get();*/
    }
}