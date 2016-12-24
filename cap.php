<?php

require_once 'core/init.php';

header('Content-type: image/jpeg');

$text = Captcha::get('secure');

$font_size = 30;

$image_width = 110;

$image_hieght = 40;



$image = imagecreate($image_width, $image_hieght);

imagecolorallocate($image, 255, 255, 255);

$text_color = imagecolorallocate($image, 0, 0, 0);



//lines



for ($i=1; $i <= 30; $i++) { 

	$x1 = rand(1, 100);

	$y1 = rand(1, 100);

	$x2 = rand(1, 100);

	$y2 = rand(1, 100);





	imageline($image, $x1, $y1, $x2, $y2, $text_color);

}



imagettftext($image, $font_size, 0, 15, 30, $text_color, 'includes/fonts/cap.ttf', $text);

imagejpeg($image);

?>

