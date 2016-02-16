<?php

include("color.class.php");
include("./config.php");

$bg_color = 'FFFFFF'; 
$background = new color();
$background->set_hex($bg_color);

$fg_color = 000; 
$foreground = new color();
$foreground->set_hex($fg_color);

$file_format = 'png';

//$width = round($width, 3);
//$height = round($height, 3);

$font = "./mplus-1c-medium.ttf";

$img = imageCreate($width,$height); 
$bg_color = imageColorAllocate($img, $background->get_rgb('r'), $background->get_rgb('g'), $background->get_rgb('b'));
$fg_color = imageColorAllocate($img, $foreground->get_rgb('r'), $foreground->get_rgb('g'), $foreground->get_rgb('b'));

$lines = 1;
$text = 'ok';
//if ($_POST['text'])
if ($_GET['text'])
    //$text = $_POST['text'];
    $text = $_GET['text'];

$user = 0;
//if ($_POST['id'])
if ($_GET['id'])
    //$user = $_POST['id'];
    $user = $_GET['id'];


$text_angle = 0;

$fontsize = max(min($width/strlen($text)*1.15, $height*0.5) ,5);

$textBox = imagettfbbox_t($fontsize, $text_angle, $font, $text); 

$textWidth = ceil( ($textBox[4] - $textBox[1]) * 1.0 );

$textHeight = ceil( (abs($textBox[7])+abs($textBox[1])) * 1 ); 

$textX = ceil( ($width - $textWidth)/2 ); 
$textY = ceil( ($height - $textHeight)/2 + $textHeight ); 

imageFilledRectangle($img, 0, 0, $width, $height, $bg_color);

imagettftext($img, $fontsize, $text_angle, $textX, $textY, $fg_color, $font, $text); 

imagejpeg($img,'./tmp.jpg');
imageDestroy($img);//Destroy the image to free memory.
$img = imagecreatefromjpeg('./tmp.jpg');


//$share_1 = imagecreatefrompng("./cards/".$user.".png");
$share_1 = file_get_contents("./cards/".$user.".txt");
if($share_1==FALSE)
{
    header("Location: /i-dont-think-therefore-i-am-not"); 
    exit();
}
//imagejpeg($share_1,'./tmp.jpg');
//imageDestroy($share_1);//Destroy the image to free memory.
//$share_1 = imagecreatefromjpeg('./tmp.jpg');

//error_log("".strlen($share_1));

$share_2 = imagecreate($width*$factor,$height*$factor);

$col_black = imagecolorallocate($share_2,0,0,0);
$col_white = imagecolorallocate($share_2,255,255,255);
$col_grey = imagecolorallocate($share_2,128,128,128);

for($i=3; $i<imagesx($img)-3; $i++)
{
    for($j=3; $j<imagesy($img)-3; $j++)
    {
        $rgb = imagecolorat($img,$i,$j);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;
        if($r<128)
        {
            for($p=0; $p<$factor; $p++)
                for($q=0; $q<$factor; $q++)
                {
                   $r1 = $share_1[($i*$factor + $p)*($height*$factor)+$j*$factor + $q];
                   if($r1 == '1')
                       imagesetpixel($share_2, $i*$factor + $p, $j*$factor + $q, $col_grey);
                   else
                       imagesetpixel($share_2, $i*$factor + $p, $j*$factor + $q, $col_white);
                }
        }
        else
        {
            for($p=0; $p<$factor; $p++)
                for($q=0; $q<$factor; $q++)
                {
                   $r1 = $share_1[($i*$factor + $p)*($height*$factor)+$j*$factor + $q];
                   if($r1 == '0')
                       imagesetpixel($share_2, $i*$factor + $p, $j*$factor + $q, $col_grey);
                   else
                       imagesetpixel($share_2, $i*$factor + $p, $j*$factor + $q, $col_white);
                }
        }
    }
}
for($i=1; $i<3; $i++)
    for($j=1; $j<$height-1; $j++)
        for($p=0; $p<$factor; $p++)
            for($q=0; $q<$factor; $q++)
                 imagesetpixel($share_2, $i*$factor + $p, $j*$factor + $q, $col_white);
for($i=$width-3; $i<$width-1; $i++)
    for($j=1; $j<$height-1; $j++)
        for($p=0; $p<$factor; $p++)
            for($q=0; $q<$factor; $q++)
                 imagesetpixel($share_2, $i*$factor + $p, $j*$factor + $q, $col_white);
for($i=1; $i<$width-1; $i++)
    for($j=1; $j<3; $j++)
        for($p=0; $p<$factor; $p++)
            for($q=0; $q<$factor; $q++)
                 imagesetpixel($share_2, $i*$factor + $p, $j*$factor + $q, $col_white);
for($i=1; $i<$width-1; $i++)
    for($j=$height-3; $j<$height-1; $j++)
        for($p=0; $p<$factor; $p++)
            for($q=0; $q<$factor; $q++)
                 imagesetpixel($share_2, $i*$factor + $p, $j*$factor + $q, $col_white);


//unlink('./screen.png');
imagepng($share_2,'./screen/'.$user.'.png');
imageDestroy($img);//Destroy the image to free memory.
//imageDestroy($share_1);//Destroy the image to free memory.
imageDestroy($share_2);//Destroy the image to free memory.


function imagettfbbox_t($size, $text_angle, $fontfile, $text){
    $coords = imagettfbbox($size, 0, $fontfile, $text);
   
    $a = deg2rad($text_angle);
   
    $ca = cos($a);
    $sa = sin($a);
    $ret = array();
   
    for($i = 0; $i < 7; $i += 2){
        $ret[$i] = round($coords[$i] * $ca + $coords[$i+1] * $sa);
        $ret[$i+1] = round($coords[$i+1] * $ca - $coords[$i] * $sa);
    }
    return $ret;
}
?>
