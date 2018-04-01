<?php
namespace RexShop;

use \RexConfig as RexConfig;
use \QRcode as QRcode;

/**
 * Class QRCodeManager
 *
 * Manager of QRCode
 *
 * @author   Fatal
 * @access   public
 * @created  Wed Jan 24 12:05:33 EET 2008
 */
class QRCodeManager extends \RexFramework\DBManager
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexFramework\DBManager:standart:1.0',
        'QRCodeEntity:shop.standart:1.0'
    );
    
    function generateQRCode($id, $code, $level = 'M', $size = '2.4')
    {
        include "phpqrcode/qrlib.php";
        
        $upload_dir = $this->_generateUploadDirForQRCode($id);
        $filename = $upload_dir.'/main.png';
        
        QRcode::png($code, $filename, $level, $size, 2);
        
        return true;
    }
    
    function _generateUploadDirForQRCode($id)
    {
        $upload_dir = REX_ROOT.RexConfig::get('RexPath', 'image', 'storage').'qrcode/'.$id;
        
        if(!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777);
            chmod($upload_dir, 0777);
        }
        
        return $upload_dir;
    }
}