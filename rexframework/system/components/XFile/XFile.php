<?php
class XFile
{
    static protected $__error;

    function XFile($aProperty = null) 
    { 
    }

    static public function getExtension($aField) 
    {
        if (!preg_match('#^.+\.([a-z0-9]{2,4})$#is', $_FILES[$aField]['name'], $subpattern)) {
           static::_setError('Extension is empty');
            return false; 
        } else {
            $extension = strtolower($subpattern[1]);
            if ($extension == 'php' or $extension == 'html' or $extension == 'htm') { 
                static::_setError('Warning extension!');    
                return false;
            } else {
                return $extension;
            }
        } 
    }

    static public function getFileName($aField) 
    {
        if (!preg_match('#^(.+)\.[a-z0-9]{2,4}$#is', $_FILES[$aField]['name'], $subpattern)) {
            static::_setError('FileName is empty');
            return false;
        } else {
            $filename = strtolower($subpattern[1]);
            return $filename;
        } 
    }

    static public function getType($aField) 
    {
        if (isset($_FILES[$aField]['type'])) {
            return $_FILES[$aField]['type'];
        } else {
            static::_setError('Type is empty');
            return false;
        } 
    }
    
    static public function getSize($aField) 
    {
        if (isset($_FILES[$aField]['size'])) {
            return $_FILES[$aField]['size'];
        } else {
            static::_setError('Size is empty');
            return false;
        } 
    }

    static public function upload($aField, $aPath) 
    {
        if (!file_exists($_FILES[$aField]['tmp_name'])) {
            static::_setError('TMP File is empty');
            return false;
        }     

        try {
            @unlink($aPath);
        } catch (Exception $e) {};
        if(!copy($_FILES[$aField]['tmp_name'], $aPath)) {
            static::_setError('Error copy file');
            return false;
        } else { 
            try {
                @unlink($_FILES[$aField]['tmp_name']);
            } catch (Exception $e) {};
            return true;
        }
    }

    static public function delete($aPath) 
    {
        try {
            @unlink($aPath);
        } catch (Exception $e) {};
        return true;
    }

    static public function _setError($aError)
    {
        static::$__error = $aError;
    }


    static public function isError()
    {
        if (static::$__error) {
            return true;
        } else {
            return false;
        }
    }

    static public function getError()
    {
        return static::$__error;
    }
}