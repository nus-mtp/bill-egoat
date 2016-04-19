<?php

    /* @author Tan Tack Poh
    ** Testing page to see if logo recognition actually works
    ** Will remove due to security. Part of this will be incorporated into the view page where detection results will show.
    */

    // Parameters for the recognition result image's directory and file name, and the recognition algorithm file's too
    $detectionFileDirectory = "application/views/OpenCV_Main/";
    $resultDirectory = "images/detection_result/";
    $detectionFileName = "logo_recognition.py";
    $resultFileImgName = "recognitionResult.jpg";

    // Test run the algorithm and output the result recognition image
    echo "Running logo recognition" . "<br>" . PHP_EOL;
    $command = escapeshellcmd('python ' . $detectionFileDirectory . $detectionFileName);
    shell_exec($command);
    echo "Loading " . $resultFileImgName . "<br>" . PHP_EOL;
    $image2 = $resultDirectory . $resultFileImgName;
    
    // check if the image is actually created. Creation implies successful run of algorithm
    if(file_exists($image2)){
        echo "The file has been processed" . "<br>" . PHP_EOL;
    } else {
        echo "The file has not been processed" . "<br>" . PHP_EOL;
    }

    // remove the image once the check is complete.
    $command = escapeshellcmd('rm -f ' . $resultDirectory . $resultFileImgName);
    shell_exec($command);
    
?>