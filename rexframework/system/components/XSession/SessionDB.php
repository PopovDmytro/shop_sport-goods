<?php
class SessionDB {
    var $_table_name;
    var $_session_id;
    
    function SessionDB($session_id = null) {
        $this->_table_name = XConfig::get("table_name", "Session");
    }
    
    function set($session_id, $key, $value) {
        $value = serialize($value);
        XDatabase::query("
            REPLACE INTO " . $this->_table_name . "
            SET session_id = ?, session_key = ?, session_data = ?",
            array($session_id, $key, $value)
        );
    }
    
    function get($session_id, $key) {
        $data = XDatabase::getOne("
            SELECT session_data FROM " . $this->_table_name . "
            WHERE session_id = ? AND session_key = ?",
            array($session_id, $key)
        );
        return $data ? unserialize($data) : null;
    }
    
    function remove($session_id, $key) {
        XDatabase::query("
            DELETE FROM " . $this->_table_name . "
            WHERE session_id = ? AND session_key = ?",
            array($session_id, $key)
        );
    }
    
    function removeAll($session_id) {
        XDatabase::query("
            DELETE FROM " . $this->_table_name . "
            WHERE session_id = ?",
            array($session_id)
        );
    }
}