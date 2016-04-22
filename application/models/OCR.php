<?php

	/* OCR Model Script
	** @author Justin
	** @reviewer Daryl Lim
	*/
    require_once('TesseractOCR/TesseractOCR.php');

    $CI = &get_instance();
	
	$this->templatedb = $CI->load->database('tempaltedb', TRUE);

    $query = $this->templatedb->query("SELECT coordinateLabelX, coordinateLabelY, coordinateLabelX2, coordinateLabelY2 FROM datafields WHERE templateID = 0 AND dataFieldLabel = 'info'");

    // Create a blank image and add some text
    $ini_filename = 'attachments/bill.jpg';
    $im = imagecreatefromjpeg($ini_filename);

    $to_crop_array = array('x' => $query[0] , 'y' => $query[1], 'width' => $query[2]- $query[0], 'height'=> $query[3] - $query[1]);
    $thumb_im = imagecrop($im, $to_crop_array);

    imagejpeg($thumb_im, 'attachments/cropped.jpg', 100);

    echo "cropped ";
    echo $ini_filename;

    mysql_close($link);
?>
