<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!--
/* View to manually add bills
** @author Daryl Lim
*/
-->
	<head>
		<link href="https://www.billegoat.gq/css/bootstrap-datepicker3.min.css" rel="stylesheet" />
		<link href="https://www.billegoat.gq/css/forms.css" rel="stylesheet" />
	</head>

	<body>
	
	<div class="container center-block text-center">
		<h1>Add Bill Manually</h1>
		<!-- Bill Preview div-->
		<div class = "col-md-6 text-left"> 
			<h2>Bill Items</h2>
			<?php echo form_open_multipart('MAddBill/addManualBill');?>
			
			<label for="billOrg">Billing Organisation:</label>
			<input type="text" class="form-control" id="billOrg" name="billOrg" placeholder="e.g, Citibank" 
			value="<?php echo set_value('billOrg'); ?>"/>
			<br/>
			
			<label for="billSentDate">Billing Date:</label>
			<input type="text" class="form-control" id="billSentDate" name="billSentDate" placeholder="YYYY-MM-DD" 
			data-provide="datepicker" value="<?php echo set_value('billSentDate'); ?>"/>
			<?php echo form_error('billSentDate'); ?>
			<br/>
				 
			<label for="billDueDate">Date Due:</label>
			<input type="text" class="form-control" id="billDueDate" name="billDueDate" placeholder="YYYY-MM-DD" 
			data-provide="datepicker" value="<?php echo set_value('billDueDate'); ?>"/>
			<?php echo form_error('billDueDate'); ?>
			<br/>
				 
			<label for="totalAmt">Total Amount Due ($):</label>
			<input type="text" class="form-control" id="totalAmt" name="totalAmt" placeholder="e.g, 101.20" 
			value="<?php echo set_value('totalAmt'); ?>"/>
			<?php echo form_error('totalAmt'); ?>
			<br/>
				 
			<label for="billFilePath">Upload Bill Image</label>
			<input type="file" class="form-control" id="image" name="image" value="<?php echo set_value('image'); ?>"/>
			<?php echo form_error('image'); ?>
			<br/>
			
			<label for="tagName">Tags (Separated by commas)</label>
			<input type="text" class="form-control" id="tagName" name="tagName" placeholder="e.g, CC,amex,groceries" 
			value="<?php echo set_value('tagName'); ?>"/>
			<br/>
			
			<label for="isComplete">Mark as completed?</label>
			<input type="checkbox" id="isComplete" name="isComplete" 
			value="<?php echo set_value('isComplete'); ?>">
			
			<input type="text" class="form-control" id="dateCompleted" name="dateCompleted" placeholder="YYYY-MM-DD" 
			data-provide="datepicker" value="<?php echo set_value('dateCompleted'); ?>"/>
			<?php echo form_error('dateCompleted'); ?>
			
			<!-- Script to automatically toggle if bill is completed or not -->
			<script>
			$('#isComplete').change(function() 
			{
				$("#dateCompleted").toggle($(this).is(':checked'));
			});
			$('#isComplete').trigger('change');
			</script>
			<br/>
				 
			<button type="submit" class = "btn btn-primary">
				Add Bill
			</button>
			<?php echo form_close();?>
	
		</div>	
	</div>
	</body>
	<script  src="https://billegoat.gq/js/bootstrap-datepicker.js" />
	<script>
		$('.datepicker').datepicker({});
	</script>
</html>