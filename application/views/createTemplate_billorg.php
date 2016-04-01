<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>

 </head>
 <body>
    <!--Create Template Input Billing Organization written by James-->
     <?php
     
     echo "Bill Image: <br>";
     
     echo '<img src="https://www.billegoat.gq/'.$bills_id['billFilePath'].'"><br>';
     
     ?>

     
 <?php echo form_open('Templates/addTemplate/'.$bills_id['billID']);?>
     <label for="billingOrgName">Name of billing organization:</label>
     <input type="text" id="billingOrgName" name="billingOrgName"/>

     <br/>
	 <input type="hidden" id="bill_id" name="bill_id" value="<?php echo $bills_id['billID']; ?>"/>
     <br/>
	 
     <input type="submit" value="Proceed to create template"/>
 <?php echo form_close();?>
 </body>

</html>