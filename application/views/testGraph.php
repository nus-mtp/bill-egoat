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
    <script src="../js/highcharts.js"></script>
     
 </head>
 <body>

<h2><?php echo $title; ?></h2>

     
<!-- TABLE-->     

<table id="myTable" class="tablesorter"> 
    <thead> 
    <tr> 
       
        <th>billID</th> 
        <th>userID</th>
        <th>totalAmt</th>
        <th>billOrg</th>
        <th>billSentDate</th> 
        <th>billDueDate</th> 
        <th>billIsComplete</th> 
        <th>billCompleteDateTime</th> 
        <th>billIsVerified</th> 
        <th>billIsCopy</th> 
        <th>billModifiedTimeStamp</th> 
        <th>submittedTimeStamp</th> 
        <th>billFilePath</th> 
        <th>templateID</th>
        <th>View Bill</th> 
        <th>Update Bill</th> 
        <th>Template</th> 
        
    </tr> 
    </thead>
    
    <tbody> 
        
        <?php foreach ($bills as $bills_id): ?>
        
        <tr>  
        <td><?php echo $bills_id['billID']; ?></td>
        <td><?php echo $bills_id['userID']; ?></td>
        <td><?php echo $bills_id['totalAmt']; ?></td>
        <td><?php echo $bills_id['billOrg']; ?></td>
        <td><?php echo $bills_id['billSentDate']; ?></td>
        <td><?php echo $bills_id['billDueDate']; ?></td>
        <td><?php echo $bills_id['billIsComplete']; ?></td>
        <td><?php echo $bills_id['billCompleteDateTime']; ?></td>
        <td><?php echo $bills_id['billIsVerified']; ?></td>
        <td><?php echo $bills_id['billIsCopy']; ?></td>
        <td><?php echo $bills_id['billModifiedTimeStamp']; ?></td>
        <td><?php echo $bills_id['submittedTimeStamp']; ?></td>
        <td><?php echo $bills_id['billFilePath']; ?></td>
        <td><?php echo $bills_id['templateID']; ?></td>
        <td><a href="<?php echo site_url('Graph/viewBill/'.$bills_id['billID']); ?>">View Bill</a></td>
        <td><a href="<?php echo site_url('Graph/updateBill/'.$bills_id['billID']); ?>">Update Bill</a></td>
        <td><a href="<?php echo site_url('Templates/addTemplateFromBill/'.$bills_id['billID']); ?>">Use Bill to create Template</a></td>
        </tr>
        
        <?php endforeach; ?>     
    </tbody> 
</table> 
     
     
     
     
<!-- GRAPH-->       
     
     <div id="container" style="width:100%; height:600px; background-color: grey;"></div>
     
     <script>
         
    $(function () {

    var colors = Highcharts.getOptions().colors,
        categories = ['MSIE', 'Firefox', 'Chrome', 'Safari', 'Opera'],
        data = [{
            y: 56.33,
            color: colors[0],
            drilldown: {
                name: 'MSIE versions',
                categories: ['MSIE 6.0', 'MSIE 7.0', 'MSIE 8.0', 'MSIE 9.0', 'MSIE 10.0', 'MSIE 11.0'],
                data: [1.06, 0.5, 17.2, 8.11, 5.33, 24.13],
                color: colors[0]
            }
        }, {
            y: 10.38,
            color: colors[1],
            drilldown: {
                name: 'Firefox versions',
                categories: ['Firefox v31', 'Firefox v32', 'Firefox v33', 'Firefox v35', 'Firefox v36', 'Firefox v37', 'Firefox v38'],
                data: [0.33, 0.15, 0.22, 1.27, 2.76, 2.32, 2.31, 1.02],
                color: colors[1]
            }
        }, {
            y: 24.03,
            color: colors[2],
            drilldown: {
                name: 'Chrome versions',
                categories: ['Chrome v30.0', 'Chrome v31.0', 'Chrome v32.0', 'Chrome v33.0', 'Chrome v34.0',
                    'Chrome v35.0', 'Chrome v36.0', 'Chrome v37.0', 'Chrome v38.0', 'Chrome v39.0', 'Chrome v40.0', 'Chrome v41.0', 'Chrome v42.0', 'Chrome v43.0'
                    ],
                data: [0.14, 1.24, 0.55, 0.19, 0.14, 0.85, 2.53, 0.38, 0.6, 2.96, 5, 4.32, 3.68, 1.45],
                color: colors[2]
            }
        }, {
            y: 4.77,
            color: colors[3],
            drilldown: {
                name: 'Safari versions',
                categories: ['Safari v5.0', 'Safari v5.1', 'Safari v6.1', 'Safari v6.2', 'Safari v7.0', 'Safari v7.1', 'Safari v8.0'],
                data: [0.3, 0.42, 0.29, 0.17, 0.26, 0.77, 2.56],
                color: colors[3]
            }
        }, {
            y: 0.91,
            color: colors[4],
            drilldown: {
                name: 'Opera versions',
                categories: ['Opera v12.x', 'Opera v27', 'Opera v28', 'Opera v29'],
                data: [0.34, 0.17, 0.24, 0.16],
                color: colors[4]
            }
        }, {
            y: 0.2,
            color: colors[5],
            drilldown: {
                name: 'Proprietary or Undetectable',
                categories: [],
                data: [],
                color: colors[5]
            }
        }],
        browserData = [],
        versionsData = [],
        i,
        j,
        dataLen = data.length,
        drillDataLen,
        brightness;


    // Build the data arrays
    for (i = 0; i < dataLen; i += 1) {

        // add browser data
        browserData.push({
            name: categories[i],
            y: data[i].y,
            color: data[i].color
        });

        // add version data
        drillDataLen = data[i].drilldown.data.length;
        for (j = 0; j < drillDataLen; j += 1) {
            brightness = 0.2 - (j / drillDataLen) / 5;
            versionsData.push({
                name: data[i].drilldown.categories[j],
                y: data[i].drilldown.data[j],
                color: Highcharts.Color(data[i].color).brighten(brightness).get()
            });
        }
    }

    // Create the chart
    $('#container').highcharts({
        chart: {
            type: 'pie'
        },
        title: {
            text: 'Browser market share, January, 2015 to May, 2015'
        },
        subtitle: {
            text: 'Source: <a href="http://netmarketshare.com/">netmarketshare.com</a>'
        },
        yAxis: {
            title: {
                text: 'Total percent market share'
            }
        },
        plotOptions: {
            pie: {
                shadow: false,
                center: ['50%', '50%']
            }
        },
        tooltip: {
            valueSuffix: '%'
        },
        series: [{
            name: 'Browsers',
            data: browserData,
            size: '60%',
            dataLabels: {
                formatter: function () {
                    return this.y > 5 ? this.point.name : null;
                },
                color: '#ffffff',
                distance: -30
            }
        }, {
            name: 'Versions',
            data: versionsData,
            size: '80%',
            innerSize: '60%',
            dataLabels: {
                formatter: function () {
                    // display only if larger than 1
                    return this.y > 1 ? '<b>' + this.point.name + ':</b> ' + this.y + '%' : null;
                }
            }
        }]
    });
});
     
     </script>
     
     
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
