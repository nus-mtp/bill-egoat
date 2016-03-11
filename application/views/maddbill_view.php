<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!--
/* View to manually add bills
** @author Daryl Lim
*/
-->
	<head>
		<link href="https://www.billegoat.gq/css/bootstrap-datepicker3.min.css" rel="stylesheet" />
	</head>
	
	<body>
		<?php echo form_open_multipart('MAddBill/addManualBill');?>
		
		<label for="billOrg">Billing Organisation:</label>
		<input type="text" id="billOrg" name="billOrg" placeholder="e.g, Citibank"/>
		<br/>
		
		<label for="billSentDate">Billing Date:</label>
		<input type="text" id="billSentDate" name="billSentDate" placeholder="YYYY-MM-DD" data-provide="datepicker"/>
		<br/>
			 
		<label for="billDueDate">Date Due:</label>
		<input type="text" id="billDueDate" name="billDueDate" placeholder="YYYY-MM-DD" data-provide="datepicker"/>
		<br/>
			 
		<label for="totalAmt">Total Amount Due ($):</label>
		<input type="text" id="totalAmt" name="totalAmt" placeholder="e.g, 101.20"/>
		<br/>
			 
		<label for="billFilePath">Upload Bill Image</label>
		<input type="file" id="image" name="image"/>
		<br/>
		
		<label for="tagName">Tags (Separated by commas)</label>
		<input type="text" id="tagName" name="tagName" placeholder="e.g, CC,amex,groceries"/>
		<br/>
		
		<label for="billFilePath">Mark as completed?</label>
		<input type="checkbox" id="isComplete" name="isComplete" onclick = "dynInput(this);">
		<br/>
		
		<div id="insertinputs"></div>
			 
		<input type="submit" value="Add Bill"/>
		<?php echo form_close();?>
	</body>

	<script  src="https://billegoat.gq/js/bootstrap-datepicker.js" />
	<script>
		$('.datepicker').datepicker({});
	</script>
	<script type="text/javascript">
			 function dynInput(cbox) {
			  if (cbox.checked) {
				var input = document.createElement("input");
				input.type = "text";
				input.name= "dateCompleted";
				input.id= "dateCompleted";
				input.placeholder ="YYYY-MM-DD";
				input.setAttribute('data-provide','datepicker');
				var div = document.createElement("div");
				div.id = "hiddenField";
				div.innerHTML = "Date Bill Completed:";
				div.appendChild(input);
				document.getElementById("insertinputs").appendChild(div);
			  } else {
				document.getElementById("hiddenField").remove();
			  }
			}
			</script>
</html>