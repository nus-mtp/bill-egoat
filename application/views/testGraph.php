<!--
/* View for getting and displaying bill data
** @author Qiu Yunhan
*/
-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
   <title>Table of Bills</title>
    <script src="../js/jquery-latest.js"></script> 
    <script src="../js/jquery.tablesorter.js"></script> 
    <script src="https://billegoat.gq/js/highcharts.js"></script>
 </head>
 <body>

<h2><?php echo $title; ?></h2>

     
    
     
<!-- GRAPH-->  

     
    <div id="container" style="width:100%; height:600px; background-color: grey;"></div>
     
    <script>
    
    $(function () {
    // Create the chart
        $('#container').highcharts({
            chart: {
                type: 'pie'
            },
            title: {
                text: 'User Spending'
            },
            subtitle: {
                text: 'By Billing Organisations'
            },
            plotOptions: {
                series: {
                    dataLabels: {
                        enabled: true,
                        format: '{point.name}: ${point.y:.1f}'
                    }
                }
            },

            tooltip: {
                headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>${point.y:.2f}</b> <br/>'
            },
            series: [{
                name: 'Bill Organisations',
                colorByPoint: true,
                data: [ <?php 

                        $last = count($billOrgs);
                        $counter = 1;

                        foreach ($billOrgs as $row) {
                            echo "{name: '".$row['billOrg']."',";
                            echo "y: ".$row['sum'].",";
                            echo "drilldown: '".$row['billOrg']."'}";
            

                            if ($counter != $last){
                                echo ",";
                                $counter++;
                            }

                        }
                    ?>]
                 }],
                
            //----------drilldown here
             drilldown: {
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
                                 echo "['".$mth['month']."',".$mth['sum']. "]";
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
     
            //--------
            
        });
    });
     </script>     
     
<!-- TABLE-->     

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
     
     
 <?php print_r($billOrgMths);?>
     
     <br>
     
     <?php 

                        $last = count($billOrgMths);
                        echo "last  = ".$last;
                        $counter = 0;
                        $prevRowOrg = 'noPrevOrg';
     
                        foreach ($billOrgMths as $row)
                        {
                           
                            echo "{name: '".$row[0]['billOrg']."',";
                            echo "id: '".$row[0]['billOrg']."',";
                            echo "data: [";
                            
                            $end = count($row);
                            $i = 0; 
                            foreach ($row as $mth)
                            {
                                 echo "[".$mth['month'].",".$mth['sum']. "]";
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
                                
                        
                    ?>
     
     
     <br>
       <br>
       <br>


     <?php 

                        $last = count($billOrgs);
                        $counter = 1;

                        foreach ($billOrgs as $row) {
                            echo "{name: '".$row['billOrg']."',";
                            echo "y: ".$row['sum'].",";
                            echo "drilldown: '".$row['billOrg']."'}";
            

                            if ($counter != $last){
                                echo ",";
                                $counter++;
                            }

                        }
                    ?>
         
     

     
     
 </body>
</html>




<!--
<?php foreach ($bills as $bills_id): ?>

        <h3><?php echo "billID : ".$bills_id['billID']; ?></h3>
        <p><a href="<?php echo site_url('Graph/viewBill/'.$bills_id['billID']); ?>">View Bill</a></p>
        <p><a href="<?php echo site_url('Graph/updateBill/'.$bills_id['billID']); ?>">Update Bill</a></p>
        <div class="main">
         <?php 
            echo "userID : ".$bills_id['userID']."<br>";
            echo "submittedTimeStamp : ".$bills_id['submittedTimeStamp']."<br>";
            echo "billFilePath : ".$bills_id['billFilePath']."<br>";
            echo "revisionNo : ".$bills_id['revisionNo']."<br>";
            echo "templateID : ".$bills_id['templateID']."<br>";
            echo "billSentDate : ".$bills_id['billSentDate']."<br>";
            echo "billDueDate : ".$bills_id['billDueDate']."<br>";
            echo "billIsComplete : ".$bills_id['billIsComplete']."<br>";
            echo "billIsVerified : ".$bills_id['billIsVerified']."<br>";
            echo "billIsCopy : ".$bills_id['billIsCopy']."<br>";
            echo "billCompleteDateTime : ".$bills_id['billCompleteDateTime']."<br>";
            echo "billModifiedTimeStamp : ".$bills_id['billModifiedTimStamp']."<br>";

        ?>
            
        </div>
        
<?php endforeach; ?>   

-->

<script>
$(document).ready(function() 
    { 
        $("#myTable").tablesorter(); 
    } 
); 
</script>

<p>
   123
    ---    TEST GRAPH PAGE LOADED---- 
</p>
