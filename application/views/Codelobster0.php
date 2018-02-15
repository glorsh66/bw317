<?php 
#######################################################################
#				PHP Simple Captcha Script
#	Script Url: http://toolspot.org/php-simple-captcha.php
#	Author: Sunny Verma
#	Website: http://toolspot.org
#	License: GPL 2.0, @see http://www.gnu.org/licenses/gpl-2.0.html
########################################################################
$code=rand(1000,9999);
//$im = imagecreatetruecolor(50, 24);
$im = imagecreatefrompng('img/dogecoin-300.png');
$bg = imagecolorallocate($im, 22, 86, 165);
$fg = imagecolorallocate($im, 255, 255, 255);
//imagefill($im, 0, 0, $bg);
//imagestring($im, 5, 5, 5,  $code, $fg);

//imagefttext($im,20,5,100,100,$fg,'../fonts/DogeSans-Regular.otf',$code);
$initial_rast = 80;
for($i = 0; $i < strlen($code); $i++){
	$rand_angle = rand(-30,30);
	$initial_rast=$initial_rast+$i+20;
	imagefttext($im,20,$rand_angle,$initial_rast,100,$fg,'fonts/DogeSans-Regular.otf',$code[$i]);
}

imagepng($im);
imagedestroy($im);
?>