<?php

function gdgetpic($url){
    return ImageCreateFromString(file_get_contents($url));
}
function gdresize($url,$ratio){
    $image=gdgetpic($url);
    
    $height=ImageSY($image)*$ratio;
    $width=ImageSX($image)*$ratio;

    $output=ImageCreateTrueColor($width, $height);
    ImageCopyResampled($output, $image, 0, 0, 0, 0, $width, $height, ImageSX($image), ImageSY($image));

    ImageJPEG($output); 

    return $output;
}
function gdcrop($url,$startx,$starty,$cropwidth,$cropheight){
    $image=gdgetpic($url);
    
    $output=ImageCreateTrueColor($cropwidth, $cropheight);
    ImageCopyResampled($output, $image, 0, 0, $startx, $starty, $cropwidth, $cropheight, $cropwidth, $cropheight);

    ImageJPEG($output); 

    return $output;
}
function gdthumbnail($url, $filename, $width = 150, $height = true) {
    $image=gdgetpic($url);
    
    $height=($height == true)? (ImageSY($image)*$width/ImageSX($image)):$height;

    $output=ImageCreateTrueColor($width, $height);
    ImageCopyResampled($output, $image, 0, 0, 0, 0, $width, $height, ImageSX($image), ImageSY($image));

    ImageJPEG($output, $filename, 95); 

    return $output;
}

/*ImageJPEG(gdcrop("http://cdn.cutestpaw.com/wp-content/uploads/2013/01/l-Cute-Kitten.jpg",200,200,100,100));
header ("Content-type: image/jpeg"); 
*/
header ("Content-type: image/png");
$image = imagecreate(200,50);

$orange = imagecolorallocate($image, 255, 128, 0);
$bleu = imagecolorallocate($image, 0, 0, 255);
$bleuclair = imagecolorallocate($image, 156, 227, 254);
$noir = imagecolorallocate($image, 0, 0, 0);
$blanc = imagecolorallocate($image, 255, 255, 255);

imagepng($image);
?>