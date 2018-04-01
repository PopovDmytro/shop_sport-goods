<?php
namespace RexFramework;

use \RexFactory as RexFactory;
use \XDatabase as XDatabase;
use \RexSettings as RexSettings;

/**
 * Class NewsRextestController
 *
 * News Controller Class
 *
 * @author   Fatal
 * @access   public
 * @package  NewsRextestController.class.php
 * @created  Sun Jan 20 10:38:56 EET 2008
 */
class NewsRextestController extends ParentRextestController
{    
    static public $assemble = 'standart';
    static public $version = 1.0;
    public static $needed = array(
        'RexShop\NewsEntity:shop.standart:1.0',
        'RexShop\NewsManager:shop.standart:1.0'
    );
    
    function testDefault()
    {
        $this->entity = RexFactory::entity($this->mod);
        $last_alias = XDatabase::getOne('SELECT `alias` FROM `news` ORDER BY `'.$this->entity->__uid.'` DESC LIMIT 1');
        
        $get = $this->generateGet($this->mod, 'default', $last_alias);
        $post = $this->generatePost('frontend');
        
        $result = $this->getDataFrontend($get, $post);
        
        $this->assertDoctype($result[0], 'Check for errors and exeptions');
        
        $this->entity->getByFields(array('alias' => $get['task']));
        
        $pattern = RexSettings::get('news_title');
        if (strlen(trim($this->entity->title)) > 0) {
            $this->assertRegExp('#<title>'.$this->entity->title.'</title>#', $result[0], 'Check title valid 1');
        } elseif ($pattern !== false and strlen(trim($pattern)) > 0) {
            $title = str_replace('{news_name}', $this->entity->name, $pattern);
            $this->assertRegExp('#<title>'.$title.'</title>#', $result[0], 'Check title valid 2');
        } else {
            if ($this->entity->name) {
                $reg = '#<title>'.$this->entity->name.'</title>#';
            } else {
                $reg = '#<title>\/</title>#';
            }
            $this->assertRegExp($reg, $result[0], 'Check title valid 3');
        }
    }
    
    function testArchive()
    {
        $get = $this->generateGet($this->mod, 'archive');
        
        $result = $this->getDataFrontend($get);
        
        $this->assertDoctype($result[0], 'Check for errors and exeptions');
    }
    
    //admin
    function testDefaultAdmin()
    {
        $this->entity = RexFactory::entity($this->mod);
        
        $get = $this->generateGet($this->mod);
        $post = $this->generatePost('admin');
        
        $result = $this->getDataAdmin($get, $post);
        
        $this->assertDoctype($result[0], 'Check for errors and exeptions');
        
        $this->assertRegExp('#class=\"itemadd\"#', $result[0], 'Check add button in the content');
    }
    
    function testAddEditDeleteAdmin()
    {
        $get = $this->generateGet($this->mod, 'add');
        $post = $this->generatePost('admin');
        
        $result = $this->getDataAdmin($get, $post);
        $result_decode = json_decode($result[0]);
        
        $this->assertRegExp('#name=\"entity\[name\]\"#', $result_decode->content->template, 'Check news input name in the page');
        
        $maxId = XDatabase::getOne('SELECT MAX(`'.$this->entity->__uid.'`) FROM `news`');
        
        if (!$maxId)
            $maxId = 0;
            
        $entity = RexFactory::entity($this->mod);
        $entity->get($maxId);
        
        $post = array(
            'entity[exist_id]' => '',
            'entity[submit]' => true,
            'entity[name]' => 're',
            'entity[content]' => 'rextest_content',
            'entity[title]' => 'rextest_title',
            'entity[description]' => 'rextest_description',
            'entity[keywords]' => 'rextest_keywords'
        );
        $post = $this->generatePost('admin', $post);
        
        $result = $this->getDataAdmin($get, $post);
        $result_decode = json_decode($result[0]);
        
        if (isset($result_decode->error)) {
            $this->assertEquals($result_decode->error, 'Name must have min 3 characters', 'Check error name');
        } else {
            $this->assertEquals('error', 'ok', 'Check error name');
        }
        
        $post['entity[name]'] = 'rextest_name';
        
        $result = $this->getDataAdmin($get, $post);
        $result_decode = json_decode($result[0]);
        
        if (isset($result_decode->content)) {
            $this->assertEquals($result_decode->content, 'ok', 'Check result by add');
        } else {
            $this->assertEquals('error', 'ok', 'Check result by add');
        }
        
        $maxIdNew = XDatabase::getOne('SELECT MAX(`'.$this->entity->__uid.'`) FROM `news`');
        
        if (!$maxIdNew)
            $this->assertTrue(false, 'News does not creat');
        else {
            $entityNew = RexFactory::entity($this->mod);
            $entityNew->getByFields(array('id' => $maxIdNew));
            
            if ($this->assertMore($entityNew->{$this->entity->__uid}, $entity->{$this->entity->__uid}, 'Check to create the news record') && $this->assertEquals($entityNew->name, 'rextest_name', 'Check news name')) {
                $news_id = $entityNew->id;
                
                $this->_testEditAdmin($news_id);
                                
                $this->_testDeleteAdmin($news_id);
            }
        }
    }
    
    private function _testEditAdmin($news_id)
    {
        $get = $this->generateGet($this->mod, 'edit', $news_id);
        $post = $this->generatePost('admin');
        
        $result = $this->getDataAdmin($get, $post);
        $result_decode = json_decode($result[0]);
                
        if (isset($result_decode->content->template)) {
            $brand = RexFactory::entity($this->mod);
            $brand->getByFields(array('id' => $news_id));
            
            $this->assertRegExp('#'.$brand->name.'#', $result_decode->content->template, 'Check brand name in the edit page');
        } else {
            $this->assertEquals('error', 'ok', 'Check result by display the add page');
        }
        
        $get = $this->generateGet($this->mod, 'add');
        
        $post = array(
            'entity[exist_id]' => $news_id,
            'entity[submit]' => true,
            'entity[name]' => 'rextest_name_second',
            'entity[content]' => 'rextest_content_second',
            'entity[title]' => 'rextest_title',
            'entity[description]' => 'rextest_description',
            'entity[keywords]' => 'rextest_keywords'
        );
        
        $result = $this->getDataAdmin($get, $post);
        $result_decode = json_decode($result[0]);
        
        if (isset($result_decode->content)) {
            $this->assertEquals($result_decode->content, 'ok', 'Check result by edit');
        } else {
            $this->assertEquals('error', 'ok', 'Check result by add');
        }

        $entity = RexFactory::entity($this->mod);
        $entity->getByFields(array('id' => $news_id));
        
        $this->assertEquals($entity->name, 'rextest_name_second', 'Verify that names');
        $this->assertEquals($entity->content, 'rextest_content_second', 'Verify that content');
        
        return true;
    }
    
    private function _testDeleteAdmin($news_id)
    {
        $get = $this->generateGet($this->mod, 'delete', $news_id);
        $post = $this->generatePost('admin');
        
        $result = $this->getDataAdmin($get, $post);
        $result_decode = json_decode($result[0]);
        
        if (isset($result_decode->content)) {
            $this->assertEquals($result_decode->content, 'ok', 'Check result by delete');
        } else {
            $this->assertEquals('error', 'ok', 'Check result by delete');
            
            $entity = RexFactory::entity($this->mod);
            $entity->getByFields(array('id' => $news_id));
            $entity->delete();
        }
        
        return true;
    }
}