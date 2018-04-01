<?php

    /**
    * Class Entity
    *
    * entity
    *
    * @author   Fatal
    * 
    * @access   public
    * 
    * @package  XFramework
    * 
    * @created  Wed Jan 19 09:04:37 EEST 2007
    */
    class Entity extends RexObject
    {
        /**
        * validate errors
        *
        * @var array
        */
        var $__validate_errors;

        /**
        * Constructor of Entity
        *
        * @access  public
        */
        public function __construct($aID = null) 
        {
            parent::__construct();
        }

        /**
        * _setError
        *
        * set error message for current field
        *
        * @access  protected
        * @param   string     $aElement   element name
        * @param   string     $aMessage   error message
        * @return  bool
        */
        public function _setError($aElement, $aMessage = '') 
        {
            if (empty($this->__validate_errors[$aElement])) {
                if (array_key_exists($aElement, get_object_vars($this))) {
                    $this->__validate_errors[$aElement] = $aMessage;
                    return true;
                }
            } else {
                return false;
            }
        }

        /**
        * getValidateErrors
        *
        * return array of errors
        *
        * @access  public
        * @return  array
        */
        public function getValidateErrors() 
        {
            return $this->__validate_errors;
        }

        /**
        * validate Entity
        *
        * @access  public
        * 
        * @return  bool
        */
        public function validate() 
        {
            return true;
        }

        /**
        * is valid Entity
        *
        * @access  public
        * 
        * @return  bool
        */
        public function isValid() 
        {
            if (empty($this->__validate_errors)) {
                return true;
            } else {
                return false;   
            }	     
        }

        /**
        * set Entity
        *
        * @access  public
        * 
        * @param  array $aParams
        * 
        * @return  bool
        */
        public function set($aParams = null) 
        {
            $flag = true;

            if ($aParams) {
                // set all params
                foreach ($aParams as $name => $value) {
                    if (array_key_exists($name, get_object_vars($this))) {
                        $this->{$name} = $value;
                    }
                }
                // validate all params
                $flag = $this->validate();
            }

            return $flag;
        }

        /**
        * create Entity
        *
        * @abstract 
        * @access  public
        * 
        * @return  bool
        */
        public function create() 
        {
            return false;
        }

        /**
        * update Entity
        *
        * @abstract 
        * @access  public
        * 
        * @return  bool
        */
        public function update() 
        {
            return false;
        }

        /**
        * delete Entity
        *
        * @abstract 
        * @access  public
        * 
        * @return  bool
        */
        public function delete() 
        {
            return false;
        }

        /**
        * getArray
        *
        * return object fields as array
        *
        * @access  public
        *
        * @return  mixed
        */
        public function getArray() 
        {
            $arr = array();

            foreach ($this as $fKey => $fValue) {
                if (!is_object($fValue) && !is_array($fValue) && substr($fKey, 0, 1) != '_') {
                    $arr[$fKey] = $fValue;
                }
            }

            return $arr;
        }
    }