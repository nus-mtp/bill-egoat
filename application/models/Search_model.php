<?php

/* Model for searching bill data
** @author Daryl Lim
*/

class Search_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		
		$CI = &get_instance();
		$this->billdb = $CI->load->database('billdb', TRUE);
	}

	/* Search by billing organisation, tags, amount
	** @author Daryl Lim
	** @Output: Array of SQL items
	*/
	public function search_bills()
	{
		$inputArr = $this->search_split();
		$billOrgs = $this->search_billOrgTag($inputArr);
		
		return $billOrgs;
	}
	
	/* Search by billing organisation/tags
	** @author Daryl Lim
	** @Output: Array of SQL items
	*/
	private function search_billOrgTag($inputArr)
	{
		$billOrgsMatch = array();
		$counter = 1;
		$countMax = count($inputArr);
		$queryStatement = "";
		
		foreach ($inputArr as $row)
		{
			// Search by billOrg
			$queryStatement = $queryStatement."(SELECT * FROM billdb.bills WHERE userID ='".$this->session->userdata("userID")."' AND billOrg LIKE '%".$row."%')";
			$queryStatement = $queryStatement." UNION DISTINCT ";
			//Search by billTags
			$queryStatement = $queryStatement."(SELECT * FROM billdb.bills WHERE userID ='".$this->session->userdata("userID")."' AND billID IN (SELECT billID FROM billdb.billTags WHERE tagName LIKE '%".$row."%'))";
			
			// If more than one token in query string
			if ($counter < $countMax)
			{
				$queryStatement = $queryStatement." UNION DISTINCT ";
				$counter++;
			}
			else
			{	
				$query = $this->billdb->query($queryStatement);
				return $query->result_array();
			}
		} 
    }
	
	/* Splits string into arrays
	** @author Daryl Lim
	** @Output: Array of SQL items
	*/
	private function search_split()
	{
		 $inputStr = $this->input->post('searchInput');
		 $inputArr = explode(" ", $inputStr);
		 return $inputArr;
    }
	
	
}