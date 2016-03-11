<?php

    require_once('TesseractOCR/TesseractOCR.php');

    mysql_connect('localhost:3306', 'root', 'ysAb7cEkjvOa');
    $sql = "SELECT coordinateLabelX, coordinateLabelY, coordinateLabelX2, coordinateLabelY2 FROM datafields WHERE templateID = 0 AND dataFieldLabel = 'info'";
    mysql_select_db('templatedb');
    $result = mysql_query($sql);

    //$CI = &get_instance();
    //$this->templatedb = $CI->load->database('templatedb', TRUE);
    //$query = $this->templatedb->query("SELECT coordinateLabelX, coordinateLabelY, coordinateLabelX2, coordinateLabelY2 FROM datafields WHERE templateID = 0 AND dataFieldLabel = 'info'");

    // Create a blank image and add some text

    $ini_filename = 'attachments/bill.jpg';
    $im = imagecreatefromjpeg($ini_filename );

    //$ini_x_size = getimagesize($ini_filename )[0];
    //$ini_y_size = getimagesize($ini_filename )[1];

    //the minimum of xlength and ylength to crop.
    //$crop_measure = min($ini_x_size, $ini_y_size);

    // Set the content type header - in this case image/jpeg
    //header('Content-Type: image/jpeg');
    
    $row = mysql_fetch_array($result, MYSQL_NUM);
    $x1 = $row[0];
    $y1 = $row[1];
    $x2 = $row[2];
    $y2 = $row[3];
   

    $to_crop_array = array('x' => $x1 , 'y' => $y1, 'width' => $x2, 'height'=> $y2);
    $thumb_im = imagecrop($im, $to_crop_array);

    imagejpeg($thumb_im, 'attachments/cropped.jpg', 100);

    //echo "cropped ";
    //echo $ini_filename;

    $tesseract = new TesseractOCR('attachments/cropped.jpg');
    $tesseract->setLanguage(eng);
    $data = $tesseract->recognize();
    echo $data;

    mysql_close($link);

?>
