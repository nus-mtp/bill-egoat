<?php

    echo "Original Bill Details<br><br>";

    echo "userID : ".$bills_id['userID']."<br>";
    echo "submittedTimeStamp : ".$bills_id['submittedTimeStamp']."<br>";
    echo "billFilePath : ".$bills_id['billFilePath']."<br>";
    echo "revisionNo : ".$bills_id['revisionNo']."<br>";
    echo "templateID : ".$bills_id['templateID']."<br>";
    echo "billSentDate : ".$bills_id['billSentDate']."<br>";
    echo "billDueDate : ".$bills_id['billDueDate']."<br>";
    echo "billAmount : ".$bills_id['totalAmt']."<br>";
    echo "billIsComplete : ".$bills_id['billIsComplete']."<br>";
    echo "billIsVerified : ".$bills_id['billIsVerified']."<br>";
    echo "billIsCopy : ".$bills_id['billIsCopy']."<br>";
    echo "billCompleteDateTime : ".$bills_id['billCompleteDateTime']."<br>";
    echo "billModifiedTimeStamp : ".$bills_id['billModifiedTimeStamp']."<br>";

    echo "<br>"."<br>"."<br>";

    echo form_open('MAddBill/updateBill');?>
    <h1>Enter Updated Bill Details:</h1>;
   
     <label for="billSentDate">Date of Bill (YYYY-MM-DD):</label>
     <input type="text" size="20" id="billSentDate" name="billSentDate"/>
     <br/>
	 
	<label for="billDueDate">Date Due (YYYY-MM-DD):</label>
     <input type="text" size="20" id="billDueDate" name="billDueDate"/>
     <br/>
	 
	 <label for="amtLabel">Type of Payment:</label>
     <input type="text" size="20" id="amtLabel" name="amtLabel"/>
     <br/>
	 
	 <label for="amt">Amount:</label>
     <input type="text" size="20" id="amt" name="amt"/>
     <br/>
	 
	 <label for="billSentDate">Tag:</label>
     <input type="text" size="20" id="tagName" name="tagName"/>
     <br/>

     <input type="hidden" name="billID" value="<?php echo $bills_id['billID'];?>">
	 
     <input type="submit" value="Update Bill"/>
    
    <?php echo form_close();
?>