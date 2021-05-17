<?php

session_start();
header ("Content-type: image/png");
if(isset($_SESSION['vervalue']))
{
unset($_SESSION['vervalue']); // destroy the session if already there
}
$text="0123456789";
$rnd=substr(str_shuffle($text),0,6);
//session_register("vervalue");
$_SESSION['vervalue']=$rnd;

$im = @ImageCreate (80, 20)
or die ("Cannot Initialize new GD image stream");
$background_color = ImageColorAllocate ($im, 204, 204, 204); // Assign background color
$text_color = ImageColorAllocate ($im, 51, 51, 255);      // text color is given 
ImageString($im,5,5,2,$_SESSION['vervalue'],$text_color); // Random string  from session added 

ImagePng ($im); // image displayed
imagedestroy($im); // Memory allocation for the image is removed. 

?>