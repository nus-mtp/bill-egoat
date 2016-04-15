<!--
/* View for search results
** @author Daryl Lim
*/
-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<script src="https://www.billegoat.gq/js/jquery-2.2.0.min.js"></script> 
		<script src="https://www.billegoat.gq/js/jquery.tablesorter.js"></script> 
		<script src="https://www.billegoat.gq/js/highcharts.js"></script>
		<script src="https://www.billegoat.gq/js/drilldown.js"></script>
		<script src="https://www.billegoat.gq/js/exporting.js"></script>
	</head>
	<body>
		<div class = "row" id ="padrow">
			<div class="col-md-12">
				<h1> Search Results: </h1>
				<!-- TABLE-->     
				
				<?php if(!empty($results))
				{		
					echo "
						<table id='myTable' class='tablesorter table-responsive'> 
							<thead>
							<tr> 
								<th>ID</th>
								<th>Bill Org.</th>
								<th>Amt Due</th>
								<th>Sent Date</th> 
								<th>Due Date</th>  
								<th>Modified On</th> 
								<th>Submitted On</th> 
								<th>Bill Controls</th>
								
							</tr> 
							</thead>
					";
				}
				else
				{
					echo "<table> <p>No results found</p>";
				}?>
					<tbody> 
						
						<?php if(!empty($results)) foreach ($results as $row): ?>
						
						<td><?php echo $row['billID']; ?></td>
						<td id = "left"><?php echo $row['billOrg']; ?></td>
						<td  id = "left"><?php echo $row['totalAmt']; ?></td>
						<td><?php echo $row['billSentDate']; ?></td>
						<td><?php echo $row['billDueDate']; ?></td>
						<td><?php echo $row['billModifiedTimeStamp']; ?></td>
						<td><?php echo $row['submittedTimeStamp']; ?></td>
						<td>
							<a href="<?php echo site_url('Graph/viewBill/'.$row['billID']); ?>">View Bill</a> | 
							<a href="<?php echo site_url('Graph/updateBill/'.$row['billID']); ?>">Update Bill</a> | 
							<a href="<?php echo site_url('Templates/addTemplateFromBill/'.$row['billID']); ?>">Create Template</a>
						</td>
						</tr>
						<?php endforeach; ?>     
					</tbody> 
				</table>
				<?php echo "Found ".count($results)." results."; ?>
			</div>
		</div>
	</body>
</html>

<script>
$(document).ready(function() 
    { 
        $("#myTable").tablesorter(); 
    } 
); 
</script>