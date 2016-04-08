<!--
/* View for getting and displaying bill data
** @author Qiu Yunhan, Daryl Lim
** @reviewer Daryl Lim
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

	<div class = "container center-block text-center">
		<h1><?php echo $title; ?></h1>
	</div>
	
	<!-- Containers for Charts -->
	<div class = "row" id ="padrow">
		<div id="container" class="col-lg-6"></div>
		<div class="col-lg-6 text-center" id = "container2">
			<!-- Year Dropdown list -->
			<div class = "col-xs-2 col-centered">
				<select name="Select Year:" class = "form-control" id ="selectYear">
					<option> Select Year: </option>
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
	
	<!--Pass PHP array to jQuery-->
	<script>
		$(function () 
			{
				// billMths[year][month], by year
				var billMths = new Array();
				var billAvg = new Array();
				var billTotal = new Array();
				var billBrkdown = new Array();
				
				var years = new Array();
				var yearTotal = new Array();
				var yearAvg = new Array();
				
				var maxYrs = <?php echo count($billYears).';'?>
				
				// Pass PHP months array to jQuery
				<?php
					
					// Yearly statistics
					$yrCnt = 0;
					
					foreach ($billYearsAT as $row) //iterate each year
					{	
						if ($row['year'] == NULL)
						{
							echo 'years['.$yrCnt.'] = "Unspecified";';
						}
						else
						{
							echo 'years['.$yrCnt.'] = "'.$row['year'].'";';
						}
						
						echo 'yearTotal['.$yrCnt.'] = '.$row['totalAmt'].';';
						echo 'yearAvg['.$yrCnt.'] = '.$row['avgAmt'].';';
						
						$yrCnt++;
						
					}
					
					// Monthly breakdown
				
					$yearCounter = 0;
					
					
					foreach ($billMthBillOrg as $year) // iterate through each year
					{
						$rowCnt = 0;
						echo 'tempArr = new Array();';
						
						foreach ($year as $row) // Iterate through each year's bills
						{
							echo 'tempArr['.$rowCnt.'] = new Array();';
							echo 'tempArr['.$rowCnt.'] = [';
							echo '"'.$row['billOrg'].'",';
							echo '"'.$row['month'].'",';
							echo $row['totalAmt'].'];';
							
							$rowCnt++;
						}
						// Populate javascript array
						
						echo 'billBrkdown['.$yearCounter.'] = tempArr;';
						
						$yearCounter++;
					}
					
					// Monthly statistics
					$yearCount = 0;
							
					foreach ($billMonths as $row) // iterate through each year
					{	
						// Populate months list
						echo 'billMths['.$yearCount.'] = new Array();
						billMths['.$yearCount.'] = [';
						
						$mthCount = 0;
						$maxMthCount = count($row);
						
						// Populate year with months
						foreach ($row as $mth)
						{
							echo $mth['month']; // From 0-12
							
							if ($mthCount != $maxMthCount)
							{
								echo ',';
							}
							
							$mthCount++;
						}
								
						echo '];';
								
						// Populate average
						echo 'billAvg['.$yearCount.'] = new Array();
						billAvg['.$yearCount.'] = [';
						
						$mthCount = 0;
						$maxMthCount = count($row);
						
						// Populate year with months
						foreach ($row as $mth)
						{
							echo $mth['avgAmt']; // From 0-12
							
							if ($mthCount != $maxMthCount)
							{
								echo ',';
							}
							
							$mthCount++;
						}
						
						echo '];';
						
						// Populate average
						echo 'billTotal['.$yearCount.'] = new Array();
						billTotal['.$yearCount.'] = [';
								
						$mthCount = 0;
						$maxMthCount = count($row);
						
						// Populate year with months
						foreach ($row as $mth)
						{
							echo $mth['totalAmt']; // From 0-12
							
							if ($mthCount != $maxMthCount)
							{
								echo ',';
							}
							
							$mthCount++;
						}
						
						echo '];';
						
						$yearCount++;
					}
				?>
						
				// Make global
				window.billMths = billMths;
				window.billAvg = billAvg;
				window.billTotal = billTotal;
				
				window.years = years;
				window.yearTotal = yearTotal;
				window.yearAvg = yearAvg;
				window.billBrkdown = billBrkdown;
		});
	</script>
	
	<!-- Script for billOrg Chart -->
	<script>
		$(function () 
		{
			// Create the chart
			$('#container').highcharts(
			{
				chart: 
				{
					type: 'pie',
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
				// Create chart
				chart = new Highcharts.Chart(
				{
					chart: 
					{
						renderTo: 'container3',
						type: 'column',
                        zoomType: 'x',
                        panning: true,
                        panKey: 'shift'
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
						categories: years
					},
					series: 
					[{    
						name: 'Average',
						type : 'spline',
						data: yearTotal,
						marker: 
						{
							lineWidth: 2,
							lineColor: Highcharts.getOptions().colors[3],
							fillColor: 'white'
						},
						zIndex: 1
					},
					{    
						name: 'Total',
						type : 'spline',
						data: yearAvg,
						marker: 
						{
							lineWidth: 2,
							lineColor: Highcharts.getOptions().colors[4],
							fillColor: 'white'
						},
						zIndex: 2
					}]
				});
			});
			
			$("#selectYear").on('change', function ()
			{
				var selYear = $("#selectYear").val();
				var yearMonths = new Array();		

				// Change to names
				for (i = 0; i < billMths[selYear].length; i++)
				{	
					console.log("Switched Month: " +billMths[selYear][i]);
					switch(billMths[selYear][i])
					{
						case 1:
							yearMonths[i] = "Jan";
							break;
						case 2:
							yearMonths[i] = "Feb";
							break;
						case 3:
							yearMonths[i] = "Mar";
							break;	
						case 4:
							yearMonths[i] = "Apr";
							break;	
						case 5:
							yearMonths[i] = "May";
							break;	
						case 6:
							yearMonths[i] = "Jun";
							break;	
						case 7:
							yearMonths[i] = "Jul";
							break;	
						case 8:
							yearMonths[i] = "Aug";
							break;	
						case 9:
							yearMonths[i] = "Sep";
							break;	
						case 10:
							yearMonths[i] = "Oct";
							break;	
						case 11:
							yearMonths[i] = "Nov";
							break;	
						case 12:
							yearMonths[i] = "Dec";
							break;	
						default:
							yearMonths[i] = "Unspecified";
							break;
					}
				}
				
				// Dynamically update chart according to selected year.
				// Set title according to year
				chart.setTitle(null, { text: 'By Month'}, false);
				// Set average spline according to selected year.
				chart.xAxis[0].setCategories(yearMonths, false);
				chart.series[0].setData(billAvg[selYear], false);
				chart.series[1].setData(billTotal[selYear], false);
				
				// Add stacked columns
				var brkdown = billBrkdown[selYear];
				
				// Change month to index
				for (i = 0; i < brkdown.length; i++)
				{
					for (j = 0; j < yearMonths.length; j++)
					{
						if (brkdown[i][1] == yearMonths[j])
						{
							brkdown[i][1] = j;
						}
					}
				}
				
				var currOrg = brkdown[0][0];
				var currMth = brkdown[0][1];
				var currAmt = brkdown[0][2];
				var currSeries = 2;
				var currPt = 0;

				// Clear existing series
				var seriesLength = chart.series.length;
				for(var i = seriesLength - 1; i > 1; i--) 
				{
					chart.series[i].remove();
				}
				
				chart.addSeries(
				{
					name: brkdown[0][0],
					stacking: 'normal'
				});
				
				chart.series[currSeries].addPoint([currMth, currAmt], false); // Add first point
				currPt++;
				
				for (i = 1; i < brkdown.length; i++)
				{
					if (brkdown[i][0] == currOrg) // billOrg match, may not match month
					{
						
						if (brkdown[i][1] == currMth) // billOrg AND month match
						{
							currAmt = currAmt + brkdown[i][2]; // Sum totalAmt
							
							currPt--;
							chart.series[currSeries].removePoint(currPt, false); // Remove previous point
							
							chart.series[currSeries].addPoint([currMth, currAmt], false); // Add summed point at x(month), y(amt)
							currPt++;
						}
						else // Only billOrgs match, month does not match (New point)
						{
							console.log("billOrgs match, month doesn't match");
							// Add new point
							currMth = brkdown[i][1];
							currAmt = brkdown[i][2];
							chart.series[currSeries].addPoint([currMth, currAmt], false);
							currPt++;
						}
					}
					else //billOrgs do NOT match
					{
						console.log("billOrgs do not match");
						// Start new series
						currPt = 0;
						currSeries++; 
						currOrg = brkdown[i][0];
						currMth = brkdown [i][1];
						currAmt = brkdown [i][2];
						
						chart.addSeries(
						{
							name: currOrg,
							stacking: 'normal'
						});
						
						chart.series[currSeries].addPoint([currMth, currAmt], false);
						currPt++;
					}
				}
				
				chart.redraw();
			});
		});
	</script>         
	
	<div class = "row" id ="padrow">
		<div id="container4" class="col-md-12">
			<!-- TABLE-->     

			<table id="myTable" class="tablesorter table-responsive"> 
				<thead> 
				<tr> 
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