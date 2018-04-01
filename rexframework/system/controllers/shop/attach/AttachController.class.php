<?php
namespace RexShop;

use \RexFactory as RexFactory;
use \RexDisplay as RexDisplay;
use \RexPage as RexPage;
use \RexConfig as RexConfig;

/**
 * Class AttachController
 *
 * Category Controller Class
 *
 * @author   Fatal
 * @access   public
 * @package  AttachController.class.php
 * @created  Sun Jan 20 10:38:56 EET 2008
 */
class AttachController extends \RexFramework\ParentController
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexShop\AttachEntity:shop.standart:1.0',
        'RexShop\AttachManager:shop.standart:1.0',
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
    
    function getDownload()
    {
        $entity = RexFactory::entity($this->mod);
        
        if (!$this->task || $this->task < 1 || !$entity->get($this->task) || !$entity->id) {
            exit;
        }
        
        $entity->download_count += 1;
        $entity->update();
        
        header('Content-Type: '.$entity->type);
        header('Content-Disposition: attachment;filename="'.$entity->filename.'"');
        header('Cache-Control: max-age=0');
        
        echo file_get_contents(REX_ROOT.RexConfig::get('RexPath', 'attach', 'folder').$entity->product_id.'/'.$entity->filename);
        exit;
    }    
}