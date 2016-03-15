<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!--
/* View to manually add bills
** @author Daryl Lim
*/
-->
	<head>
		<link href="https://www.billegoat.gq/css/bootstrap-datepicker3.min.css" rel="stylesheet" />
	</head>

	<body>
		<?php echo form_open_multipart('MAddBill/addManualBill');?>
		
		<label for="billOrg">Billing Organisation:</label>
		<input type="text" id="billOrg" name="billOrg" placeholder="e.g, Citibank" 
		value="<?php echo set_value('billOrg'); ?>"/>
		<br/>
		
		<label for="billSentDate">Billing Date:</label>
		<input type="text" id="billSentDate" name="billSentDate" placeholder="YYYY-MM-DD" 
		data-provide="datepicker" value="<?php echo set_value('billSentDate'); ?>"/>
		<?php echo form_error('billSentDate'); ?>
		<br/>
			 
		<label for="billDueDate">Date Due:</label>
		<input type="text" id="billDueDate" name="billDueDate" placeholder="YYYY-MM-DD" 
		data-provide="datepicker" value="<?php echo set_value('billDueDate'); ?>"/>
		<?php echo form_error('billDueDate'); ?>
		<br/>
			 
		<label for="totalAmt">Total Amount Due ($):</label>
		<input type="text" id="totalAmt" name="totalAmt" placeholder="e.g, 101.20" 
		value="<?php echo set_value('totalAmt'); ?>"/>
		<?php echo form_error('totalAmt'); ?>
		<br/>
			 
		<label for="billFilePath">Upload Bill Image</label>
		<input type="file" id="image" name="image"/>
		<br/>
		
		<label for="tagName">Tags (Separated by commas)</label>
		<input type="text" id="tagName" name="tagName" placeholder="e.g, CC,amex,groceries" 
		value="<?php echo set_value('tagName'); ?>"/>
		<br/>
		
		<label for="billFilePath">Mark as completed?</label>
		<input type="checkbox" id="isComplete" name="isComplete" 
		value="<?php echo set_value('isComplete'); ?>">
		
		<input type="text" id="dateCompleted" name="dateCompleted" placeholder="YYYY-MM-DD" 
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
			 
		<input type="submit" value="Add Bill"/>
		<?php echo form_close();?>
	</body>
	<script  src="https://billegoat.gq/js/bootstrap-datepicker.js" />
	<script>
		$('.datepicker').datepicker({});
	</script>
</html>