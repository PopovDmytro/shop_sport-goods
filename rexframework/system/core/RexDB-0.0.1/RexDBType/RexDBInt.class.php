<?php
require_once 'RexDBType.class.php';

/**
 * Class RexDBInt
 *
 * @author   MAG
 * @access   public
 * @created  14.01.2012
 */
class RexDBInt extends RexDBType
{
    /**
    * Max value of integer
    *
    * @var int
    */
    protected $max_value;
    /**
    * Min value of integer
    *
    * @var int
    */
    protected $min_value;

    public function __construct($flags = 0, $default_value = 0, $length = 9)
    {
        $length = intval($length);
        $flags = intval($flags);

        $unsigned = $flags & REXDB_FIELD_UNSIGNED;
        if ($length < 1) {
            throw new Exception(__CLASS__.': length "'.$length.'" not correct');
        } elseif ($length < 3) {
            $this->type = 'tinyint';
        } elseif ($length < 5) {
            $this->type = 'smallint';
        } elseif (($length < 7 && !$unsigned) || ($length < 8 && $unsigned)) {
            $this->type = 'mediumint';
        } elseif ($length < 10) {
            $this->type = 'int';
        } elseif (($length < 18 && !$unsigned) || ($length < 19 && $unsigned)) {
            $this->type = 'bigint';
        } else {
            throw new Exception(__CLASS__.': length "'.$length.'" too long');
        }
        $this->type .= '('.$length.')';
        if ($unsigned) {
            $this->type .= ' unsigned';
        }

        $possibleLength = pow(2, 8 * $length);
        $this->min_value = $unsigned ? 0 : -$possibleLength / 2;
        $this->max_value = $unsigned ? $possibleLength-1 : $possibleLength / 2 - 1;

        parent::__construct($flags, $default_value);
    }

    protected function validate($value)
    {
        if (!is_null($value) && ($value > $this->max_value || $value < $this->min_value)) {
            throw new Exception(__CLASS__.': value "'.$value.'" out of range '.$this->min_value.' : '.$this->max_value);
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
            $result = 0 + $value;
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
?>
