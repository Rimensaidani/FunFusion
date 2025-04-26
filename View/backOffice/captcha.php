<?php
/*
session_start();

// Set headers
header('Content-Type: image/png');

// Create image
$image = imagecreatetruecolor(120, 40);

// Colors
$bgColor = imagecolorallocate($image, 255, 255, 255); // white
$textColor = imagecolorallocate($image, 0, 0, 0); // black

// Fill background
imagefilledrectangle($image, 0, 0, 120, 40, $bgColor);

// Generate random captcha code
$captchaCode = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'), 0, 6);
$_SESSION['captcha'] = $captchaCode; // Save to session

// Add text to image
$font = __DIR__.'/arial.ttf'; // Make sure you have this font OR use built-in fonts
if (file_exists($font)) {
    imagettftext($image, 20, 0, 10, 30, $textColor, $font, $captchaCode);
} else {
    // fallback to imagestring if no TTF font
    imagestring($image, 5, 20, 10, $captchaCode, $textColor);
}

// Output the image
imagepng($image);
imagedestroy($image);
*/
?>

?>
<?php

ob_clean();         
session_start();   


$captcha_code = '';
$captcha_length = 6;
$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

for ($i = 0; $i < $captcha_length; $i++) {
    $captcha_code .= $characters[rand(0, strlen($characters) - 1)];
}

$_SESSION['captcha'] = $captcha_code;


$image_width = 210;
$image_height = 50;
$image = imagecreate($image_width, $image_height);


$background_color = imagecolorallocate($image, 255, 255, 255); 
$text_color = imagecolorallocate($image, 0, 0, 0);             


for ($i = 0; $i < 1000; $i++) {
    $noise_color = imagecolorallocate($image, rand(150,255), rand(150,255), rand(150,255));
    imagesetpixel($image, rand(0,$image_width), rand(0,$image_height), $noise_color);
}


for ($i = 0; $i < 10; $i++) {
    $line_color = imagecolorallocate($image, rand(100,255), rand(100,255), rand(100,255));
    imageline($image, rand(0,$image_width), rand(0,$image_height), rand(0,$image_width), rand(0,$image_height), $line_color);
}


$font_path = __DIR__ . '/assets/fonts/arial.ttf'; // Make sure the path is correct
$font_size = 16;


if (!file_exists($font_path)) {
    die('Font file not found: ' . $font_path);
}


$x = 10; 
$min_x = 10;
$max_x = 25; 

for ($i = 0; $i < strlen($captcha_code); $i++) {
    $angle = rand(-20, 20); 
    $y = 35;

 
    $box = imagettfbbox($font_size, $angle, $font_path, $captcha_code[$i]);
    $char_width = $box[2] - $box[0]; 


    $x += rand($max_x, $max_x + 10); 
    

    $result = imagettftext($image, $font_size, $angle, $x, $y, $text_color, $font_path, $captcha_code[$i]);
    
    if ($result === false) {
        error_log("Failed to render text for character: {$captcha_code[$i]} at position: {$x}, {$y}");
    }
}


if (ob_get_length()) ob_end_clean();

header('Content-type: image/png');
imagepng($image);
imagedestroy($image);

?>
