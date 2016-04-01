<h2><?php echo $title; ?></h2>

<?php foreach ($bills as $bills_id): ?>

        <h3><?php echo "billID : ".$bills_id['billID']; ?></h3>
        <div class="main">
         <?php 
            echo "userID : ".$bills_id['userID'];
            echo "submittedTimeStamp : ".$bills_id['submittedTimeStamp'];
            echo "billFilePath : ".$bills_id['billFilePath'];
            echo "revisionNo : ".$bills_id['revisionNo'];
            echo "templateID : ".$bills_id['templateID'];
            echo "billSentDate : ".$bills_id['billSentDate'];
            echo "billDueDate : ".$bills_id['billDueDate'];
            echo "billIsComplete : ".$bills_id['billIsComplete'];
            echo "billIsVerified : ".$bills_id['billIsVerified'];
            echo "billIsCopy : ".$bills_id['billIsCopy'];
            echo "billCompleteDateTime : ".$bills_id['billCompleteDateTime'];
            echo "billModifiedTimeStamp : ".$bills_id['billModifiedTimeStamp'];

        ?>
            
        </div>
        
<?php endforeach; ?>   


<p>
   123
    ---    TEST GRAPH PAGE LOADED---- 
</p>
