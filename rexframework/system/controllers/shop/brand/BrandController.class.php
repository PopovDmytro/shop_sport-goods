<?php
namespace RexShop;

use \RexFactory as RexFactory;
use \RexDisplay as RexDisplay;
use \RexPage as RexPage;

/**
 * Class BrandController
 *
 * Category Controller Class
 *
 * @author   Fatal
 * @access   public
 * @package  BrandController.class.php
 * @created  Sun Jan 20 10:38:56 EET 2008
 */
class BrandController extends \RexFramework\ParentController
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexShop\BrandEntity:shop.standart:1.0',
        'RexShop\BrandManager:shop.standart:1.0',
    );
	
	function getDefault()
	{
		$this->entity = RexFactory::entity($this->mod);
		if (!$this->entity->getByFields(array('alias' => $this->task))) {
			exit;
		}
        
		RexDisplay::assign('brand', $this->entity);
        
		$pattern = false;
		if (strlen(trim($this->entity->title)) > 0) {
			RexPage::setTitle($this->entity->title);
		} elseif ($pattern !== false and strlen(trim($pattern)) > 0) {
			$title = str_replace('{name}', $this->entity->name, $pattern);
			RexPage::setTitle($title);
		} else {
			RexPage::setTitle($this->entity->name);
		}
		
		RexPage::setDescription($this->entity->description);
		RexPage::setKeywords($this->entity->keywords);
	}
	
	function getList($aParams) //smarty func
	{
		$brandManager = RexFactory::manager($this->mod);
		$brandManager->getByWhere('1=1 ORDER BY `name`');
		RexDisplay::assign($aParams['saveto'], $brandManager->getCollection());
	}
}