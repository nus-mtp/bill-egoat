<html xmlns="http://www.w3.org/1999/xhtml">
<!--
/* View to view bills (no editing)
** @author Daryl Lim
*/
-->
	<head>
		<!-- CSS -->
		<link href="https://www.billegoat.gq/css/forms.css" rel="stylesheet" />
		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	</head>
	
	<body>
	<div class="container center-block text-center">
		<h1>
			<?php 
				echo '<h1>View Bill '.$bills_id['billID'].'</h1>';
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
			<div class = "col-md-12 text-left">
				<h2>Bill Items</h2>
				<label for="billOrg">Billing Organisation:</label>
				<br/>
				<?php echo $bills_id['billOrg']; ?>
				<br/>
			</div>
		
			<div class = "col-md-6 text-left">
				<label for="billSentDate">Billing Date:</label>
				<br/>
				<?php echo $bills_id['billSentDate']; ?>
			</div>	
			
			<div class = "col-md-6 text-left">
				<label for="billDueDate">Date Due:</label>
				<br/>
				<?php echo $bills_id['billDueDate']; ?>
				<br/>
			</div>
			
			<div class = "col-md-12 text-left">
				<label for="totalAmt">Total Amount Due ($):</label>
				<br/>
				<?php echo $bills_id['totalAmt']; ?>
				<br/>
		
				<label for="tagName">Tags: </label>
				<br/>
				<?php 
					if(empty($tags) == FALSE)
					{
						foreach ($tags as $tags_item)
						{ 
							echo $tags_item['tagName'];
							echo ",";
						}
					}
				?>
				<br/>
		
				<label for="isComplete">Bill Completed:</label>
				<br/>
				<?php 
					if ($bills_id['billIsComplete'] == TRUE)
					{
						echo "Bill Completed On: ".$bills_id['billCompleteDateTime']."<br/>";
					}
					else
					{
						echo "Bill Not Completed <br/>";
					}
				?>
				<br/>
			</div>
			
			<br/>
		
			<span style = "display: inline;">
				<a href = "https://www.billegoat.gq/index.php/Graph/updateBill/<?php echo $bills_id['billID']?>">
					<button type = "submit" class = "btn btn-primary">
						Update Bill
					</button>
				</a>
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
</html>