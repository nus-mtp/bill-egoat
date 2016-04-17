<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->

<!--
Authored by Tan Tack Poh
-->

<?php

   // Define global variable.
   $max_file_size = 2097152;
   $img_upload_directory = "images/";
   $supported_img_file_format = array("jpeg","jpg","png");

   // Define image file attributes variable
   if(isset($_FILES['image'])){
      $errors= array();
      $file_name = $_FILES['image']['name'];
      $file_size = $_FILES['image']['size'];
      $file_tmp = $_FILES['image']['tmp_name'];
      $file_type = $_FILES['image']['type'];
      $file_ext = strtolower(end(explode('.',$_FILES['image']['name'])));
      
      // Define supported image file formats
      $expansions = $supported_img_file_format;
      
      // Show error if unsupported file format is uploaded
      if(in_array($file_ext,$expansions)=== false){
         $errors[] = "extension not allowed, please choose a JPEG or PNG file.";
      }
      
      // Show error if file is oversized
      if($file_size > $max_file_size) {
         $errors[] = 'File size must be less than 2 MB.';
      }
      

      if(empty($errors)==true) {
         move_uploaded_file($file_tmp, $img_upload_directory . $file_name);
         echo "Stored in: " . $img_upload_directory . $file_name;
         
         $image = $file_name; /* Displaying Image*/
         $img = $img_upload_directory . $image;
         echo'<img src="'.$img.'">';
      
      }else{
         print_r($errors);
      }
   }
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
       <form action = "" method = "POST" enctype = "multipart/form-data">
         <input type = "file" name = "image" />
         <input type = "submit"/>
			
         <ul>
            <li>Sent file: <?php echo $_FILES['image']['name'];  ?>
            <li>File size: <?php echo $_FILES['image']['size'];  ?>
            <li>File type: <?php echo $_FILES['image']['type'] ?>
         </ul>
			
      </form>
    </body>
</html>
