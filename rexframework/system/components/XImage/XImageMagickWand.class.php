<?php

class XImageMagickWand extends XImageInterface
{
    public function resize($aParam)
    {
        $result = $this->prepareResize($aParam);
        list($width, $height, $width_dst, $height_dst, $width_dst_full, 
                $height_dst_full, $ddx, $ddy, $sdx, $sdy) = $result;
        
        $resource = new Imagick();
        $resource->readImage($aParam['source']);
        $resource->setImagePage($width, $height, 0, 0);
        $resource->resizeImage($width_dst, $height_dst, Imagick::FILTER_LANCZOS, 1);
        
        if (isset($aParam['return']) && $aParam['return']) {
            return $resource;
        }
        
        if (isset($aParam['borders']) && $aParam['borders']) {
            $out = new Imagick();
            $out->newImage($width_dst_full, $height_dst_full, 
                new ImagickPixel('#'.str_pad(dechex(isset($aParam['borders']) && $aParam['borders'] ? $aParam['borders'] : 0xFFFFFF), 6, '0', STR_PAD_LEFT)));
            $out->compositeImage($resource,  Imagick::COMPOSITE_SRCOVER, $ddx, $ddy, Imagick::CHANNEL_ALL);   
        } else {
            $out = &$resource;
        }
        
        $out->setImageFileName($aParam['destination']);
        
        if (isset($aParam['extension']) && $aParam['extension']) {
            $extensions = array('jpg', 'png');
            
            if (in_array($aParam['extension'], $extensions)) {
                $out->setImageFormat($aParam['extension']);
            }
        }
        
        $out->writeImage();
        $out->clear();
        $out->destroy();

        return true;
    }
    
    public function crop($aParam)
    {
        if (!isset($aParam['source']) or
        !isset($aParam['destination']) or
        (!isset($aParam['width']) and !isset($aParam['height']))) {
            return false;
        }
        
        list($width, $height, $type, $attr) = getimagesize($aParam['source']);
        if (isset($aParam['src_w']) && $aParam['src_w']) {
            $width = $aParam['src_w'];
        }
        if (isset($aParam['src_h']) && $aParam['src_h']) {
            $height = $aParam['src_h'];
        }

        $type = image_type_to_mime_type($type);
        $type = str_replace('image/', '', $type);
        
        $resource = new Imagick();
        $resource->readImage($aParam['source']);
        $resource->setImagePage($width, $height, 0, 0);
        
        if (!isset($aParam['width'])) {
            $proportion = $aParam['height'] / $height;
            $newWidth = intval($width * $proportion);
            
            if (isset($aParam['src_x']) && isset($aParam['src_y']) && isset($aParam['src_w']) && isset($aParam['src_h']) &&
                $aParam['src_x'] !== false && $aParam['src_y'] !== false && $aParam['src_w'] !== false && $aParam['src_h'] !== false) {
                    
                $resource->cropImage($aParam['src_w'], $aParam['src_h'], $aParam['src_x'], $aParam['src_y']);
            }
            
            $resource->resizeImage($newWidth, $aParam['height'], Imagick::FILTER_LANCZOS, 1);
        } else {
            if ($width < $aParam['width']) {
                $aParam['width'] = $width;
            }
            
            $proportion = $aParam['width'] / $width;
            $newHeight = round($height * $proportion);
            
            if (isset($aParam['src_x']) && isset($aParam['src_y']) && isset($aParam['src_w']) && isset($aParam['src_h']) &&
                $aParam['src_x'] !== false && $aParam['src_y'] !== false && $aParam['src_w'] !== false && $aParam['src_h'] !== false) {
                
                $resource->cropImage($aParam['src_w'], $aParam['src_h'], $aParam['src_x'], $aParam['src_y']);
            }

            $resource->resizeImage($aParam['width'], $newHeight, Imagick::FILTER_LANCZOS, 1);
        }
        
        $resource->setImageFileName($aParam['destination']);
        $resource->writeImage();
        $resource->clear();
        $resource->destroy();
        
        return true;                  
    }
    
    public function watermark($aParam)
    {
        list($dstwidth, $dstheight, $type, $attr) = getimagesize($aParam['destination']);
        
        $handle = $this->prepareWatermark($aParam);
        
        $geo = $handle->getImageGeometry();
        $width = $geo['width'];
        $height = $geo['height'];
        
        $hzpad = $aParam['align']['hz']['padding'];
        $vtpad = $aParam['align']['vt']['padding'];
        
        switch ($aParam['align']['hz']['type']) {
            case XImage::WATER_ALIGN_LEFT: $left = $hzpad; break;
            case XImage::WATER_ALIGN_RIGHT: $left = $dstwidth - $hzpad * 2 - $width + $hzpad; break;
            case XImage::WATER_ALIGN_CENTER: default: 
                $left = ($dstwidth - $hzpad * 2 - $width) / 2 + $hzpad;
        }
        
        switch ($aParam['align']['vt']['type']) {
            case XImage::WATER_ALIGN_TOP: $top = $vtpad; break;
            case XImage::WATER_ALIGN_BOTTOM: $top = $dstheight - $vtpad * 2 - $height + $vtpad; break;
            case XImage::WATER_ALIGN_CENTER: default: 
                $top = ($dstheight - $vtpad * 2 - $height) / 2 + $vtpad;
        }
          
        $out = new Imagick();
        $out->readImage($aParam['destination']);
        
        $handle->evaluateImage(Imagick::EVALUATE_MULTIPLY, $aParam['opacity'], Imagick::CHANNEL_ALPHA);
        
        $out->compositeImage($handle, Imagick::COMPOSITE_OVER, $left, $top);
        
        $out->setImageFileName($aParam['destination']);
        $out->writeImage();
        
        return true;
    }
}