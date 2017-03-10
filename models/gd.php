<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/TenAsMarreDeTonWallpaper/config.php';
require_once KERNEL . 'kernel.php';

class Gd extends Model {

    public function __construct(){
        parent::__construct();
    }

    public function gdgetpic($url) {
        return ImageCreateFromString(file_get_contents($url));
    }

    public function gdcollect($url) {
        $image=gdgetpic($url);
        $info=getimagesize($url);
        $format=image_type_to_extension($info[2]);

        return array("image"=>$image, "height"=>$info[1],"width"=>$info[0],"extension"=>$format);
    }

    public function gdsave($image, $filename, $dotextension) {
        switch($dotextension) {
            case ".jpeg":
            case ".jpg":{
            //echo "c'est un jpeg";
            ImageJPEG($image,$filename.$dotextension);
        } break;
            case ".png":{
            //echo "cest un png";
            Imagepng($image,$filename.$dotextension);
        } break;
            default:
                break;
        }
    }

    public function gdcrop($image, $startx, $starty, $cropwidth, $cropheight) {

        $output=ImageCreateTrueColor($cropwidth, $cropheight);
        ImageCopyResampled($output, $image, 0, 0, $startx, $starty, $cropwidth, $cropheight, $cropwidth, $cropheight);

        return $output;
    }

    public function gdresize($image, $width = 150, $height = true) {

        $height=($height === true)? (ImageSY($image)*$width/ImageSX($image)):$height;

        $output=ImageCreateTrueColor($width, $height);
        ImageCopyResampled($output, $image, 0, 0, 0, 0, $width, $height, ImageSX($image), ImageSY($image));

        return $output;
    }
    
    
}

?>