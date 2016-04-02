<!--
/* View for getting and displaying bill data
** @author Qiu Yunhan, modified by Daryl Lim
** @reviewer Daryl Lim
*/
-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<script src="../js/jquery-2.2.0.min.js"></script> 
		<script src="../js/jquery.tablesorter.js"></script> 
		<script src="../js/highcharts.js"></script>
		<script src="../js/drilldown.js"></script>
		<script src="../js/exporting.js"></script>
	</head>

	<body>

	<div class = "container center-block text-center">
		<h1>Bill Overview</h1>
	</div>
	
	<!-- Containers for Charts -->
	<div class = "row" id ="padrow">
		<div id="container" class="col-lg-6"></div>
		<div class="col-lg-6 text-center" id = "container2">
			<!-- Year Dropdown list -->
			<div class = "col-xs-2 col-centered">
				<select name="Select Year:" class = "form-control" id ="selectYear">
					<?php				
						// Select current year, or latest year if non-existent
						$selectedYear = $billYears[0]['year']; // Set to latest year in list
						
						$counter = 0;
						// Populate dropdown list with existing years
						foreach ($billYears as $row) 
						{
							// Set to current year if exists
							if ($row['year'] == date("Y"))
							{
								$selectedYear = date("Y");
							}
							
							// Populate all years
							echo '<option ';

							if ($row['year'] == $selectedYear)
							{
								echo 'selected ';
							}
							
							echo 'value ="'.$counter.'">';
							
							if ($row['year'] == NULL)
							{
								echo "Unspecified";
							}
							else
							{
								echo $row['year'];
							}
							
							echo '</option>';
							
							$counter++;
						}
					?>
				</select>
			</div>
			<!-- Sub-Container for monthly chart -->
			<div id="container3" class="row"></div>
		</div>
	</div>
	
	<!-- Script for billOrg Chart -->
	<script>
		$(function () 
		{
			// Create the chart
			$('#container').highcharts(
			{
				chart: 
				{
					type: 'pie'
				},
				title: 
				{
					text: 'User Spending'
				},
				subtitle: 
				{
					text: 'By Billing Organisations'
				},
				plotOptions: 
				{
					series: 
					{
						dataLabels: // Label display format. Name: $amt
						{
							enabled: true,
							format: '{point.name}: ${point.y:.1f}'
						}
					}
				},
				tooltip: {
					headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
					pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>${point.y:.2f}</b> <br/>'
				},
				series: [ // Display all billing organisations with summed amount
				{
					name: 'Bill Organisations',
					colorByPoint: true,
					data: [ <?php 
							$last = count($billOrgs);
							$counter = 1;
							foreach ($billOrgs as $row) 
							{
								echo "{name: '".$row['billOrg']."',";
								echo "y: ".$row['sum'].",";
								echo "drilldown: '".$row['billOrg']."'}";
									
								// Add "," if not the last entry
								if ($counter != $last)
								{
									echo ",";
									$counter++;
								}
							}
						?>]
					}],
						
					// Drilldown (Allows user to expand into months
					drilldown: 
					{
						series:[ <?php 
						
							$last = count($billOrgMths);
							$counter = 0;
							
							foreach ($billOrgMths as $row)
							{
								echo "{name: '".$row[0]['billOrg']."',";
								echo "id: '".$row[0]['billOrg']."',";
								echo "data: [";
								
								$end = count($row);
								$i = 0; 
								foreach ($row as $mth)
								{			
									if ($mth['month'] == NULL)
										{
											echo "['Unspecified',".$mth['sum']. "]";
										}
										else
										{
											echo "['".$mth['month']."',".$mth['sum']. "]";
										}
										
										if ($i != $end-1)
										{
											echo ",";
											$i++;
										}
								}
	
								echo "]}";
									
								if ($counter != $last-1)
								{
									 echo ",";
									 $counter++;
								}
							}
						?>]
				 }	
			});
		});
	</script>         
		
	<!-- Script for Monthly Chart -->
	<script>
		$(function () 
		{
			var chart;
			
			$(document).ready(function() 
			{
				chart = new Highcharts.Chart(
				{
					chart: 
					{
						renderTo: 'container3',
						type: 'column'
					},
					title: 
					{
						text: 'User Spending'
					},
					subtitle: 
					{
						text: 'By Year'
					},
					tooltip: 
					{
						valueDecimals: 2,
						valuePrefix: '$',
					},
					yAxis: 
					{
						title: 
						{
							text: 'Amount Payable ($)'
						}
					},
					xAxis: {
						categories: 
						[
						'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
						]
					},
					series: [
					{
						name: 'Data 1',
						stacking: 'normal',
						data: [29.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4]
					}, 
					{
						name: 'Data 2',
						stacking: 'normal',
						data: [144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4, 29.9, 71.5, 106.4, 129.2]
					}, 
					{    
						name: 'Average',
						type : 'spline',
						data: [35, 60, 77, 56.7, 34, 55, 66, 72, 90, 45, 56, 60],
						marker: 
						{
							lineWidth: 2,
							lineColor: Highcharts.getOptions().colors[3],
							fillColor: 'white'
						}
					}]
				});
			});
			
			/*
			$("#selectYear").on('change', function ()
			{
				// Pass PHP array to jQuery
				var arrYearPHP = [
				<?php 
					$counter = 0;
					$maxCount = count($billMonths);
					
					foreach ($billMonths as $row)
					{
						echo '"';
						
						switch ($billMonths['month'])
						{
							case 1:
								echo "Jan";
								break;
							case 2:
								echo "Feb";
								break;
							case 3:
								echo "Mar";
								break;	
							case 4:
								echo "Apr";
								break;	
							case 5:
								echo "May";
								break;	
							case 6:
								echo "Jun";
								break;	
							case 7:
								echo "Jul";
								break;	
							case 8:
								echo "Aug";
								break;	
							case 9:
								echo "Sep";
								break;	
							case 10:
								echo "Oct";
								break;	
							case 11:
								echo "Nov";
								break;	
							case 12:
								echo "Dec";
								break;	
							default:
								echo "Unspecified";
								break;
						}
						
						echo '"';
					}
				?>
				];
				var selYear = $("#selectYear").val();
				chart.xAxis[0].setCategories([]);
				
				chart.series[2].setData([32, 80, 37, 26.7, 14, 56, 76, 82, 10, 35, 46, 10]);
			});*/
		});
	</script>         
		
	<!-- TABLE-->     

	<table id="myTable" class="tablesorter table-responsive"> 
		<thead> 
		<tr> 
			<th>Bill Complete?</th>
			<th>Bill ID</th> 
			<th>Billing Organisation</th>
			<th>Amount Payable</th>
			<th>Sent Date</th> 
			<th>Due Date</th>  
			<th>Modified Date</th> 
			<th>Submitted Date</th> 
			<th>View Bill</th> 
			<th>Update Bill</th> 
			<th>Template</th> 
			
		</tr> 
		</thead>
		
		<tbody> 
			
			<?php foreach ($bills as $bills_id): ?>
			
			<tr>
			<td><?php echo $bills_id['billIsComplete']; ?></td>
			<td><?php echo $bills_id['billID']; ?></td>
			<td><?php echo $bills_id['billOrg']; ?></td>
			<td><?php echo $bills_id['totalAmt']; ?></td>
			<td><?php echo $bills_id['billSentDate']; ?></td>
			<td><?php echo $bills_id['billDueDate']; ?></td>
			<td><?php echo $bills_id['billModifiedTimeStamp']; ?></td>
			<td><?php echo $bills_id['submittedTimeStamp']; ?></td>
			<td><a href="<?php echo site_url('Graph/viewBill/'.$bills_id['billID']); ?>">View Bill</a></td>
			<td><a href="<?php echo site_url('Graph/updateBill/'.$bills_id['billID']); ?>">Update Bill</a></td>
			<td><a href="<?php echo site_url('Templates/addTemplateFromBill/'.$bills_id['billID']); ?>">Use Bill to create Template</a></td>
			</tr>
			
			<?php endforeach; ?>     
		</tbody> 
	</table> 


	</body>
</html>


<script>
$(document).ready(function() 
    { 
        $("#myTable").tablesorter(); 
    } 
); 
</script>