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
		$this->billdb = $CI->load->database('billdb', TRUE); // Load DB to be used
	}
	
	/* Inserts form fields into DB billdb.bills
	** @author Daryl Lim
	** @Parameter: image name to be posted to DB
	** @Output: Row ID/billID of inserted path
	*/
	public function insert_bills($imgName)
	{		
		//Prepare array for posting to DB
		$data = array(
		
			// Compulsory/Default values to post
			'submittedTimestamp' => date("Y-m-d h:m:s",time()), // Automatically post current server time
			'revisionNo' => 1, // Default revision upon creation
			'templateID' => 0, // Default for manually entered bill with no template
			'billIsVerified' => TRUE, // Default, bill is 'human-verified' as it is manually entered.
			'billIsCopy' => FALSE, //Default, as bill is original copy
			'userID' => $this->session->userdata('userID'),
			
			// User-entered values, NULL/FALSE if empty
			'billFilePath' => $this->writeImgPath($imgName),
			'billOrg' => $this->setNulltoUnknown('billOrg'),
			'billSentDate' => $this->input->post('billSentDate'),
			'billDueDate' => $this->input->post('billDueDate'),
			'totalAmt' => $this->setNulltoZero('totalAmt'),
		);
		
		// Append flag and date/time if bill is completed
		$data = $this->postIsComplete($data);
		
		// Post form values to bills table and get auto-generated ID
		$this->billdb->insert('bills', $data);
		$billID = $this->billdb->insert_id();
		
		// Post tags to billsDB.billTags
		$this->postTags($billID);
		
		return $billID;
	}
    
	/* Updates form fields into DB billdb.bills
	** @author Daryl Lim
	** @Parameter: billID, image name to be posted to DB
	** @Output: billID updated
	*/
    public function update_bills_table($billID, $imgName) 
    {		
		// Clear existing tags
		$query = $this->billdb->delete('billTags', array('billID' => $billID));

		//Prepare array for posting to DB
		$data = array(
		
			// Compulsory/Default values to post
			'billIsVerified' => TRUE, // Default, bill is 'human-verified' as it is manually updated.
			'revisionNo' => $this->input->post('revisionNo'), // Automatically incremented every update
			'userID' => $this->session->userdata('userID'),
			
			// User-entered values, NULL/FALSE if empty
			'billOrg' => $this->setNulltoUnknown('billOrg'),
			'billSentDate' => $this->input->post('billSentDate'),
			'billDueDate' => $this->input->post('billDueDate'),
			'totalAmt' => $this->setNulltoZero('totalAmt'),
		);
		
		// Append flag and date/time if bill is completed
		$data = $this->postIsComplete($data);
		
		// Replace existing image if needed
		if ($imgName != NULL)
		{
			$this->del_curr_img($billID); // Delete existing image
			$imgName = $this->writeImgPath($imgName); // Write new filepath
			
			$data_new = array('billFilePath' => $imgName);
			$data = array_merge($data, $data_new);
		} 
		
		// Update DB entry
        $this->billdb->where('billID', $billID);
        $this->billdb->update('bills', $data);
		
		$this->postTags($billID);
		
		return $billID;
    }

	/* Deletes a bill from billDB.bills
	** @author Daryl Lim
	** @Parameter: billID
	*/
    public function delete_bills_table($billID) 
    {		
		// Clear existing tags
		$query = $this->billdb->delete('billTags', array('billID' => $billID));
		
		// Delete bill and image
		$this->del_curr_img($billID);
		$query = $this->billdb->delete('bills', array('billID' => $billID));

		return $billID;
    }
	
	/* Uploads a file to server @ root/images/
	** @author Tan Tack Poh, modified by Daryl Lim
	** @Output Unique filepath for uploaded image, or error message
	*/
	public function upload()
	{
		if($_FILES['image']['size'] != 0)
		{
			$file_name = $_FILES['image']['name'];
			$file_size = $_FILES['image']['size'];
			$file_tmp = $_FILES['image']['tmp_name'];
			$file_type = $_FILES['image']['type'];
			$tmp = explode('.',$_FILES['image']['name']);
			$file_ext = strtolower(end($tmp)); // Get file extension
			
			// Generate unique ID for file
			$uniqueName = uniqid().".".$file_ext;
			move_uploaded_file($file_tmp, "images/".$uniqueName);  
				
			if (($this->upload_chk_ext($file_ext)) == FALSE) // Illegal file extension
			{
				if(file_exists($uniqueName))
				{
					unlink($uniqueName);
				}
				return "EXT_ERR";
			}
			
			if (($this->upload_chk_size($file_size)) == FALSE) // Illegal file size
			{
				if(file_exists($uniqueName))
				{
					unlink($uniqueName);
				}
				return "SIZE_ERR";
			}
			
			// No errors, and non-null
			if ((($_FILES['image']['name']) != NULL))
			{	
				return $uniqueName;
			}
		}
		else
		{
			return NULL;
		}
	}
	
	// ==================================== Private helper functions
	
	/* Appends isComplete info to database array if completed, else delete
	** @author Daryl Lim
	** @Parameter: Database array, string to be appended
	** @Output: Database array to be posted
	*/
	private function postIsComplete($arr)
	{
		// Append completed flag if bill is completed
		if (isset($_POST['isComplete']))
		{
			$dateComplete = array(
				'billIsComplete' => TRUE,
				'billCompleteDateTime' => $this->input->post('dateCompleted'));
			
			return array_merge($arr, $dateComplete);
		}
		else // Flag removed, bill incomplete
		{
			$dateComplete = array(
				'billIsComplete' => FALSE,
				'billCompleteDateTime' => NULL);
			
			return array_merge($arr, $dateComplete);
		}
	}
	
	/* Parses CSV string into array, trims whitespace and removes duplicate values
	** @author Daryl Lim
	** @Parameter: CSV String
	** @Output: array with unique, trimmed values, else NULL
	*/
	private function trimUniqueCSV($csv_str)
	{
		if (empty($csv_str) == FALSE) // Check that string is not empty
		{
			$strArray = str_getcsv($csv_str); // Convert string to array
			$strArray = array_unique($strArray); // Remove duplicate values
			$strArray = array_map('trim', $strArray); // Trim whitespaces
			
			return $strArray;
		}
		else
		{
			return NULL;
		}
	}
	
	/* Posts tags in form field to billsDB.billTags (INSERT/UPDATE)
	** @author Daryl Lim
	** @Parameter: billID to insert/update
	*/
	private function postTags($billID)
	{
		// Clear existing tags if exists
		$query = $this->billdb->delete('billTags', array('billID' => $billID));
		
		// Parse tag string to array
		$tagStr = $this->input->post('tagName');
		$strArray = $this->trimUniqueCSV($tagStr);
		
		// Insert each tag into tags table
		if ($strArray != NULL)
		{	
			foreach ($strArray as $tag)
			{
				$tagRow = array(
					'billID' => $billID,
					'tagName' => $tag,
				);	
				$this->billdb->insert('billTags', $tagRow);
			}
		}
	}

	/* Deletes existing image matching bill ID
	** @author Daryl Lim
	** @Parameter: billID
	*/
    public function del_curr_img($billID) 
    {	
		// Retrieve existing image path
		$query = $this->billdb->get_where('bills', array('billID' => $billID));
		$rowData = $query->row();
		$imgPath = $rowData->billFilePath;
		
		// Delete image
		if(file_exists($imgPath))
		{
			unlink($imgPath);
		}
	}
	
	/* Retrieves tags matching bill ID
	** @author Daryl Lim
	** @Parameter: billID
	** @Output: array of SQL tag objects or NULL if no tags
	*/
    public function get_tags($billID) 
    {	
		$query = $this->billdb->get_where('billTags', array('billID' => $billID));
		
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		else
		{
			return NULL;
		}
	}
	
	/* Returns "Unknown" IFF form field is empty/null
	** @author Daryl Lim
	** @Parameter: String of form field's name
	** @Output: variable containing string
	*/
	private function setNulltoUnknown($formField)
	{
		if ($this->input->post($formField)==NULL)
		{
			return "Unknown";
		}
		else
		{
			return $this->input->post($formField);
		}
	}
	
	/* Returns 0 IFF form field is empty/null
	** @author Daryl Lim
	** @Parameter: String of form field's name
	** @Output: variable containing 0 or string
	*/
	private function setNullToZero($formField)
	{
		if ($this->input->post($formField)==NULL)
		{
			return 0;
		}
		else
		{
			return $this->input->post($formField);
		}
	}
	
	/* Writes image path for uploaded image
	** @author Daryl Lim
	** @Parameter: Random, unique image name
	** @Output: String with image path.
	*/
	private function writeImgPath($imgName)
	{
		if ($imgName == NULL)
		{
			return NULL;
		}
		else
		{
			return "images/".$imgName;
		}
	}
	
	/* Helper function to validate an uploaded file's extension
	** @author Tan Tack Poh, modified by Daryl Lim
	*/
	private function upload_chk_ext($file_ext)
	{
		// Allowed file formats
		$legal_ext = array("jpeg","jpg","png","gif","pdf","bmp","tiff");
		
		// Check for allowed file formats
		if(in_array($file_ext, $legal_ext) === FALSE)
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	/* Helper callback function to validate an uploaded file's size
	** @author Tan Tack Poh, modified by Daryl Lim
	*/
	private function upload_chk_size($file_size)
	{
		// Enforce maximum file size
		if($file_size > 5242880) 
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
}