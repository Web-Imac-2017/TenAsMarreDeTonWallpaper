<?php
//OBJECTIF
//je télécharge le wallpaper depuis une url internet et crée sa miniature
//j'ouvre un wallpaper existant depuis un fichier
//je récupère ses info
//je le traite

//nb : lorsque imagejpeg ou imagepng possède un second argument, il n'affiche rien

//les trois fonctions marchent avec path et url !!! yeaaaah
function gdgetpic($url){
    return ImageCreateFromString(file_get_contents($url));
}
function gdcollect($url){
    $image=gdgetpic($url);
    $info=getimagesize($url);
    $format=image_type_to_extension($info[2]);

    return array("image"=>$image, "height"=>$info[1],"width"=>$info[0],"extension"=>$format);
}

//n'affiche rien
function gdsave($image,$filename,$dotextension){
    switch($dotextension){
        case ".jpeg":
        case ".jpg":{
            //echo "c'est un jpeg";
            ImageJPEG($image,$filename.$dotextension);
        }break;
        case ".png":{
            //echo "cest un png";
            Imagepng($image,$filename.$dotextension);
        }break;
        default:
            break;
    }

}

function gdcrop($image,$startx,$starty,$cropwidth,$cropheight){
    
    $output=ImageCreateTrueColor($cropwidth, $cropheight);
    ImageCopyResampled($output, $image, 0, 0, $startx, $starty, $cropwidth, $cropheight, $cropwidth, $cropheight);

    return $output;
}
//argument supplémentaire après image:
//seulement image -> thumbnail
//width -> resize avec la width demandé proportionellement
//widht + height -> simple resize
function gdresize($image, $width = 150, $height = true) {

    $height=($height === true)? (ImageSY($image)*$width/ImageSX($image)):$height;

    $output=ImageCreateTrueColor($width, $height);
    ImageCopyResampled($output, $image, 0, 0, 0, 0, $width, $height, ImageSX($image), ImageSY($image));

    return $output;
}

//Decommenter les 2 lignes suivantes et commenter celle de print_r pour visualiser l'image
header ("Content-type: image/jpg");
$res=gdcollect("http://cdn.cutestpaw.com/wp-content/uploads/2013/01/l-Cute-Kitten.jpg");
gdsave($res["image"],"phototest",$res["extension"]);

$recup=gdcollect("phototest.jpeg");
imagejpeg($recup["image"]);

//print_r(gdcollect("http://cdn.cutestpaw.com/wp-content/uploads/2013/01/l-Cute-Kitten.jpg"));
//Array ( [image] => Resource id #4 [height] => 435 [width] => 580 [extension] => .jpeg ) 

/*function gdresize($image,$ratio){
    
    $height=ImageSY($image)*$ratio;
    $width=ImageSX($image)*$ratio;

    $output=ImageCreateTrueColor($width, $height);
    ImageCopyResampled($output, $image, 0, 0, 0, 0, $width, $height, ImageSX($image), ImageSY($image));

    return $output;
}*/

?>