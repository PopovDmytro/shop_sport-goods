<?php
require_once dirname(__FILE__).'/../RexDB.consts.class.php';
require_once 'RexDBType.class.php';

/**
 * Class RexDBAncor
 * Class for ancor other entity for field
 *
 * @author   MAG
 * @access   public
 * @created  15.01.2012
 *
 * @property-read string $flags
 * @property-read string $type
 * @property int $source
 * @property RexDBEntity $entity
 */
class RexDBAncor extends RexDBType
{
    /**
    * Type for ancored entity uid
    *
    * @var RexDBType
    */
    protected $ancor_type;

    /**
    * Ancor class name
    *
    * @var string
    */
    protected $ancor_class;

    /**
    * Base initializaition
    *
    * @param int $flags
    * @param mixed $default_value
    */
    public function __construct($ancor)
    {
        $ancor_entity = is_string($ancor) ? RexFactory::entity($ancor) : $ancor;
        $ancor_class = get_class($ancor_entity);
        if (!is_subclass_of($ancor_class, 'RexDBEntity')) {
            throw new Exception('Submitted class "'.$ancor_class.'", which is not a child of RexDBEntity');
        }
        if (!$ancor_class::getTable() || !$ancor_class::getUid()) {
            throw new Exception('Uncorrect info for db table in class "'.$ancor_class.'"');
        }

        $this->ancor_type = null;
        $this->ancor_class = $ancor_class;
    }

    protected function checkAncor()
    {
        if (is_null($this->ancor_type)) {
        	$ancor_class = $this->ancor_class;
            $ancor_spec = $ancor_class::getFieldSpec();
            $this->ancor_type = clone $ancor_spec[$ancor_class::getUid()];
        }
    }

    /**
    * Implements read-access for some inner variables
    *
    * @param string $name
    * @return mixed
    */
    public function __get($name) {
        $this->checkAncor();
        if ($name == 'ancor_class') {
        	return $this->ancor_class;
        } elseif ($name == 'entity') {
            $ancor_class = $this->ancor_class;
            $entity = new $ancor_class();
            $entity->get($this->ancor_type->get());
            return $entity;
        } else {
            return $this->ancor_type->__get($name);
        }
    }

    /**
     * Implaments write values for some inner variables
     *
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value) {
        $this->checkAncor();
        $this->ancor_type->__set($name, $value);
    }

    /**
    * Returns inner value
    *
    * @return mixed
    */
    public function get()
    {
        $this->checkAncor();
        return $this->ancor_type->get();
    }

    /**
    * Set inner value
    *
    * @param mixed $value
    * @param int $source
    */
    public function set($value, $source)
    {
        $this->checkAncor();
        if (is_object($value)) {
            if (!$value instanceof $this->ancor_class) {
                throw new Exception('Only entity of class "'.$this->ancor_class.'" allowed');
            }
            $this->ancor_type->set($value->{$ancor_class::getUid}, $source);
        } else {
            $this->ancor_type->set($value, $source);
        }
    }

    /**
    * Converting inner value to string, which used for db record
    *
    * @return string
    */
    public function __toString()
    {
        $this->checkAncor();
        return $this->ancor_type->__toString();
    }
    
    public function __clone()
    {
        $this->ancor_type = clone $this->ancor_type;
    }
}
?>
