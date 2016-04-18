<?php

    require_once('TesseractOCR/TesseractOCR.php');

    // Create a blank image and add some text
$ini_filename = 'attachments/bill3.jpg';
$im = imagecreatefromjpeg($ini_filename );

$ini_x_size = getimagesize($ini_filename )[0];
$ini_y_size = getimagesize($ini_filename )[1];

//the minimum of xlength and ylength to crop.
//$crop_measure = min($ini_x_size, $ini_y_size);

// Set the content type header - in this case image/jpeg
//header('Content-Type: image/jpeg');

$to_crop_array = array('x' =>1000 , 'y' => 1000, 'width' => 500, 'height'=> 500);
$thumb_im = imagecrop($im, $to_crop_array);

imagejpeg($thumb_im, 'attachments/cropped.jpg', 100);

echo "cropped";
echo $ini_filename;

    //$tesseract = new TesseractOCR('attachments/bill.jpg');
    //$tesseract->setLanguage(eng);
    //echo $tesseract->recognize();

?>
