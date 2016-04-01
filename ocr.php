<?php

    //Written by Justin
    /*OCR processing, including reading the image, retrieving the coordinate data from the database,
      cropping out that part of the image and run OCR on it
      */
    
    function ocr ($id, $template)
        
    {
        //connect to mysql and getting the coordinate data
        require_once('TesseractOCR/TesseractOCR.php');

        $link = mysql_connect('localhost:3306', 'root', 'ysAb7cEkjvOa');
        mysql_select_db('billdb');
        //$id = 171;
        //$template = 54;

        $sql = "SELECT billFilePath from bills where billID = " . $id;
        $result = mysql_query($sql);
        $row = mysql_fetch_array($result, MYSQL_NUM);
        $ini_filename = $row[0];
        $im = imagecreatefromjpeg($ini_filename);

        //echo $ini_filename;
        //echo $id;
        //echo $template;

        mysql_select_db('templatedb');

        $sql = "SELECT coordinateLabelX, coordinateLabelY, coordinateLabelX2, coordinateLabelY2 FROM datafields WHERE templateID = " . $template ." AND dataFieldLabel = 'amount'";
        $result = mysql_query($sql);

        $row = mysql_fetch_array($result, MYSQL_NUM);
        $x1 = $row[0];
        $y1 = $row[1];
        $x2 = $row[2] - $row[0];
        $y2 = $row[3] - $row[1];

        //cropping the image using the coordinate data
        $to_crop_array = array('x' => $x1 , 'y' => $y1, 'width' => $x2, 'height'=> $y2);
        $thumb_im = imagecrop($im, $to_crop_array);

        imagejpeg($thumb_im, 'images/cropped1.jpg', 100);

        //run OCR on the cropped section
        $tesseract = new TesseractOCR('images/cropped1.jpg');
        $tesseract->setLanguage(eng);
        $amount = $tesseract->recognize();

        $sql = "SELECT coordinateLabelX, coordinateLabelY, coordinateLabelX2, coordinateLabelY2 FROM datafields WHERE templateID = ". $template ." AND dataFieldLabel = 'duedate'";

        $result = mysql_query($sql);

        $row = mysql_fetch_array($result, MYSQL_NUM);
        $x1 = $row[0];
        $y1 = $row[1];
        $x2 = $row[2] - $row[0];
        $y2 = $row[3] - $row[1];

        //cropping the image using the coordinate data
        $to_crop_array = array('x' => $x1 , 'y' => $y1, 'width' => $x2, 'height'=> $y2);
        $thumb_im = imagecrop($im, $to_crop_array);

        imagejpeg($thumb_im, 'images/cropped2.jpg', 100);

        //run OCR on the cropped section
        $tesseract = new TesseractOCR('images/cropped2.jpg');
        $tesseract->setLanguage(eng);
        $duedate = $tesseract->recognize();

        $amount = strtok($amount, " ");

        $day = strtok($duedate, " ");
        $month = strtok(" ");
        $year = strtok(" ");

        switch ($month) {
            case Jan:
                $month = "01";
                break;
            case Feb:
                $month = "02";
                break;
            case Mar:
                $month = "03";
                break;
            case Apr:
                $month = "04";
                break;
            case May:
                $month = "05";
                break;
            case Jun:
                $month = "06";
                break;
            case Jul:
                $month = "07";
                break;
            case Aug:
                $month = "08";
                break;
            case Sep:
                $month = "09";
                break;
            case Oct:
                $month = "10";
                break;
            case Nov:
                $month = "11";
                break;
            case Dec:
                $month = "12";
                break;
        }

        //echo "<br>" . $amount . "<br>";
        //echo $year;
        //echo $month;
        //echo $day;

        mysql_select_db('billdb');
        $sql = "UPDATE bills SET totalAmt = " . $amount . ", billDueDate = '" . $year . "-" . $month . "-" . $day . "' WHERE billID = " . $id;

        //echo "<br>" . $sql;

        $result = mysql_query($sql);

        mysql_close($link);
    }
?>
