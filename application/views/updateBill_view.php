<html xmlns="http://www.w3.org/1999/xhtml">
<!--
/* View to manually update bills
** @author Daryl Lim
*/
-->
	<head>
		<link href="https://www.billegoat.gq/css/bootstrap-datepicker3.min.css" rel="stylesheet" />
					<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

	</head>
	<body>
	
		<?php echo form_open_multipart('MAddBill/updateBill');?>
		
		<?php echo "Current Revision: ".$bills_id['revisionNo']?>
		<br/>
		
		<label for="billOrg">Billing Organisation:</label>
		<input type="text" id="billOrg" name="billOrg" placeholder="e.g, Citibank"
		value="<?php echo $bills_id['billOrg']; ?>"/>
		<br/>
		
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
		<?php echo '<img src="https://www.billegoat.gq/'.$bills_id['billFilePath'].'" style="max-height: 20%; max-width: 20%;">'; ?>
		<br/>
		
		<label for="billFilePath">Upload New Bill Image</label>
		<input type="file" id="image" name="image" value="<?php echo $bills_id['billFilePath']; ?>"/>
		<br/>
		
		<label for="tagName">Tags (Separated by commas)</label>
		<input type="text" id="tagName" name="tagName" placeholder="e.g, CC,amex,groceries" 
		value = "<?php 
					if(empty($tags) == FALSE)
					{
						foreach ($tags as $tags_item)
						{ 
							echo $tags_item['tagName'];
							echo ",";
						}
					}
				?>">
		<br/>
		
		<label for="billFilePath">Mark as completed?</label>
		<input type="checkbox" id="isComplete" name="isComplete"
		<?php 
			if ($bills_id['billIsComplete'] == TRUE)
			{
				echo "checked";
			}
		?>>
		
		<input type="text" id="dateCompleted" name="dateCompleted" placeholder="YYYY-MM-DD" 
		data-provide="datepicker" value="<?php echo $bills_id['billCompleteDateTime']; ?>"/>
		
		<script>
		$('#isComplete').change(function() {
		$("#dateCompleted").toggle($(this).is(':checked'));
		});

		$('#isComplete').trigger('change');
		</script>
		
		<br/>
		
		<label for="billFilePath">Delete bill?</label>
		<input type="checkbox" id="isDelete" name="isDelete">
		<br/>
		
		<input type="hidden" name="billID" value="<?php echo $bills_id['billID'];?>">
		
		<input type="hidden" name="revisionNo" value="<?php 
		echo $bills_id['revisionNo']+1; // Auto-increment revision no.
		?>">
		
		<input type="submit" value="Update Bill"/>
		
		<?php echo form_close();?>
	</body>
	<script  src="https://billegoat.gq/js/bootstrap-datepicker.js" />
	<script>
		$('.datepicker').datepicker({});
		
	

    


	</script>

</html>