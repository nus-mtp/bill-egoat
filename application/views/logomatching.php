/* @author Tan Tack Poh
** Testing page to see if logo detection actually works
** Will remove due to security. Part of this will be incorporated into the view page where detection results will show.
*/

<?php

    echo "Running logo detection" . "<br>" . PHP_EOL;
    $command = escapeshellcmd('python /opt/bitnami/apache2/htdocs/application/views/OpenCV_Main/feature_matching.py');
    shell_exec($command);
    echo "Loading detectionResult.jpg (output of the py script)" . "<br>" . PHP_EOL;
    $image2 = '/opt/bitnami/apache2/htdocs/images/detection_result/detectionResult.jpg';
    
    if(file_exists($image2)){
        echo "The file has been processed" . "<br>" . PHP_EOL;
    } else {
        echo "The file has not been processed" . "<br>" . PHP_EOL;
    }

    $command = escapeshellcmd('rm -f /opt/bitnami/apache2/htdocs/images/detection_result/detectionResult.jpg');
    shell_exec($command);
    
?>