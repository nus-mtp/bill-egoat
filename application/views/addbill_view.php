<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!--
/* View to upload bills for processing automatically
** @author Daryl Lim
*/
-->
	<head>
		<link href="https://www.billegoat.gq/css/forms.css" rel="stylesheet" />
	</head>

	<body>
	
	<div class="container center-block text-center">
		<h1>Upload Bill</h1>
		<!-- Bill Preview div-->
		<div class = "col-md-6 center-block text-left"> 
			<?php echo form_open_multipart('AddBill/addBill');?>
			
			<label for="billOrg">Billing Organisation:</label>
			<input type="text" class="form-control" id="billOrg" name="billOrg" placeholder="e.g, Citibank" 
			value="<?php echo set_value('billOrg'); ?>"/>
			<?php echo form_error('billOrg'); ?>
			<br/>
				 
			<label for="billFilePath">Upload Bill Image</label>
			<input type="file" class="form-control" id="image" name="image" value="<?php echo set_value('image'); ?>"/>
			<?php echo form_error('image'); ?>
			<br/>
				 
			<button type="submit" class = "btn btn-primary">
				Add Bill
			</button>
			<?php echo form_close();?>
		</div>	
	</div>
	</body>
</html>