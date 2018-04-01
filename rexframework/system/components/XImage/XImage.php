<?php

class XImage
{
    const WATER_ALIGN_LEFT = 1;
    const WATER_ALIGN_TOP = 2;
    const WATER_ALIGN_BOTTOM = 4;
    const WATER_ALIGN_RIGHT = 8;
    const WATER_ALIGN_CENTER = 16;
    
    const WATER_PADDING_HZ = 1;
    const WATER_PADDING_VT = 2;
    
    const METHOD_RESIZE = 1;
    const METHOD_CUT = 2;
    const METHOD_BORDERS = 4;
    
    protected static $__error;

    protected static $lib = 'GD2';
    protected static $resize;
    protected static $thumbnail_width;
    protected static $thumbnail_height;

    protected static $extension;
    protected static $filename;
    protected static $mimetype;

    protected static $object;

    function XImage($aProperty = null)
    {
        static::init($aProperty);
    }
    
    public static function init($aProperty)
    {
        foreach ($aProperty as $key => $value) {
            static::${$key} = $value;
        }

        $name = 'XImage'.static::$lib;
        static::$object = new $name();

        static::clear();
    }

    public static function clear()
    {
        static::$__error = false;
    }

    public static function upload($field, $path)
    {
        static::clear();

        $xFile = new XFile();
        
        if (!$xFile->upload($field, $path)) {
            static::_setError('Invalid uploading');
            return false;
        }
        return true;
    }
    
    public static function createPreview($source, $destination, $width = false, $height = false, $method = false, $borders = false, $extension = false)
    {
        if (!$width && !$height) {
            throw new Exception('No dimensions');
        }

        if (!$method) {
            $method = RexConfig::get('Components', 'XImage', 'settings', 'default_resize_method') ?: 'resize';
        }
        
        $params = array(
            'source' => $source,
            'destination' => $destination,
            'method' => $method,
            'width' => $width,
            'height' => $height,
            'borders' => $borders,
            'extension' => $extension
        );
        
        if (!static::$object->resize($params)) {
            static::_setError('Resize error');
            return false;
        }
        return true;
    }
    
    public static function createPreviewFromCrop($aPathSource, $aPathDestination, $aWidth=false, $aHeight=false, $src_x = false, $src_y = false, $src_w = false, $src_h = false)
    {
        if (!$aWidth) {
            $params = array(
                'source'=>$aPathSource,
                'destination'=>$aPathDestination,
                'height'=>$aHeight,
                'src_x' => $src_x,
                'src_y' => $src_y,
                'src_w' => $src_w,
                'src_h' => $src_h
            );

            if (!static::$object->crop($params)) {
                static::_setError('Crop error');
                return false;
            }
        } elseif (!$aHeight) {
            $params = array(
                'source'=>$aPathSource,
                'destination'=>$aPathDestination,
                'width'=>$aWidth,
                'src_x' => $src_x,
                'src_y' => $src_y,
                'src_w' => $src_w,
                'src_h' => $src_h
            );

            if (!static::$object->crop($params)) {
                static::_setError('Crop error');
                return false;
            }
        } else {
            $params = array(
                'source'=>$aPathSource,
                'destination'=>$aPathDestination,
                'width'=>$aWidth,
                'height'=>$aHeight,
                'src_x' => $src_x,
                'src_y' => $src_y,
                'src_w' => $src_w,
                'src_h' => $src_h
            );

            if (!static::$object->crop($params)) {
                static::_setError('Crop error');
                return false;
            }
        }

        return true;
    }
    
    public static function putWatermark($destination, $watermark = false, $width = 'auto', $height = 'auto', 
        $hzalign = XImage::WATER_ALIGN_RIGHT, $hzpadding = 0, $vtalign = XImage::WATER_ALIGN_BOTTOM, $vtpadding = 0, $opacity = 1)
    {
        if (!$watermark) {
            $watermark = RexConfig::get('Components', 'XImage', 'settings', 'watermark') ?: false;
            if ($watermark) {
                $watermark = rtrim(HTDOCS, DS).RexDisplay::getContentPath($watermark, 'img');
            }
            if (!$watermark || !file_exists($watermark)) {
                throw new Exception('Wrong watermark file');
            }
        }
        
        $params = array(
            'destination' => $destination,
            'watermark' =>$watermark,
            'width' => $width,
            'height' => $height,
            'align' => array(
                'hz' => array(
                    'type' => $hzalign,
                    'padding' => $hzpadding
                ),
                'vt' => array(
                    'type' => $vtalign,
                    'padding' => $vtpadding
                )
            ),
            'opacity' => $opacity
        );
        
        if (!static::$object->watermark($params)) {
            static::_setError('Watermark error');
            return false;
        }
        return true;
    }

    public static function delete($aPath)
    {
        $xFile = new XFile();
        return $xFile->delete($aPath);
    }

    public static function _setError($aError)
    {
        static::$__error = $aError;
    }


    public static function isError()
    {
        if (static::$__error) {
            return true;
        }
        return false;
    }

    public static function getError()
    {
        return static::$__error;
    }
    
    public static function validMimeType($mime)
    {
        if ($mime != 'image/jpeg' && $mime != 'image/gif' && $mime != 'image/png') { 
            static::_setError('Warning image mime type');
            return false;
        }
        
        return true;
    }
    
    public static function validExtension($name) 
    {
        if (!preg_match('#^.+\.([a-z0-9]{2,4})$#is', $name, $subpattern)) {
            static::_setError('Extension is empty');
            return false;
        }
        
        $extension = strtolower($subpattern[1]);
        if ($extension != 'jpg' and $extension != 'jpeg' and $extension != 'gif' and $extension != 'png') { 
            static::_setError('Warning image extension');
            return false;
        }
        
        return true;
    }
    
    public static function unlinkResized($mod = false, $id = false)
    {
        $pictures = RexConfig::get('Images');
        
        if ($mod && $id) {
            static::_checkImages($pictures[$mod], $mod, $id);        
        } else {
            foreach ($pictures as $mod => $picture) {
                if ($mod != 'default') {
                    static::_checkImages($picture, $mod);        
                }
            }
        }
    }
    
    protected static function _checkImages($picture, $mod, $id = false)
    {
        $dirPath = REX_ROOT.RexConfig::get('RexPath', 'image', 'folder').strtolower($mod);
        if (is_dir($dirPath)) {
            if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
                $dirPath .= '/';
            }
            $files = glob($dirPath . '*', GLOB_MARK);
            foreach ($files as $file) {
                if (is_dir($file)) {
                    if (substr($file, strlen($file) - 1, 1) != '/') {
                        $file .= '/';
                    }
                    
                    $dirName = basename($file);
                    
                    if ($dirName == 'temporary' && !$id) {
                        $tempFiles = glob($file . '*', GLOB_MARK);
                        foreach ($tempFiles as $tempFile) {
                            if (is_dir($tempFile)) {
                                static::_deleteDirAndFiles($tempFile);
                            } elseif (is_file($tempFile)) {
                                unlink($tempFile);
                            }
                        }
                    } elseif ($id && intval($id) == intval($dirName)) {
                        $entity = RexFactory::entity($mod);
                        $entity->getByFields(array($entity->__uid => intval($dirName)));
                        
                        if ($entity->{$entity->__uid}) {
                            static::_deleteDirAndFiles($file);   
                            static::_deleteDirAndFiles(REX_ROOT.RexConfig::get('RexPath', 'image', 'storage').strtolower($mod).'/'.$dirName);   
                        } else {
                            $entityFiles = glob($file . '*', GLOB_MARK);
                            
                            foreach ($entityFiles as $entityFile) {
                                if (is_dir($entityFile)) {
                                    static::_deleteDirAndFiles($entityFile);
                                } elseif (is_file($entityFile)) {
                                    $fileInfo = pathinfo($entityFile);
                                    if (!isset($picture['size']) || !isset($picture['size'][$fileInfo['filename']])) {
                                        unlink($entityFile);    
                                    }
                                }
                            }    
                        }
                        return true;    
                    } elseif (!$id) {
                        $entity = RexFactory::entity($mod);
                        $entity->getByFields(array($entity->__uid => intval($dirName)));
                        
                        if (!$entity->{$entity->__uid}) {
                            static::_deleteDirAndFiles($file);
                            static::_deleteDirAndFiles(REX_ROOT.RexConfig::get('RexPath', 'image', 'storage').strtolower($mod).'/'.$dirName);   
                        } else {
                            $entityFiles = glob($file . '*', GLOB_MARK);
                            
                            foreach ($entityFiles as $entityFile) {
                                if (is_dir($entityFile)) {
                                    static::_deleteDirAndFiles($entityFile);
                                } elseif (is_file($entityFile)) {
                                    $fileInfo = pathinfo($entityFile);
                                    if (!isset($picture['size']) || !isset($picture['size'][$fileInfo['filename']])) {
                                        unlink($entityFile);    
                                    }
                                }
                            }    
                        }
                    }
                }
            }
        }    
    }
    
    protected static function _deleteDirAndFiles($path)
    {
        if (is_dir($path)) {
            if (substr($path, strlen($path) - 1, 1) != '/') {
                $path .= '/';
            }
            $files = glob($path . '*', GLOB_MARK);
            foreach ($files as $file) {
                if (is_dir($file)) {
                    static::_deleteDirAndFiles($file);
                } elseif (is_file($file)) {
                    unlink($file);
                }
            }
            rmdir($path);
        }    
    }
    
    public static function getImg($aParams)
    {
        set_time_limit(0);
        if (isset($aParams['type']) && isset($aParams['name']) && isset($aParams['id']) && isset($aParams['ext'])) {
            $pictureSizes = RexConfig::get('Images', $aParams['name'], 'size', $aParams['type']);
            $pictureSize = explode('x', trim($pictureSizes));
            
            if (count($pictureSize) == 2) {
                $aParams['w'] = $pictureSize[0];
                $aParams['h'] = $pictureSize[1];
                $picFolderUrl['main'] = REX_ROOT.RexConfig::get('RexPath', 'image', 'folder').strtolower($aParams['name']).'/'.intval($aParams['id']).'/';
                
                if(!is_dir($picFolderUrl['main'])) {
                    mkdir($picFolderUrl['main'], 0777);
                    chmod($picFolderUrl['main'], 0777);
                }
                
                $aParams['source'] = REX_ROOT.RexConfig::get('RexPath', 'image', 'storage').strtolower($aParams['name']).'/'.intval($aParams['id']).'/main'.'.'.$aParams['ext'];
                
                if (is_dir($picFolderUrl['main']) && is_file($aParams['source'])) {
                    $picFolderUrl['resize'] = $picFolderUrl['main'].'resize/';
                    $aParams['dest'] = $picFolderUrl['main'].$aParams['type'].'.'.$aParams['ext'];

                    if (is_file($aParams['dest'])) {
                        list($width, $height) = getimagesize($aParams['dest']);
                        if ($width != $aParams['w'] || $height != $aParams['h']) {
                            $reCreate = 1;    
                        }    
                    }

                    if(!is_file($aParams['dest']) || isset($reCreate)) {  
                        static::_getResizeImage($aParams, $picFolderUrl); 
                    } 
                    return RexConfig::get('RexPath', 'image', 'link').strtolower($aParams['name']).'/'.intval($aParams['id']).'/'.$aParams['type'].'.'.$aParams['ext'];
                }
            }
        }
        return false;
    }
    
    protected static function _getResizeImage(&$aParams, $picFolderUrl)
    {
        if (getimagesize($aParams['source'])) {
            list($width, $height, $type, $attr) = getimagesize($aParams['source']);

            if ($width == $aParams['w'] && $height == $aParams['h']) {
                return copy($aParams['source'], $aParams['dest']);   
            }

            if ($width / $height > $aParams['w'] / $aParams['h']) {
                $chopWidth = $width - $aParams['w'] / $aParams['h'] * $height;
                if ($chopWidth > 1) {
                    $width = $width - $chopWidth;
                    return self::createPreviewFromCrop($aParams['source'], $aParams['dest'], $aParams['w'], $aParams['h'], $chopWidth / 2, 0, $width, $height);
                }
                return self::createPreviewFromCrop($aParams['source'], $aParams['dest'], $aParams['w'], $aParams['h'], 0, 0, $width, $height);
            } elseif ($height / $width > $aParams['h'] / $aParams['w']) {
                $chopHeight = $height - $aParams['h'] / $aParams['w'] * $width;

                if ($chopHeight > 1) {
                    $height = $height - $chopHeight;

                    return self::createPreviewFromCrop($aParams['source'], $aParams['dest'],  $aParams['w'], $aParams['h'], 0, $chopHeight / 2, $width, $height);
                }

                return self::createPreviewFromCrop($aParams['source'], $aParams['dest'],  $aParams['w'], $aParams['h'], 0, 0, $width, $height);
            } else {
                return self::createPreview($aParams['source'], $aParams['dest'], $aParams['w'], $aParams['h']);   
            }
        }
        
        return false;    
    }
}