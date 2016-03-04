<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<link href="https://www.billegoat.gq/css/bootstrap-datepicker3.min.css" rel="stylesheet" />
	</head>
	
	<body>

		<?php echo form_open_multipart('MAddBill/updateBill');?>
		<h1>Update Bill Details:</h1>
	   
		 <label for="billSentDate">Billing Date:</label>
			<input type="text" id="billSentDate" name="billSentDate" placeholder="YYYY-MM-DD" 
			data-provide="datepicker" value="<?php echo $bills_id['billSentDate']; ?>"/>
			<br/>
				 
			<label for="billDueDate">Date Due:</label>
			<input type="text" id="billDueDate" name="billDueDate" placeholder="YYYY-MM-DD" 
			data-provide="datepicker" value="<?php echo $bills_id['billDueDate']; ?>"/>
			<br/>
				 
			<label for="totalAmt">Total Amount Due ($):</label>
			<input type="text" id="totalAmt" name="totalAmt" placeholder="e.g, 101.20" 
			value="<?php echo $bills_id['totalAmt']; ?>"/>
			<br/>
			
			<label>Current Bill Image Preview </label>
			<br/>
			<?php echo '<img src="https://www.billegoat.gq/'.$bills_id['billFilePath'].'" width=300>'; ?>
			<br/>
			
			<label for="billFilePath">Upload New Bill Image</label>
			<input type="file" id="image" name="image" value="<?php echo $bills_id['billFilePath']; ?>"/>
			<br/>

			<input type="hidden" name="billID" value="<?php echo $bills_id['billID'];?>">
			<input type="hidden" name="revisionNo" value="<?php echo $bills_id['revisionNo']+1;?>">
		 
			<input type="submit" value="Update Bill"/>
			
			<?php echo form_close();?>
	</body>
	<script  src="https://billegoat.gq/js/bootstrap-datepicker.js" />
	<script>
		$('.datepicker').datepicker({
		});
	</script>
</html>