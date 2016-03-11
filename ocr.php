<?php

    require_once('TesseractOCR/TesseractOCR.php');

    $conn = new mysqli('localhost:3306', 'root', 'ysAb7cEkjvOa');
    $sql = "SELECT coordinateLabelX, coordinateLabelY, coordinateLabelX2, coordinateLabelY2 FROM datafields WHERE templateID = 0 AND dataFieldLabel = 'info'";
$result = $conn->query($sql);

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
    
/*
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $x1 = $row[0];
        $y1 = $row[1];
        $x2 = $row[2];
        $y2 = $row[3];
    }
} else {
    echo "0 results";
    

    echo $x1;
    echo $y1;
    echo $x2;
    echo $y2;
*/

    $to_crop_array = array('x' => 1700 , 'y' => 0, 'width' => 600, 'height'=> 250);
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
