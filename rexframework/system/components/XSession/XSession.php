<?php

session_name('SESSION'.config('sysname'));

require_once(dirname(__FILE__) . "/SessionDB.php");

class XSession 
{
    var $_session_id;
    var $_container;
    var $_session_type;

    function XSession() {
        global $XSession;
        if (!isset($XSession)) $XSession = $this;
    }

    function create() {
        return new XSession();
    }

    function setSessionId() {
        if (!session_id()) { session_start(); }
        $this->_session_id = session_id();
    }

    function _init() {
        global $XSession;
        if (!$XSession) $XSession = $this;
    }

    function _setContainer($type = null) {
        global $XSession;
        if (!isset($XSession)) XSession::create();
        $XSession->_session_type = $type;
        if ($type == "db") {
            $XSession->_container = new SessionDB();
            return true;
        }
        return false;
    }

    public static function get($aKey) {
        global $XSession;

        if (!isset($XSession)) XSession::create();
        $XSession->setSessionId();
        if ($XSession->_setContainer()) {
            return $XSession->_container->get($XSession->_session_id, $aKey);
        }
        return array_key_exists($aKey, $_SESSION) ? $_SESSION[$aKey] : null;
    }

    public static function set($aKey, $aValue) {
        global $XSession;
        if (!isset($XSession)) XSession::create();
        $XSession->setSessionId();
        if ($XSession->_setContainer()) {
            return $XSession->_container->set($XSession->_session_id, $aKey, $aValue);
        }
        if ($aValue !== null) $_SESSION[$aKey] = $aValue;
        elseif(isset($_SESSION[$aKey])) XSession::remove($_SESSION[$aKey]);
    }

    public static function remove($aKey) {
        global $XSession;
        if (!isset($XSession)) XSession::create();
        $XSession->setSessionId();
        if ($XSession->_setContainer()) {
            return $XSession->_container->remove($XSession->_session_id, $aKey);
        }
        if (!session_id()) { session_start(); }
        unset($_SESSION[$aKey]);
    }

    public static function removeall() {
        global $XSession;
        if (!isset($XSession)) XSession::create();
        $XSession->setSessionId();
        if ($XSession->_setContainer()) {
            return $XSession->_container->removeAll($XSession->_session_id);
        }
        if (!session_id()) { session_start(); }
        $_SESSION = array();
    }

    public static function destroy() {
        if (!session_id()){ session_start(); }
        session_destroy();
    }
}