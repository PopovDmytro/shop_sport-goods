<?php
namespace RexFramework;

use \XDatabase as XDatabase;

/**
 * Class ImageManager
 *
 * Manager of Image
 *
 * @author   Fatal
 * @access   public
 * @created  Wed Jan 24 12:05:33 EET 2008
 */
class ImageManager extends DBManager
{
    function __construct($aTable = 'image', $aPrimary = 'id')
    {
        parent::__construct($aTable, $aPrimary);
    }
    
    function getLatest($aCount=3)
    {
        if (!$aCount or $aCount < 1) {
            $aCount = 3;
        }

        $sql = 'SELECT * FROM '.$this->_table.' ORDER BY UNIX_TIMESTAMP(`date_create`) DESC LIMIT ?';
        $res = XDatabase::getAll($sql, array($aCount));
        
        if (!$res or sizeof($res) < 1) {
            $this->_collection = false;
        } else {
            $this->_collection = $res;
        }
    }
}