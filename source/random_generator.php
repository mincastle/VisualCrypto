<?php

    include "./config.php";


$card = imagecreate($width*$factor, $height*$factor);

$col_black = imagecolorallocate($card,0,0,0);
$col_white = imagecolorallocate($card,255,255,255);
$col_grey = imagecolorallocate($card,128,128,128);

for($i=0; $i<$width; $i++)
    for($j=0; $j<$height; $j++)
        for($p=0; $p<$factor; $p++)
            for($q=0; $q<$factor; $q++)
                $bytes[($i*$factor + $p)*($height*$factor)+$j*$factor + $q] = '0';

for($i=3; $i<$width-3; $i++)
{
    for($j=3; $j<$height-3; $j++)
    {
        $ran = rand(0,1);
        for($p=0; $p<$factor; $p++)
            for($q=0; $q<$factor; $q++)
            {
                   if($ran == 0)
                   {
                       imagesetpixel($card, $i*$factor + $p, $j*$factor + $q, $col_grey);
                       $bytes[($i*$factor + $p)*($height*$factor)+$j*$factor + $q] = '0';
                   }
                   else
                   {
                       imagesetpixel($card, $i*$factor + $p, $j*$factor + $q, $col_white);
                       $bytes[($i*$factor + $p)*($height*$factor)+$j*$factor + $q] = '1';
                   }
            }
    }
}
for($i=0; $i<3; $i++)
    for($j=0; $j<$height; $j++)
        for($p=0; $p<$factor; $p++)
            for($q=0; $q<$factor; $q++)
                 imagesetpixel($card, $i*$factor + $p, $j*$factor + $q, $col_white);
for($i=$width-3; $i<$width; $i++)
    for($j=0; $j<$height; $j++)
        for($p=0; $p<$factor; $p++)
            for($q=0; $q<$factor; $q++)
                 imagesetpixel($card, $i*$factor + $p, $j*$factor + $q, $col_white);
for($i=0; $i<$width; $i++)
    for($j=0; $j<3; $j++)
        for($p=0; $p<$factor; $p++)
            for($q=0; $q<$factor; $q++)
                 imagesetpixel($card, $i*$factor + $p, $j*$factor + $q, $col_white);
for($i=0; $i<$width; $i++)
    for($j=$height-3; $j<$height; $j++)
        for($p=0; $p<$factor; $p++)
            for($q=0; $q<$factor; $q++)
                 imagesetpixel($card, $i*$factor + $p, $j*$factor + $q, $col_white);


for($i=0; $i<1; $i++)
    for($j=0; $j<$width/10; $j++)
        for($p=0; $p<$factor; $p++)
            for($q=0; $q<$factor; $q++)
                 imagesetpixel($card, $i*$factor + $p, $j*$factor + $q, $col_black);
for($i=0; $i<$width/10; $i++)
    for($j=0; $j<1; $j++)
        for($p=0; $p<$factor; $p++)
            for($q=0; $q<$factor; $q++)
                 imagesetpixel($card, $i*$factor + $p, $j*$factor + $q, $col_black);
for($i=$width-1; $i<$width; $i++)
    for($j=$height-$width/10; $j<$height; $j++)
        for($p=0; $p<$factor; $p++)
            for($q=0; $q<$factor; $q++)
                 imagesetpixel($card, $i*$factor + $p, $j*$factor + $q, $col_black);
for($i=$width-$width/10; $i<$width; $i++)
    for($j=$height-1; $j<$height; $j++)
        for($p=0; $p<$factor; $p++)
            for($q=0; $q<$factor; $q++)
                 imagesetpixel($card, $i*$factor + $p, $j*$factor + $q, $col_black);


//unlink('./card.png');
//$num = $_POST['id'];
$num = $_GET['id'];
imagepng($card,'./cards/'.$num.'.png');
imageDestroy($card);

$log_file = fopen("./cards/".$num.".txt", "w");  

for($i=0; $i<$width*$height*$factor*$factor; $i++)
    fwrite($log_file, $bytes[$i]);
fclose($log_file);  
?>
