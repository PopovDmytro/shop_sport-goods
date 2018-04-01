<?php
namespace RexShop;

use \RexDBEntity as RexDBEntity;
use \RexDBInt as RexDBInt;
use \RexDBString as RexDBString;
use \RexDBFloat as RexDBFloat;
use \XDatabase as XDatabase;

/**
 * Class Prod2OrderEntity
 *
 * Entity of SiteEntities
 *
 * @author   Fatal
 * @access   public
 * @created  Wed Jan 24 12:05:33 EET 2008
 */
class Prod2OrderEntity extends RexDBEntity
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    
    protected static $__table = "prod2order";
    protected static $__uid   = "id";
    
    protected static function initTypes()
    {
        static::add('id', new RexDBInt());
        static::add('order_id', new RexDBInt());
        static::add('product_id', new RexDBInt());
        static::add('count', new RexDBInt());
        static::add('discount', new RexDBInt());
        static::add('price', new RexDBFloat());
        static::add('attributes', new RexDBString());
        static::add('sku', new RexDBInt());
    }

    function getProductPriceBySku($sku) 
    {
        $sql = 'SELECT price FROM sku WHERE id = '.$sku;   
        return XDatabase::getOne($sql);
    }
    
    function getProductPriceBySkuArticle($sku) 
    {
        $sql = 'SELECT price FROM sku WHERE sku_article = '.$sku;   
        return XDatabase::getOne($sql);
    }
    
    function getProductPriceByProductId($id) 
    {
        $sql = 'SELECT price FROM product WHERE id = '.$id;   
        return XDatabase::getOne($sql);
    }
    
    function getProductSaleByProductId($id) 
    {
        $sql = 'SELECT sale FROM product WHERE id = '.$id;   
        return XDatabase::getOne($sql);
    }   
    
    //перенести из SYSTEM!!!!
    
    function getProductOptPriceBySku($sku) 
    {
        $sql = 'SELECT price_opt FROM sku WHERE id = '.$sku;   
        return XDatabase::getOne($sql);
    }
    
    function getProductOptPriceByProductId($id) 
    {
        $sql = 'SELECT price_opt FROM product WHERE id = '.$id;   
        return XDatabase::getOne($sql);
    }  
}