<?php

abstract class XImageInterface
{
    protected function prepareResize($aParam){
        list($width, $height, $type, $attr) = getimagesize($aParam['source']);
        $ddx = 0; $ddy = 0; $sdx = 0; $sdy = 0;
     
        switch ($aParam['method']) {
            case 'fullresize':
                if ($aParam['width'] && !$aParam['height']) {
                    $width_dst = $aParam['width']; 
                    $height_dst = round($height / ($width / $width_dst));
                } elseif (!$aParam['width'] && $aParam['height']) {
                    $height_dst = $aParam['height']; 
                    $width_dst = round($width / ($height / $height_dst));
                } else {
                    $height_dst = $aParam['height'];
                    $width_dst = $aParam['width'];
                }
                $width_dst_full = $width_dst;
                $height_dst_full = $height_dst;
                break;
            case 'resize':
            case 'borders':
                if ($aParam['width'] && !$aParam['height']) {
                    $width_dst = $aParam['width'];
                    $height_dst = round($height / ($width / $width_dst));
                } elseif (!$aParam['width'] && $aParam['height']) {
                    $height_dst = $aParam['height']; 
                    $width_dst = round($width / ($height / $height_dst));
                } else {
                    $mul_x = $aParam['width'] / $width;
                    $mul_y = $aParam['height'] / $height;
                    if ($mul_x < $mul_y) {
                        $width_dst = $aParam['width'];
                        $height_dst = round($height / ($width / $width_dst));
                    } else {
                        $height_dst = $aParam['height'];
                        $width_dst = round($width / ($height / $height_dst));
                    } 
                }
                $width_dst_full = $width_dst;
                $height_dst_full = $height_dst;
                if ($aParam['method'] == 'borders') {
                    if ($width_dst < $aParam['width']) {
                        $ddx = round(($aParam['width'] - $width_dst) / 2);
                        $width_dst_full = $aParam['width'];
                    } elseif ($height_dst < $aParam['height']) {
                        $ddy = round(($aParam['height'] - $height_dst) / 2);
                        $height_dst_full = $aParam['height'];
                    }
                }
                break;
            case 'cut':
                if ($aParam['width'] && !$aParam['height']) {
                    $aParam['height'] = $aParam['width'];
                } elseif ($aParam['height'] && !$aParam['width']) {
                    $aParam['width'] = $aParam['height'];
                }
                $mul_x = $aParam['width'] / $width;
                $mul_y = $aParam['height'] / $height;
                if ($mul_x < $mul_y) {
                    $height_dst = $aParam['height'];
                    $width_dst = round($width / ($height / $height_dst));
                    $ddx = round(($aParam['width'] - $width_dst) / 2);
                    if ($ddx > 0) {
                        $ddx *= -1;
                    }
                } else {
                    $width_dst = $aParam['width'];
                    $height_dst = round($height / ($width / $width_dst));
                    $ddy = round(($aParam['height'] - $height_dst) / 2);
                    if ($ddy > 0) {
                        $ddy *= -1;
                    }
                }
                $width_dst_full = $aParam['width'];
                $height_dst_full = $aParam['height'];
                break;
            default:
                return false;
        }
        
        return array(
            $width, 
            $height,
            $width_dst,
            $height_dst,
            $width_dst_full,
            $height_dst_full,
            $ddx,
            $ddy,
            $sdx,
            $sdy
        );
    }
    
    protected function prepareWatermark($aParam)
    {
        list($dstwidth, $dstheight, $type, $attr) = getimagesize($aParam['destination']);
        list($wrwidth, $wrheight, $wrtype, $wrattr) = getimagesize($aParam['watermark']);
        
        
        $innerwidth = $dstwidth - $aParam['align']['hz']['padding'] * 2;
        $innerheight = $dstheight - $aParam['align']['vt']['padding'] * 2;
        $width = false;
        $height = false;
        
        if (is_int($aParam['width'])) {
            $width = $aParam['width'];
        } elseif (strpos('%', $aParam['width']) !== false) {
            $width = $innerwidth * trim($aParam['width'], '%') / 100;
        }
        
        if (is_int($aParam['height'])) {
            $height = $aParam['height'];
        } elseif (strpos('%', $aParam['height']) !== false) {
            $height = $innerheight * trim($aParam['height'], '%') / 100;
        }
        
        if (!$width && !$height) {
            if ($wrwidth > $innerwidth) {
                $width = $innerwidth;
            } elseif ($wrheight > $innerheight) {
                $height = $innerheight;
            } else {
                $width = $wrwidth;
                $height = $wrheight;
            }
        }
        
        $handle = $this->resize(array(
            'source' => $aParam['watermark'],
            'destination' => '',
            'method' => 'resize',
            'borders' => '',
            'width' => $width,
            'height' => $height,
            'return' => true
        ));
        
        return $handle;
    }
    
	abstract public function resize($aParam);
    
    abstract public function watermark($aParam);
}

?>