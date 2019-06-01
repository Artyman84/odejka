<?php
/**
 * Created by PhpStorm.
 * User: Arty
 * Date: 18.03.2018
 * Time: 10:08
 */

namespace app\components\helper;


use yii\helpers\VarDumper;

class CropImage {

    //------------------------------------------------------------------------------

    public static function crop($src, $dst, $crop, $canvas, $jpeg_quality = 100){
        $crop = explode(':', $crop);
        $canvas = explode(':', $canvas);
        //------------------------------------------------------------------------

        $scale = $crop[2];
        $rotate = $crop[3];
        $crop = array('x' => max(0, -$crop[0]), 'y' => max(0, -$crop[1]));
        $canvas = array('w' => $canvas[0], 'h' => $canvas[1]);

        //------------------------------------------------------------------------

        $src_img = self::readImage($src);

        if (!$src_img) {
            return "Failed to read the image file";
        }

        if( (int)$rotate ){
            $src_img = imagerotate($src_img, $rotate, 0);
            imagesavealpha($src_img, true);
        }


        if ($dst !== $src) {
            file_exists($dst) && unlink($dst);
        }

        list($naturalWidth, $naturalHeight) = getimagesize($src);

        $src_size = array(
            'x' => $crop['x']/$scale,
            'y' => $crop['y']/$scale,
            'w' => min($canvas['w']/$scale, $naturalWidth - $crop['x']/$scale),
            'h' => min($canvas['h']/$scale, $naturalHeight - $crop['y']/$scale)
        );

        $dst_size = array(
            'x' => 0,
            'y' => 0,
            'w' => min($canvas['w'], $src_size['w'] * $scale),
            'h' => min($canvas['h'], $src_size['h'] * $scale)
        );


        //------------------------------------------------------------------------
        $dst_img = imagecreatetruecolor($dst_size['w'], $dst_size['h']);
        imagefill($dst_img, 0, 0, imagecolorallocatealpha($dst_img, 0, 0, 0, 127));
        imagesavealpha($dst_img, true);
        $result = imagecopyresampled($dst_img, $src_img, $dst_size['x'], $dst_size['y'], $src_size['x'], $src_size['y'], $dst_size['w'], $dst_size['h'], $src_size['w'], $src_size['h']);
        //------------------------------------------------------------------------



        if (!$result) {
            return "Failed to crop the image file";
        } elseif (!self::saveImage($src, $dst_img, $dst, $jpeg_quality)) {
            return "Failed to save the cropped image file";
        }

        imagedestroy($src_img);
        imagedestroy($dst_img);

        return true;
    }

    protected static function readImage($src){
        $size = getimagesize($src);

        switch (strtolower($size['mime'])) {
            case 'image/gif': return imagecreatefromgif($src);
            case 'image/jpeg':
            case 'image/pjpeg':
            case 'image/jpg': return imagecreatefromjpeg($src);
            case 'image/x-png':
            case 'image/png': return imagecreatefrompng($src);
            default: return null;
        }

    }

    protected static function saveImage($src, $dst_img, $dst, $jpeg_quality = 100){
        $size = getimagesize($src);

        switch (strtolower($size['mime'])) {
            case 'image/gif': return imagegif($dst_img, $dst);
            case 'image/jpeg':
            case 'image/pjpeg':
            case 'image/jpg': return imagejpeg($dst_img, $dst, $jpeg_quality);
            case 'image/x-png':
            case 'image/png': return imagepng($dst_img, $dst, 9);
            default: return null;
        }

    }


}