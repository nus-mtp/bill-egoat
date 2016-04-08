<?php

/* Model for getting and displaying bill data
** @author Qiu Yunhan, Daryl Lim
** @reviewer Daryl Lim
*/

class Graph_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		
		$CI = &get_instance();
		$this->billdb = $CI->load->database('billdb', TRUE);
	}

	/* Retrieves all bill data matching user logged in, or specific bill details
	** @author Qiu Yunhan, Daryl Lim
	** @parameter: Select options 0: All, 1: Unpaid, 2: Recent
	** @Output: Array of SQL items
	*/
	public function get_graphdata($billID = FALSE, $select)
	{
		// If no specific bill specified
		if ($billID === FALSE)
		{
			if ($select == 0)
			{
				// All Bills
				$query = $this->billdb->get_where('bills', array('userID' => $this->session->userdata('userID')));	
			}
			else if ($select == 1)
			{
				// Unpaid Bills
				$query = $this->billdb->query("SELECT * FROM billdb.bills WHERE userID ='".$this->session->userdata("userID")."'AND billIsComplete = 'FALSE'");
			}
			else
			{
				// Recent bills only
				$query = $this->billdb->query("SELECT * FROM billdb.bills WHERE userID ='".$this->session->userdata("userID")."'AND YEAR(billDueDate) = '".date("Y")."'");
			}
			return $query->result_array();
		}
		else
		{
			// If specific bill specified
			$query = $this->billdb->get_where('bills', array('billID' => $billID));
			return $query->row_array();
		}	
    }
	
	/* Retrieves distinct billOrgs matching user logged in, and sums by billOrg.
	** @author Qiu Yunhan, Daryl Lim (SQL)
	** @parameter: Select options 0: All, 1: Unpaid, 2: Recent
	** @Output: Array of SQL items
	*/
	public function get_billOrgs($select)
	{    
		if ($select == 0)
		{
			$query = $this->billdb->query("SELECT billOrg, SUM(totalAmt) as sum FROM billdb.bills WHERE userID ='".$this->session->userdata("userID")."'GROUP BY billOrg");
		}
		else if ($select == 1)
		{
			$query = $this->billdb->query("SELECT billOrg, SUM(totalAmt) as sum FROM billdb.bills WHERE userID ='".$this->session->userdata("userID")."' AND billIsComplete = 'FALSE' GROUP BY billOrg");
		}
		else
		{
			$query = $this->billdb->query("SELECT billOrg, SUM(totalAmt) as sum FROM billdb.bills WHERE userID ='".$this->session->userdata("userID")."' AND YEAR(billDueDate) = '".date("Y")."' GROUP BY billOrg");
		}
		
		return $query->result_array();
	}

	/* Retrieves distinct months matching billorg provided, for logged in user
	** @author Qiu Yunhan, Daryl Lim (SQL)
	** @parameter: Billing organisation to sum, Select options 0: All, 1: Unpaid, 2: Recent
	** @Output: Array of SQL items
	*/	
	public function get_billOrgMonths($billOrg, $select)
	{
		if ($select == 0)
		{
			$query = $this->billdb->query("SELECT billOrg, MONTHNAME(billDueDate) as month, SUM(totalAmt) as sum FROM billdb.bills WHERE userID ='".$this->session->userdata("userID")."' AND billOrg = '".$billOrg."' GROUP BY MONTHNAME(billDueDate)");
		}
		else if ($select == 1)
		{
			$query = $this->billdb->query("SELECT billOrg, MONTHNAME(billDueDate) as month, SUM(totalAmt) as sum FROM billdb.bills WHERE userID ='".$this->session->userdata("userID")."' AND billOrg = '".$billOrg."' AND billIsComplete = 'FALSE' GROUP BY MONTHNAME(billDueDate)");
		}
		else
		{
			$query = $this->billdb->query("SELECT billOrg, MONTHNAME(billDueDate) as month, SUM(totalAmt) as sum FROM billdb.bills WHERE userID ='".$this->session->userdata("userID")."' AND billOrg = '".$billOrg."' AND YEAR(billDueDate) = '".date("Y")."' GROUP BY MONTHNAME(billDueDate)");
		}	
			return $query->result_array();
	}

	/* Retrieves distinct years for bills for logged in user
	** @author Daryl Lim
	** @parameter: Select options 0: All, 1: Unpaid, 2: Recent
	** @Output: Array of years by user
	*/	
	public function get_billYears($select)
	{
		if ($select == 0)
		{
			$query = $this->billdb->query("SELECT DISTINCT YEAR(billDueDate) as year FROM billdb.bills WHERE userID = '".$this->session->userdata("userID")."' ORDER BY YEAR(billDueDate) DESC");
		}
		else if ($select == 1)
		{
			$query = $this->billdb->query("SELECT DISTINCT YEAR(billDueDate) as year FROM billdb.bills WHERE userID = '".$this->session->userdata("userID")."' AND billIsComplete = 'FALSE' ORDER BY YEAR(billDueDate) DESC");
		}
		else
		{
			$query = $this->billdb->query("SELECT DISTINCT YEAR(billDueDate) as year FROM billdb.bills WHERE userID = '".$this->session->userdata("userID")."' AND YEAR(billDueDate) = '".date("Y")."' ORDER BY YEAR(billDueDate) DESC");
		}	
		
		return $query->result_array();
	}
	
	/* Retrieves distinct months' average and total per year for logged in user
	** @author Daryl Lim
	** @parameter: Select options 0: All, 1: Unpaid, 2: Recent
	** @Output: Array of months
	*/	
	public function get_Months($billYear, $select)
	{
		if ($select == 1)
		{
			$query = $this->billdb->query("SELECT DISTINCT MONTH(billDueDate) as month, AVG(totalAmt) as avgAmt, SUM(totalAmt) as totalAmt FROM billdb.bills WHERE userID = '".$this->session->userdata("userID")."'AND YEAR(billDueDate) = '".$billYear."' AND billIsComplete = 'FALSE' GROUP BY MONTH(billDueDate) ORDER BY MONTH(billDueDate)");
		}
		else
		{
			$query = $this->billdb->query("SELECT DISTINCT MONTH(billDueDate) as month, AVG(totalAmt) as avgAmt, SUM(totalAmt) as totalAmt FROM billdb.bills WHERE userID = '".$this->session->userdata("userID")."'AND YEAR(billDueDate) = '".$billYear."' GROUP BY MONTH(billDueDate) ORDER BY MONTH(billDueDate)");
		}
	
		return $query->result_array();
	}
	
	/* Retrieves monthly billorg breakdown per year for logged in user
	** @author Daryl Lim
	** @parameter: Select options 0: All, 1: Unpaid, 2: Recent
	** @Output: Array of months, billorg, totalAmt
	*/	
	public function get_MthlyBillOrg($billYear, $select)
	{
		
		if ($select == 1)
		{
			$query = $this->billdb->query("SELECT DATE_FORMAT(billDueDate, '%b') AS month, billOrg, totalAmt FROM billdb.bills WHERE userID = '".$this->session->userdata("userID")."' AND YEAR(billDueDate) = '".$billYear."' AND billIsComplete = 'FALSE' ORDER BY billOrg, MONTH(billDueDate)");
		}
		else
		{
			$query = $this->billdb->query("SELECT DATE_FORMAT(billDueDate, '%b') AS month, billOrg, totalAmt FROM billdb.bills WHERE userID = '".$this->session->userdata("userID")."' AND YEAR(billDueDate) = '".$billYear."' ORDER BY billOrg, MONTH(billDueDate)");
		}
		return $query->result_array();
	}
	
	/* Retrieves average/total by year for logged in user
	** @author Daryl Lim
	** @Output: Array of years
	*/	
	public function get_Years($select)
	{
		if ($select == 0)
		{
			$query = $this->billdb->query("SELECT YEAR(billDueDate) as year, AVG(totalAmt) as avgAmt, SUM(totalAmt) as totalAmt FROM billdb.bills WHERE userID = '".$this->session->userdata("userID")."' GROUP BY YEAR(billDueDate) ORDER BY YEAR(billDueDate)");
		}
		else if ($select == 1)
		{
			$query = $this->billdb->query("SELECT YEAR(billDueDate) as year, AVG(totalAmt) as avgAmt, SUM(totalAmt) as totalAmt FROM billdb.bills WHERE userID = '".$this->session->userdata("userID")."' AND billIsComplete = 'FALSE' GROUP BY YEAR(billDueDate) ORDER BY YEAR(billDueDate)");
		}
		else
		{
			$query = $this->billdb->query("SELECT YEAR(billDueDate) as year, AVG(totalAmt) as avgAmt, SUM(totalAmt) as totalAmt FROM billdb.bills WHERE userID = '".$this->session->userdata("userID")."' AND YEAR(billDueDate) = '".date("Y")."' GROUP BY YEAR(billDueDate) ORDER BY YEAR(billDueDate)");
		}	
		
		return $query->result_array();
	}
}