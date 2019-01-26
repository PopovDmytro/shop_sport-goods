<?php

class XImageGD2 extends XImageInterface
{
    function XImageGD2()
    {

    }

    public function resize($aParam)
    {
        list($width, $height, $type, $attr) = getimagesize($aParam['source']);

        //var_dump($aParam['source'], is_file($aParam['source']));exit;
        $type = image_type_to_mime_type($type);
        $type = str_replace('image/', '', $type);
        $function = 'imagecreatefrom'.$type;
        //var_dump($function);exit;
        if (!function_exists($function)) {
            return false;
        }

        list($width, $height, $width_dst, $height_dst, $width_dst_full, 
            $height_dst_full, $ddx, $ddy, $sdx, $sdy) = $this->prepareResize($aParam);
        
        $img_src = $function($aParam['source']);
        imagealphablending($img_src, false);
        imagesavealpha($img_src, true);
        $img_dst = imagecreatetruecolor($width_dst_full, $height_dst_full);
        imagealphablending($img_dst, false);
        imagesavealpha($img_dst, true);
        if (isset($aParam['borders']) && $aParam['borders']) {
            imagefill($img_dst, 0, 0, $aParam['borders']);
        }
        imagecopyresampled($img_dst, $img_src, $ddx, $ddy, $sdx, $sdy, $width_dst, $height_dst, $width, $height);
        if (isset($aParam['return']) && $aParam['return']) {
            return $img_dst;
        }
        
        $function = 'image'.$type;
        $function($img_dst, $aParam['destination']);

        imagedestroy($img_dst);
        imagedestroy($img_src);
        
        return true;
    }
        
    public function watermark($aParam)
    {
        list($dstwidth, $dstheight, $type, $attr) = getimagesize($aParam['destination']);
        
        $type = image_type_to_mime_type($type);
        $type = str_replace('image/', '', $type);
        $function = 'imagecreatefrom'.$type;
        if (!function_exists($function)) {
            return false;
        }

        $handle = $this->prepareWatermark($aParam);
        
        $width = imagesx($handle);
        $height = imagesy($handle);
        
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
        
        $out = $function($aParam['destination']);

        if ($aParam['opacity'] == 1) {
            imagecopy($out, $handle, $left, $top, 0, 0, $width, $height);
        } else {
            imagecopymerge($out, $handle, $left, $top, 0, 0, $width, $height, intval(100 * $aParam['opacity']));
        }

        $function = 'image'.$type;
        $function($out, $aParam['destination']);
        
        imagedestroy($out);
        imagedestroy($handle);

        return true;
    }

    public function crop($aParam)
    {
        list($current_width, $current_height, $type) = getimagesize($aParam['source']);

        $type = image_type_to_mime_type($type);
        $type = str_replace('image/', '', $type);
        $fnImageCreate = 'imagecreatefrom'.$type;
        $fnImage = 'image'.$type;

        if (!function_exists($fnImageCreate) || !function_exists($fnImage)) {
            return false;
        }

        $canvas = imagecreatetruecolor($aParam['src_w'], $aParam['src_h']);
        $current_image = $fnImageCreate($aParam['source']);
        imagecopy($canvas, $current_image, 0, 0, $aParam['src_x'], $aParam['src_y'], $current_width, $current_height);
        $fnImage($canvas, $aParam['destination'], $type === 'png' ? NULL : 90);

        list($current_width, $current_height) = getimagesize($aParam['destination']);
        $proportionWidth    = $aParam['width'] / $current_width;
        $proportionHeight   = $aParam['height'] / $current_height;
        $newWidth           = intval($current_width * $proportionWidth);
        $newHeight          = intval($current_height * $proportionHeight);
        $thumb              = imagecreatetruecolor($newWidth, $newHeight);
        $source             = $fnImageCreate($aParam['destination']);
        imagecopyresized($thumb, $source, 0, 0, 0, 0, $newWidth, $newHeight, $current_width, $current_height);
        $fnImage($thumb, $aParam['destination'], $type === 'png' ? NULL : 90);

        return true;
    }
}