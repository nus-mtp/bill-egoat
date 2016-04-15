<html xmlns="http://www.w3.org/1999/xhtml">
<!--
/* View to manually update bills
** @author Daryl Lim
*/
-->
	<head>
		<!-- CSS -->
		<link href="https://www.billegoat.gq/css/bootstrap-datepicker3.min.css" rel="stylesheet" />
		<link href="https://www.billegoat.gq/css/forms.css" rel="stylesheet" />
		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	</head>
	
	<body>
	<div class="container center-block text-center">
		<h1>
			<?php 
				echo '<h1>Update/Verify Bill '.$bills_id['billID'].'</h1>';
				echo '<h2>Current Revision: '.$bills_id['revisionNo'].'</h2>';
			?>
			<br/>
		</h1>
		<!-- Bill Image Preview -->
		<div class = "col-md-6 text-left">
			<h2>Current Bill Image Preview</h2>
			<br/>
				<img class = "img-responsive" src="<?php echo 'https://www.billegoat.gq/'.$bills_id['billFilePath'].'' ?>" alt = "Bill Image"
				onerror="this.src='https://www.billegoat.gq/images/core/placeholder.jpg';">
			<br/>
		</div>
		
		<!-- Bill Items -->
		<div class = "col-md-6 text-left">
		
			<?php echo form_open_multipart('MAddBill/updateBill');?>
			<div class = "col-md-12 text-left">
				<h2>Bill Items</h2>
				<label for="billOrg">Billing Organisation:</label>
				<input type="text" class="form-control" id="billOrg" name="billOrg" 
				placeholder="e.g, Citibank" value="<?php echo $bills_id['billOrg']; ?>"/>
				<br/>
			</div>
		
			<div class = "col-md-6 text-left">
				<label for="billSentDate">Billing Date:</label>
				<input type="text" class="form-control" id="billSentDate" name="billSentDate" placeholder="YYYY-MM-DD" 
				data-provide="datepicker" value="<?php echo $bills_id['billSentDate']; ?>"/>
				<?php echo form_error('billSentDate'); ?>
			</div>	
			
			<div class = "col-md-6 text-left">
				<label for="billDueDate">Date Due:</label>
				<input type="text" class="form-control" id="billDueDate" name="billDueDate" placeholder="YYYY-MM-DD" 
				data-provide="datepicker" value="<?php echo $bills_id['billDueDate']; ?>"/>
				<?php echo form_error('billDueDate'); ?>
				<br/>
			</div>
			
			<div class = "col-md-12 text-left">
				<label for="totalAmt">Total Amount Due ($):</label>
				<input type="text" class="form-control" id="totalAmt" name="totalAmt" 
				placeholder="e.g, 101.20" value="<?php echo $bills_id['totalAmt']; ?>"/>
				<?php echo form_error('totalAmt'); ?>
				<br/>
		
				<label for="billFilePath">Upload New Bill Image</label>
				<input type="file" class="form-control" id="image" name="image" />
				<br/>
		
				<label for="tagName">Tags (Separated by commas)</label>
				<input type="text-area" class="form-control" id="tagName" name="tagName" 
				placeholder="e.g, CC,amex,groceries" value = "<?php 
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
		
				<input type="text" class="form-control" id="dateCompleted" name="dateCompleted" placeholder="YYYY-MM-DD" 
				data-provide="datepicker" value="<?php echo $bills_id['billCompleteDateTime']; ?>"/>
				<?php echo form_error('dateCompleted'); ?>
				<br/>
			</div>
			
			<!-- Script to automatically toggle if bill is completed or not -->
			<script>
			$('#isComplete').change(function() 
			{
				$("#dateCompleted").toggle($(this).is(':checked'));
			});
			$('#isComplete').trigger('change');
			</script>
			<br/>
		
			<input type="hidden" id="isDelete" name="isDelete">
			
			<input type="hidden" name="billID" value="<?php echo $bills_id['billID'];?>">
			
			<input type="hidden" name="revisionNo" value="<?php 
			echo $bills_id['revisionNo']+1; // Auto-increment revision no.?>">
		
			<span style = "display: inline;">
				<button type = "submit" class = "btn btn-primary">
					Update Bill
				</button>
				<button type = "button" class = "btn btn-primary" id = "warn" data-toggle="modal" data-target="#myModal">
					Delete Bill
				</button>
			</span>
			<br/>
		
		<?php echo form_close();?>
		</div>
	</div>
	
	<!-- Modal -->
	<div id="myModal" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header text-center">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h1 class="modal-title">Confirmation</h1>
				</div>
				<div class="modal-body row text-center">
					<h2>Are you sure you wish to delete this bill?</h2>
				</div>
				<div class="modal-footer">
				<?php echo form_open('MAddBill/deleteBill');?>
					<input type="hidden" name="billID" value="<?php echo $bills_id['billID'];?>">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-primary" id="warn">Delete</button>
				<?php echo form_close();?>
				</div>
			</div>
		</div>
	</div>
	
	</body>
	<script src="https://billegoat.gq/js/bootstrap-datepicker.js" />
	<script>
		$('.datepicker').datepicker({});
	</script>
</html>