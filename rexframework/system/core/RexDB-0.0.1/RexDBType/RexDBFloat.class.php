<?php
require_once 'RexDBType.class.php';

/**
 * Class RexDBFloat
 *
 * @author   MAG
 * @access   public
 * @created  14.01.2012
 */
class RexDBFloat extends RexDBType
{
    /**
    * Digits count before point
    *
    * @var int
    */
    protected $before_length;
    /**
    * Digits count after point
    *
    * @var int
    */
    protected $after_length;
    /**
    * Max value of double
    *
    * @var int
    */
    protected $max_value;
    /**
    * Min value of double
    *
    * @var int
    */
    protected $min_value;

    /**
    * Basic float type initialization
    *
    * @param double $default_value
    * @param int $flags
    * @param int $default_value
    * @param int $length if 0 - maximum
    * @param int $precision if 0 - maximum
    * @return RexDBFloat
    */
    public function __construct($flags = 0, $default_value = 0, $length = 0, $precision = 0)
    {
        $length = intval($length);
        $precision = intval($precision);
        $flags = intval($flags);

        $this->type = 'double';

        $unsigned = $flags & REXDB_FIELD_UNSIGNED;
        if ($length || $precision) {
            if ($length > 18) {
                throw new Exception(__CLASS__.': length "'.$length.'" too long');
            }
            /*if ($length < $precision) {
                throw new Exception(__CLASS__.': length "'.$length.'" must be >= than precision "'.$precision.'"');
            }*/
            $this->before_length = $length;
            $this->after_length = $precision;

            $this->max_value = 0 + str_repeat('9', $this->before_length);
            if ($unsigned) {
                $this->min_value = 0;
            } else {
                $this->min_value = -$this->max_value;
            }

            $this->type .= '('.($length+$precision).','.$precision.')';
        } else {
            $this->before_length = 0;
            $this->after_length = 0;
            $this->max_value = 0;
            $this->min_value = 0;
        }

        if ($unsigned) {
            $this->type .= ' unsigned';
        }

        $this->max_value = 0 + str_repeat('9', $length);
        if ($unsigned) {
            $this->min_value = 0;
        } else {
            $this->min_value = -$this->max_value;
        }

        parent::__construct($flags, $default_value);
    }

    protected function validate($value)
    {
        $double_value = doubleval($value);
        if ($this->before_length || $this->after_length) {
            if ($double_value > $this->max_value || $double_value < $this->min_value) {
                throw new Exception(__CLASS__.': value "'.$value.'" out of range '.$this->min_value.' : '.$this->max_value);
            }
        }
        parent::validate($value);
        return true;
    }

    protected function convert($value)
    {
        $result = $value;
        if (is_null($value)) {
            $result = null;
        } else {
            $result = doubleval($value);
            if ($this->before_length || $this->after_length) {
                $result = round($result, $this->after_length);
            }
        }
        return $value;
    }

    public function __toString() {
        if (is_null($this->value)) {
            return 'NULL';
        } else {
            return (string)$this->value;
        }
    }
}