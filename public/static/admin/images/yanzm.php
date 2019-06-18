<?php
/**
 * Created by PhpStorm.
 * User: heyl
 * Date: 2019/6/11
 * Time: 15:27
 */

ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);
session_start();

$image = imagecreate(100, 30);
$bgColor = imagecolorallocate($image, 255, 255, 255);
imagefill($image, 0, 0, $bgColor);
$capch_code = "";
for ($i = 0; $i < 4; $i++) {
    $fontSize = 6;
    $fontColor = imagecolorallocate($image, rand(0, 120), rand(0, 120), rand(0, 120));
    $fonts = 'qwertyuiopasdfghjklzxcvbnm0123456789';
    $fontContent = substr($fonts, rand(0, strlen($fonts)), 1);
    $capch_code .= "$fontContent";
    $x = ($i * 100 / 4) + rand(5, 10);
    $y = rand(5, 10);
    imagestring($image, $fontSize, $x, $y, $fontContent, $fontColor);
}
$_SESSION['code'] = $capch_code;
for ($i = 0; $i < 100; $i++) {
    $pointColor = imagecolorallocate($image, rand(50, 200), rand(50, 200), rand(50, 200));
    imagesetpixel($image, rand(0, 99), rand(0, 29), $pointColor);
}
for ($i = 0; $i < 1; $i++) {
    $lineColor = imagecolorallocate($image, rand(60, 200), rand(60, 200), rand(60, 200));
    imageline($image, rand(1, 99), rand(1, 29), rand(1, 99), rand(1, 29), $lineColor);
}
header('content-type:image/png');
imagepng($image);
imagedestroy($image);