<?php
require_once dirname(__FILE__).'/../RexDB.consts.class.php';

/**
 * Class RexDBType
 * Base class for db type representation
 *
 * @author   MAG
 * @access   public
 * @created  15.01.2012
 *
 * @property-read string $flags
 * @property-read string $type
 * @property int $source
 */
abstract class RexDBType
{
    /**
    * Type variable value
    *
    * @var mixed
    */
    protected $value;
    /**
    * For save initialization flags
    *
    * @var int
    */
    protected $flags;
    /**
    * For save db type
    *
    * @var string
    */
    protected $type;

    /**
     * Souce type
     *
     * @var int
     */
    protected $source;

    /**
    * Base initializaition
    *
    * @param int $flags
    * @param mixed $default_value
    */
    public function __construct($flags = 0, $default_value = null)
    {
        $this->flags = $flags;
        $this->set($default_value, REXDB_FIELD_DEFAULT);
    }

    /**
    * Implements read-access for some inner variables
    *
    * @param string $name
    * @return mixed
    */
    public function __get($name) {
        if ($name == 'flags') {
            return $this->flags;
        }
        if ($name == 'type') {
            return $this->type;
        }
        if ($name == 'source') {
            return $this->source;
        }
        throw new Exception(__CLASS__.': property "'.$name.'" not exist');
    }

    /**
     * Implaments write values for some inner variables
     *
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value) {
        if ($name == 'source') {
            $this->source = $value;
            return true;
        }
        throw new Exception(__CLASS__.': property "'.$name.'" not exist or read-only');
    }

    /**
    * Returns inner value
    *
    * @return mixed
    */
    public function get()
    {
        return $this->value;
    }

    /**
    * Set inner value
    *
    * @param mixed $value
    * @param int $source
    */
    public function set($value, $source)
    {
        if ($this->validate($value)) {
            $new_value = $this->convert($value);
            if ($source != REXDB_FIELD_SET || $new_value !== $this->value || ($source == REXDB_FIELD_SET && $this->source == REXDB_FIELD_DEFAULT)) {
                $this->value = $new_value;
                $this->source = $source;
            }
        } else {
            throw new Exception(__CLASS__.': value "'.$value.'" not correct');
        }
    }

    /**
    * Function determines the correctness of value (convertibility to correct)
    *
    * @param mixed $value
    * @return bool
    */
    protected function validate($value)
    {
        if (is_null($value) && $this->flags & REXDB_FIELD_NOTNULL) {
            throw new Exception(__CLASS__.': default value must be not null, or remove NOTNULL flag');
        }
        return true;
    }

    /**
    * Function convert value to necessary type and range of data
    *
    * @param mixed $value
    * @return mixed
    */
    protected function convert($value)
    {
        return $value;
    }

    /**
    * Converting inner value to string, which used for db record
    *
    * @return string
    */
    public function __toString() {
        if (is_null($this->value)) {
            return 'NULL';
        } else {
            return '"'.addslashes($this->value).'"';
        }
    }
}
?>
