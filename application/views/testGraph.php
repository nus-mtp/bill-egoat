
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
   <title>Table of Bills</title>
     <script src="../js/jquery-latest.js"></script> 
        <script src="../js/jquery.tablesorter.js"></script> 
 </head>
 <body>

<h2><?php echo $title; ?></h2>


<table id="myTable" class="tablesorter"> 
    <thead> 
    <tr> 
       
        <th>billID</th> 
        <th>userID</th> 
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
        
    </tr> 
    </thead>
    
    <tbody> 
        
        <?php foreach ($bills as $bills_id): ?>
        
        <tr>  
        <td><?php echo $bills_id['billID']; ?></td>
        <td><?php echo $bills_id['userID']; ?></td>
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
        </tr>
        
        <?php endforeach; ?>     
    </tbody> 
</table> 
     
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
