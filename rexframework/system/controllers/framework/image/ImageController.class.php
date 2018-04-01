<?php
namespace RexFramework;

use \RexFactory as RexFactory;
use \RexRunner as RexRunner;
use \RexConfig as RexConfig;
use \XSession as XSession;
use \Request as Request;
use \RexLang as RexLang;
use \RexResponse as RexResponse;
use \RexDisplay as RexDisplay;
use \XImage as XImage;

/**
 * Class ImageController
 *
 * Image Controller Class
 *
 * @author   Fatal
 * @access   public
 * @package  ImageController.class.php
 * @created  Sun Jan 20 10:38:56 EET 2008
 */
class ImageController extends ParentAdminController
{
    static public $assemble = 'standart';
    static public $version = 1.0;
    static public $needed = array(
        'RexFramework\ParentAdminController:standart:1.0',
        'RexFramework\ImageEntity:standart:1.0',
        'RexFramework\ImageManager:standart:1.0'
    );
    
    function getUpload()
    {
        RexResponse::init();
        
        $name = Request::get('image_name', 'image');
        $this_mod = Request::get('this_mod', false);
        $arr = Request::get('image', array()); 
        $tmpfile = null;
        
        $this->_generateUploadDir(strtolower($this_mod)); // генерируем директорию для загрузки картинок мода
        $upload_dir = $this->_generateUploadDir(strtolower($this_mod).'/temporary/'); // генерируем директорию для временной загрузки картинок
        $upload_sizes = RexConfig::get('Images', 'default', 'size', 'main'); // получаем дефолтные размеры картинки для вывода в диалоговом окне кропа
        $upload_settings = RexConfig::get('Images', $this_mod, 'settings'); // получаем ресайз метод и цвет бордера
        $upload_main_sizes = RexConfig::get('Images', $this_mod, 'size', 'main'); // получаем дефолтные размеры картинки
        /*if($_SERVER['REMOTE_ADDR'] = '77.120.163.26'){
            var_dump($upload_main_sizes);
        }*/
        $upload_main_size = explode('x', trim($upload_main_sizes)); // эксплодим полученную строку в массив $upload_main_size в котором нулевой элемент - ширина, а первый - высота 
        $upload_size = explode('x', trim($upload_sizes)); // эксплодим полученную строку в массив $upload_size в котором нулевой элемент - ширина, а первый - высота 
        
        
        
        if (count($upload_size) != 2) { // если не удалось получить ровно два параметра - выдаём ошибку
            RexResponse::error('Error in config file: size must be a value like "{width}x{height}"');
        }
         
        if(isset($_FILES[$name]['tmp_name'])) { /// если такая картинка присутстует в глобальном массиве $_FILES
            if (!in_array($_FILES[$name]['type'], array('image/png', 'image/gif', 'image/jpeg'))) { // если загруженный файл не картинка
                RexResponse::error('Wrong image format'); 
            }
            
            $r = time();
            $extension = substr($_FILES[$name]['type'], 6);
            $new_file = $r.'.'.$extension; // имя картинки-источника
            $new_file_resize = $r.'_resize.'.$extension; // имя ресайзнутой картинки
            move_uploaded_file($_FILES[$name]['tmp_name'], $upload_dir.$new_file);
            
            $tmpfile = $new_file_resize;
            
            list($width, $height) = getimagesize($upload_dir.$new_file); // |получаем размеры картинки
            
            if (isset($upload_settings['resize_method']) && $upload_settings['resize_method'] == 'borders') { // если borders
                if ($width/$height != $upload_main_size[0]/$upload_main_size[1]) {
                    $scale = 1;
                    
                    if ($width/$upload_main_size[0] > $height/$upload_main_size[1] && $width > $upload_main_size[0]) {
                        $scale *= ($width/$height)/($upload_main_size[0]/$upload_main_size[1]);
                        $height *= $scale;
                    } elseif ($width/$upload_main_size[0] <= $height/$upload_main_size[1] && $height > $upload_main_size[1]) {
                        $scale *= ($height/$width)/($upload_main_size[1]/$upload_main_size[0]);
                        $width *= $scale;
                    }
                    
                    XImage::createPreview($upload_dir.$new_file, $upload_dir.$new_file, $width, $height, $upload_settings['resize_method'], (isset($upload_settings['borders_color']) ? hexdec($upload_settings['borders_color']) : false));           
                }
            }
            
            //дальнейший блок связан со скейлом картинки, за детальными объяснениями - к автору
            $scale = 1;
            
            if ($width/$upload_size[0] > $height/$upload_size[1] && $width > $upload_size[0]) {
                $scale *= $width/$upload_size[0];
            } elseif ($width/$upload_size[0] <= $height/$upload_size[1] && $height > $upload_size[1]) {
                $scale *= $height/$upload_size[1];
            }
            
            $width /= $scale; 
            $height /= $scale;
            // ресайз картинки по скейлу
            XImage::createPreview($upload_dir.$new_file, $upload_dir.$new_file_resize, $width, $height, 'borders');
            
            if(!getimagesize($upload_dir.$tmpfile)){ 
                unlink($upload_dir.$tmpfile);
                $tmpfile = false;        
            }
            
            $result['picture'] = $tmpfile;
            $result['scale'] = $scale;
        } else {
             RexResponse::error('File not found');
        }   
        
        RexResponse::response($result);
    }
    
    function getCrop()
    {
        RexResponse::init();
        
        $this_mod = Request::get('this_mod', false);
        $picture = Request::get('picture', false);
        $scale = Request::get('scale', 1);
        $x = Request::get('x', false);
        $y = Request::get('y', false);
        $h = Request::get('h', false);
        $w = Request::get('w', false);
        
        if (!$picture) { // если картинка не была передана
            RexResponse::error('Picture not found');       
        }
        
        $temp_folder = RexConfig::get('RexPath', 'image', 'folder').strtolower($this_mod).'/temporary/';
        
        if ($x !== false) { // когда был передан параметр x - т.е. при сохранении кропа
            if (is_file(REX_ROOT.$temp_folder.$picture)) {
                unlink(REX_ROOT.$temp_folder.$picture); // удаляем ресайзнутую картинку    
            }
            
            $picture = str_replace('_resize', '', $picture); // делаем путь картинки ссылающийся на картинку-источник    
        }
        
        $picturl = RexConfig::get('RexPath', 'image', 'link').strtolower($this_mod).'/temporary/'.$picture; // путь для вывода в темплейте картинки
        
        $source = REX_ROOT.$temp_folder.$picture; // путь картинки источника
        $croped = REX_ROOT.$temp_folder.'cropped_'.$picture; // путь будущей откропленой картинки
        
        if ($x !== false) { // когда был передан параметр x - т.е. при сохранении кропа
            if (!$h || !$w) { // если один из параметров не был передан, значит неверно выбран селект кропа
                RexResponse::error('Wrong selection', true);
            }
            
            if (!XImage::createPreviewFromCrop($source, $croped,
                                               $w*$scale, $h*$scale,
                                               $x*$scale, $y*$scale,
                                               $w*$scale, $h*$scale)) { // если ресайз по каким-то причинам не удался
                RexResponse::error(XImage::getError(), true);
            }
            
            unlink($source); // удаляем  картинку-источник
            $picturl_croped = RexConfig::get('RexPath', 'image', 'link').strtolower($this_mod).'/temporary/cropped_'.$picture;
            $response['cropped'] = 'temporary/cropped_'.$picture;
            $response['link'] = $picturl_croped;
            RexResponse::response($response, true);
        } else { // для отрисовки диалога кропа
            $config_mod = (array)RexConfig::get('Images', $this_mod);
            
            if (isset($config_mod['size']) && isset($config_mod['size']['main'])) {
                $sizes = $config_mod['size']['main'];
            } else {
                $sizes = RexConfig::get('Images', 'default', 'size', 'main');
            }
            
            $size = explode('x', trim($sizes)); // эксплодим полученную строку в массив $size в котором нулевой элемент - ширина, а первый - высота
            
            if (count($size) != 2) { // если не удалось получить ровно два параметра - выдаём ошибку
                RexResponse::error('Error in config file: size must be a value like "{width}x{height}"');
            }
            
            $crop_width = isset($size[0]) ? $size[0] : 800;
            $crop_height = isset($size[1]) ? $size[1] : $crop_width;
            
            list($width_main, $height_main) = getimagesize(str_replace('_resize', '', $source));
            
            list($width, $height) = getimagesize($source);
            
            if ($width_main < $crop_width) {
                RexResponse::error('The image width must be more than or equal '.$crop_width.'px, not '.$width_main.' px', true);    
            } elseif ($height_main < $crop_height) {
                RexResponse::error('The image height must be more than or equal '.$crop_height.'px, not '.$height_main.' px', true);    
            }
        }
        
        RexDisplay::assign('picture', $picture);
        RexDisplay::assign('picturl', $picturl);
        RexDisplay::assign('crop_width', $crop_width);
        RexDisplay::assign('crop_height', $crop_height);
        RexDisplay::assign('pic_width', $width);
        RexDisplay::assign('pic_height', $height);
        
        $content = RexDisplay::fetch(strtolower($this->mod).'/crop.tpl');
        
        RexResponse::responseDialog($content, $width + 11, $height + 65); // 11 и 65 - подобранные пиксели для нормального отображения диалога
    }
}