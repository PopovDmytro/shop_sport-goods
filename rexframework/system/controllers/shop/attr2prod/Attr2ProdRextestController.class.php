<?php
namespace RexFramework;

use \RexFactory as RexFactory;
use \XDatabase as XDatabase;
use \RexConfig as RexConfig;
use \RexDBList as RexDBList;

/**
 * Class Attr2ProdRextestController
 *
 * Attr2Prod Controller Class
 *
 * @author   Fatal
 * @access   public
 * @package  Attr2ProdRextestController.class.php
 * @created  Sun Jan 20 10:38:56 EET 2008
 */
class Attr2ProdRextestController extends ParentRextestController
{    
    static public $assemble = 'standart';
    static public $version = 1.0;
    public static $needed = array(
        'RexShop\Attr2ProdEntity:shop.standart:1.0',
        'RexShop\Attr2ProdManager:shop.standart:1.0',
    );
    
    //admin
    function testDefaultAdmin()
    {
        $get = $this->generateGet($this->mod);
        
        $this->entity = RexFactory::entity($this->mod);
        
        $product = XDatabase::getRow('SELECT pr.*, att.`id` AS "attr_id", att.`name` AS "attr_name"
                                        FROM `product` AS pr
                                        INNER JOIN `pcatalog` pc ON pc.`id` = pr.`category_id`
                                        INNER JOIN attr2cat atc ON pc.`id` = atc.`category_id`
                                        LEFT JOIN `attribute` att ON att.`id` = atc.`attribute_id`
                                        ORDER BY pr.`id` DESC
                                        LIMIT 1');
        
        if ($product) {
            $post = array(
                'product_id' => $product['id']
            );
            $post = $this->generatePost('admin', $post);
            
            $result = $this->getDataAdmin($get, $post);
            $result_decode = json_decode($result[0]);
            
            if (isset($result_decode->content->template)) {
                $this->assertRegExp('#'.$product['attr_name'].'#', $result_decode->content->template, 'Check attribute name in the page');
                $this->assertRegExp('#name=\"attribute\['.$product['attr_id'].'\]\"#', $result_decode->content->template, 'Check pcatalog alias in the edit page');
            } else {
                $this->assertEquals('error', 'ok', 'Check result by display the add page');
            }
        } else {
            $maxId = XDatabase::getOne('SELECT MAX(`id`) as "id"
                                            FROM `product`
                                            ORDER BY `id` DESC
                                            LIMIT 1');
            
            $post = array(
                'product_id' => $maxId
            );
            $post = $this->generatePost('admin', $post);
            
            $result = $this->getDataAdmin($get, $post);
            $result_decode = json_decode($result[0]);
            
            if (isset($result_decode->content->template)) {
                $this->assertRegExp('#No Records Found#', $result_decode->content->template, 'Check no records found in the page');
            } else {
                $this->assertEquals('error', 'ok', 'Check result by display the add page');
            }
        }
    }
}