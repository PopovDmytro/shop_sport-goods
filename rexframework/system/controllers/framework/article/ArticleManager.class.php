<?php
namespace RexFramework;

use \XDatabase as XDatabase;

/**
 * Class ArticleManager
 *
 * Manager of Article
 *
 * @author   Fatal
 * @access   public
 * @created  Wed Jan 24 12:05:33 EET 2008
 */
class ArticleManager extends DBManager
{

    public function __construct()
    {
        parent::__construct('article', 'id');
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
    
    function getArchive($aStart, $aCount)
    {
        $sql = 'SELECT * FROM '.$this->_table.' ORDER BY UNIX_TIMESTAMP(`date`) DESC LIMIT '.$aStart.', '.$aCount;
        $res = XDatabase::getAll($sql);

        if (!$res or sizeof($res) < 1) {
            $this->_collection = false;
        } else {
            $this->_collection = $res;
        }
    }
}