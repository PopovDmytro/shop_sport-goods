<?php
namespace RexShop;

use \RexFactory as RexFactory;
use \RexDisplay as RexDisplay;
use \RexSettings as RexSettings;
use \RexPage as RexPage;
use \RexRunner as RexRunner;
use \PagerObj as PagerObj;

/**
 * Class NewsController
 *
 * @author   Fatal
 * @access   public
 * @package  NewsController.class.php
 * @created  Sun Jan 20 10:38:56 EET 2008
 */
class NewsController extends \RexFramework\ParentController
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexShop\NewsEntity:shop.standart:1.0',
        'RexShop\NewsManager:shop.standart:1.0',
        'PagerObj:standart:1.0'
    );
	
	function getDefault()
	{
		$this->entity = RexFactory::entity('news');
		if ($this->entity->getByFields(array('alias' => $this->task))) {
			RexDisplay::assign('news_item', $this->entity);

			$pattern = RexSettings::get('news_title');
			if (strlen(trim($this->entity->title)) > 0) {
                RexPage::setTitle($this->entity->title);
			} elseif ($pattern !== false and strlen(trim($pattern)) > 0) {
                $title = str_replace('{news_name}', $this->entity->name, $pattern);
				RexPage::setTitle($title);
			} else {
                RexPage::setTitle($this->entity->name);
			}
			
			RexPage::setDescription($this->entity->description);
			RexPage::setKeywords($this->entity->keywords);
		}
	}
	
	function getArchive()
	{
		if (!$this->task or $this->task == 'default') {
			$this->task = 1;
		}
        $perPage = RexSettings::get('per_page');
		$pagerObj = new PagerObj('pager_'.$this->mod, $perPage, $this->task);

		$this->manager = RexFactory::manager('news');
		$this->manager->getArchive($pagerObj->getFrom(), $pagerObj->getPerPage());
        
		RexDisplay::assign('news_archive', $this->manager->getCollection());

		$pagerObj->setCount($this->manager->getCount());
        
		$pagerObj->generatePages();
        //\sys::dump($pagerObj);
		RexDisplay::assign($pagerObj->name, $pagerObj);
        RexDisplay::assign('pager_count', count($pagerObj->pages));

        $_REQUEST['task'] = 'news_archive';
        RexRunner::runController('staticPage', 'default');
	}
	
	function getLatest() //smarty func
	{
        $aParams = array(
            'saveto' => 'news',
            'count' => 3
        );
         
		$newsManager = RexFactory::manager('news');
		$newsManager->getByWhere('1=1  ORDER BY `id` DESC LIMIT '.$aParams['count'].'');		
		RexDisplay::assign($aParams['saveto'], $newsManager->getCollection());
        //\sys::dump($newsManager->getCollection());exit;
	}
}