<h2><?php echo $title; ?></h2>

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
            echo "billModifiedTimeStamp : ".$bills_id['billModifiedTimeStamp']."<br>";

        ?>
            
        </div>
        
<?php endforeach; ?>   


<p>
   123
    ---    TEST GRAPH PAGE LOADED---- 
</p>
