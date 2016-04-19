<!DOCTYPE html>
<html lang="en">
<head>
    <title>TEMPLATE CREATION</title>

    <script src="https://code.jquery.com/jquery-2.2.0.min.js"></script>
    <style type="text/css" media="screen">
        canvas {
            background:url(<?php echo "https://www.billegoat.gq/".$billFilePath ?>);
            background-size: 100% 100%;
        }
    </style>
</head>

<body>
    <h1>TEMPLATE CREATION UI</h1>
    
    <p><span id="msg"><?php echo $billFilePath ?></span></p>
    <p><span id="msg">Press the "Load Bill" button to start defining the template</span></p>
    <p><button id="generateCanvas">Load Bill</button>
        <button id="submitLogo">Submit as Logo</button>
        <button id="submitDate">Submit as Due Date</button>
        <button id="submitAmount">Submit as Amount Due</button>
        <button id="saveCoords">Save Template Coords</button>
    
    </p>
    <div id="canvas">
    </div>
    
    <div id="templateIDPassing" style="display: none;"><?php $output = $templateID; printf($output); ?>
    </div>
    <div id="billFilePathPassing" style="display: none;"><?php $output = $billFilePath; printf($output); ?>
    </div>
    
    <script src="https://www.billegoat.gq/templateUI/script.js"></script>
    
    <?php
    if (isset($_POST['submit'])) {

    }
    ?>
    
</body>

</html>
