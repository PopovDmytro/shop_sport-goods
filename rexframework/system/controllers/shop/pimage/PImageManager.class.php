<?php
namespace RexShop;
use \XDatabase as XDatabase;

/**
 * Class PImageManager
 *
 * Manager of PImage
 *
 * @author   Fatal
 * @access   public
 * @created  Wed Jan 24 12:05:33 EET 2008
 */
class PImageManager extends \RexFramework\ImageManager
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexFramework\ImageManager:standart:1.0',
        'RexShop\PImageEntity:shop.standart:1.0'
    );
    
	function __construct()
	{
		parent::__construct('pimage', 'id');
	}
    
    function getCartImage($skuID)
    {
        $sql = 'SELECT 
                      p.* 
                    FROM
                      sku s 
                      INNER JOIN sku_element se 
                        ON s.`id` = se.`sku_id` 
                      INNER JOIN attr2prod ap 
                        ON se.`attr2prod_id` = ap.`id`
                      INNER JOIN attribute a ON ap.`attribute_id` = a.id AND a.`is_picture` = 1
                      INNER JOIN attribute a1 ON ap.`value` = a1.id
                      INNER JOIN pimage p ON ap.`id` = p.`attribute_id` 
                    WHERE s.id = '.$skuID.'
                    ORDER BY sorder ASC
                    LIMIT 1';
        return XDatabase::getAll($sql);
    }

    public function getImageByProductOrSku($product_id, $sku_id = false)
    {
        $sql = 'SELECT 
                  p.* 
                FROM
                  pimage p
                  '.($sku_id ?
                  'LEFT JOIN sku s 
                    ON s.id = '.$sku_id.' 
                  LEFT JOIN sku_element se 
                    ON s.`id` = se.`sku_id` 
                    AND se.`attr2prod_id` = p.`attribute_id`
                  ' : '').'
                WHERE p.product_id = '.$product_id.' 
                GROUP BY p.`id` 
                ORDER BY p.`sorder` ASC
                LIMIT 1';
        return $this->_collection = XDatabase::getAll($sql);
    }

    public function savePImageAsMain($pImageEntity)
    {
        $sqlClearMain = 'UPDATE pimage SET main = 0 WHERE product_id = '.$pImageEntity->product_id.' AND attribute_id = '.$pImageEntity->attribute_id;

        $resClear = XDatabase::query($sqlClearMain);
        if ($resClear !== null) {
            $pImageEntity->main = 1;
            return $pImageEntity->update();
        }
        return false;
    }
}