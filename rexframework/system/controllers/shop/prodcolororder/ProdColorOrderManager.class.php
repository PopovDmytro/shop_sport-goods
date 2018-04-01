<?php
namespace RexShop;

use \RexFactory as RexFactory;
use \RexDisplay as RexDisplay;
use \RexDBList as RexDBList;
use \XDatabase as XDatabase;
use \PEAR as PEAR;

class ProdColorOrderManager extends \RexFramework\DBManager
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexFramework\DBManager:standart:1.0',
        'RexShop\ProdColorOrderEntity:shop.standart:1.0',
    );
    
    function __construct()
    {
        parent::__construct('prod_color_order', 'id');
    }

    public function deleteByProductId ($productId)
    {
        $sql = 'SELECT `value` FROM `attr2prod` WHERE attribute_id = 1 AND product_id = ?;';

        $sqlProdColorOrder = 'SELECT attribute_id FROM prod_color_order WHERE product_id = ?';

        $attributesList = XDatabase::getAll($sql, array($productId));
        $prodColorList = XDatabase::getAll($sqlProdColorOrder, array($productId));

        foreach ($prodColorList as $key => $prodColorItem) {
            foreach ($attributesList as $attributeItem) {
                if ($prodColorItem['attribute_id'] == $attributeItem['value']) {
                    unset($prodColorList[$key]);
                }
            }
        }

        foreach ($prodColorList as $key => $prodColorItem) {
            $attr2ProdEntity = RexFactory::entity('ProdColorOrder');
            $attr2ProdEntity->getByWhere('product_id = '.$productId.' AND attribute_id = '.$prodColorItem['attribute_id']);
            $attr2ProdEntity->delete();
        }

        return true;
    }
}