<?php

/* Model class to manually add/update bills
** @author Daryl Lim
*/
class Maddbill_model extends CI_Model 
{
	function __construct()
	{
		parent::__construct();
		
		$CI = &get_instance();
		$this->billdb = $CI->load->database('billdb', TRUE);
	}
	
	/* Inserts form fields into DB billdb.bills
	** @author Daryl Lim
	** @Parameter: image name to be posted to DB
	** @Output: Row ID/billID of inserted path
	*/
	public function insert_bills_table($imageName)
	{	
		//Set default amount to 0 instead of NULL if left empty, for graphing purposes
		if ($this->input->post('totalAmt')==NULL){
			$amt=0;
		}
		else
		{
			$amt= $this->input->post('totalAmt');
		}
		
		//Set filename to NULL instead of blank if left empty, to avoid duplicate empty str filepaths
		if ($imageName==NULL){
			$file=NULL;
		}
		else
		{
			//Append parent directory to image name
			$file="images/".$imageName;
		}
		
		//Prepare array for posting to DB
		$data = array(
			'billFilePath' => $file,
			'submittedTimestamp' => date("Y-m-d h:m:s",time()), //Automatically post current server time
			'revisionNo' => 1, //Default revision upon creation
			'templateID' => 0, //Default for manually entered bill with no template
			'billSentDate' => $this->input->post('billSentDate'),
			'billDueDate' => $this->input->post('billDueDate'),
			'totalAmt' => $amt,
			'billIsComplete' => FALSE, //Default, bill is not complete
			'billIsVerified' => TRUE, //Default, bill is 'human-verified' as it is manually entered.
			'billIsCopy' => FALSE, //Default, as bill is original copy
			'billCompleteDateTime' => NULL,	//Default, bill is not complete and so has no time complete
		);
		
		//Post form values to DB
		$this->billdb->insert('bills', $data);
		return $id = $this->db->insert_id();
	}
    
	/* Updates form fields into DB billdb.bills
	** @author Daryl Lim
	** @Parameter: billID, image name to be posted to DB
	** @Output: NIL
	*/
    public function update_bills_table($billID, $imageName) 
    {
		//Set default amount to 0 instead of NULL if left empty, for graphing purposes
		if ($this->input->post('totalAmt')==NULL){
			$amt=0;
		}
		else
		{
			$amt= $this->input->post('totalAmt');
		}
		
		//If no file uploaded, do not replace current file
		if ($imageName==NULL){
			$data = array();
		}
		else
		{
			//Append parent directory to new image name
			$file="images/".$imageName;
			$data = array('billFilePath' => $file);
		}
		
		//Append text fields to array for posting to DB
		$new_data = array(
			'revisionNo' => $this->input->post('revisionNo'), //Default revision upon creation
			'billSentDate' => $this->input->post('billSentDate'),
			'billDueDate' => $this->input->post('billDueDate'),
			'totalAmt' => $amt,
		);
		
		$data = array_merge($data, $new_data);
            
		//Update DB entry
        $this->billdb->where('billID',$billID);
        return $this->billdb->update('bills',$data);
    }
	
	/* Functions unavailable for now
	public function insert_bill_amts_table()
	
	{	
		$data = array(
			'billID' => 1,
			'amtLabel' => $this->input->post('amtLabel'),
			'amt' => $this->input->post('amt'),
			'currency' => 'SGD',
		);

		return $this->billdb->insert('billAmts', $data);	
	}
	
	public function insert_bill_tags_table()
	
	{	
		$data = array(
			'billID' => 1,
			'tagName' => $this->input->post('tagName'),
		);
		
		return $this->billdb->insert('billTags', $data);
		
	}*/
	
	/* Uploads a file to server @ root/images/
	** @author Daryl Lim
	*/
	public function upload()
	{
		if(isset($_FILES['image']))
		{
			$errors= array();
			$file_name = $_FILES['image']['name'];
			$file_size = $_FILES['image']['size'];
			$file_tmp = $_FILES['image']['tmp_name'];
			$file_type = $_FILES['image']['type'];
			$tmp = explode('.',$_FILES['image']['name']);
			$file_ext=strtolower(end($tmp));
      
			// Enforce maximum file size
			if($file_size > 2097152) 
			{
				$errors[]='File size must be less than 2 MB';
			}
      
			// TODO: Enforce accepted formats
			
			// Upload if no errors
			if((empty($errors)==true)&&(($_FILES['image']['name'])!=NULL)) 
			{
				// TODO: Message sent successful
				
				// Generate unique ID for file
				$uniqueName = uniqid().".".$file_ext;
				move_uploaded_file($file_tmp, "images/".$uniqueName);  
				return $uniqueName;
			}
			else
			{
				// TODO: Alert when fail
				return NULL;
			}
		}
	}
}