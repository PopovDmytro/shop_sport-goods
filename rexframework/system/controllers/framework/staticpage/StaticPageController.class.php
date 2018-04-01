<?php
namespace RexFramework;

use \RexFactory as RexFactory;
use \RexDisplay as RexDisplay;
use \RexSettings as RexSettings;
use \RexPage as RexPage;

/**
 * Class StaticPageController
 *
 * Category Controller Class
 *
 * @author   Fatal
 * @access   public
 * @package  StaticPageController.class.php
 * @created  Sun Jan 20 10:38:56 EET 2008
 */
class StaticPageController extends ParentController
{
    public static $version = 1.0;
    public static $assemble = 'standart';
    public static $needed = array(
        'RexFramework\StaticPageEntity:standart:1.0',
        'RexFramework\StaticPageManager:standart:1.0'
    );
    
	function getDefault()
	{
		$this->entity = RexFactory::entity('staticPage');

		if ($this->entity->getByFields(array('alias'=>$this->task))) {
            
			RexDisplay::assign('staticpage', $this->entity);
            
			$pattern = RexSettings::get('staticpage_title');
            
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
		} else {
            if ($this->task == '') {
                header('Location: /404');
                exit;                
            }
        }
	}
	
	function getList() 
	{
		$this->manager = RexFactory::manager('staticPage');
		$this->manager->get();	
		RexDisplay::assign('staticpage_list', $this->manager->getCollection());
	}
}