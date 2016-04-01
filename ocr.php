<?php

    //Written by Justin
    /*OCR processing, including reading the image, retrieving the coordinate data from the database,
      cropping out that part of the image and run OCR on it
      */
    
    
    //connect to mysql and getting the coordinate data
    require_once('TesseractOCR/TesseractOCR.php');

    mysql_connect('localhost:3306', 'root', 'ysAb7cEkjvOa');
    $sql = "SELECT coordinateLabelX, coordinateLabelY, coordinateLabelX2, coordinateLabelY2 FROM datafields WHERE templateID = 0 AND dataFieldLabel = 'info'";
    mysql_select_db('templatedb');
    $result = mysql_query($sql);

    $ini_filename = 'attachments/bill.jpg';
    $im = imagecreatefromjpeg($ini_filename );
    
    $row = mysql_fetch_array($result, MYSQL_NUM);
    $x1 = $row[0];
    $y1 = $row[1];
    $x2 = $row[2];
    $y2 = $row[3];
   

    //cropping the image using the coordinate data
    $to_crop_array = array('x' => $x1 , 'y' => $y1, 'width' => $x2, 'height'=> $y2);
    $thumb_im = imagecrop($im, $to_crop_array);

    imagejpeg($thumb_im, 'attachments/cropped.jpg', 100);

    //echo "cropped ";
    //echo $ini_filename;


    //run OCR on the cropped section
    $tesseract = new TesseractOCR('attachments/cropped.jpg');
    $tesseract->setLanguage(eng);
    $data = $tesseract->recognize();
    echo $data;

    mysql_close($link);

?>
