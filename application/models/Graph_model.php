<?php

/* Model for getting and displaying bill data
** @author Qiu Yunhan
*/

class Graph_model extends CI_Model{

	public function __construct()
	{
		parent::__construct();
		
		$CI = &get_instance();
		$this->billdb = $CI->load->database('billdb', TRUE);
	}

	public function get_graphdata($billID = FALSE)
    {
        if ($billID === FALSE)
        {
                $query = $this->billdb->get_where('bills', array('userID' => $this->session->userdata('userID')));
				
                return $query->result_array();
        }

        $query = $this->billdb->get_where('bills', array('billID' => $billID));
        return $query->row_array();
    }
    
    public function get_billOrgs()
    {
        
        //$this->billdb->distinct();
        //$this->billdb->select('billOrg');    
       
        
        //$query = $this->billdb->get_where('bills', array('userID' => $this->session->userdata('userID')));
        
         $query = $this->billdb->query("SELECT billOrg, SUM(totalAmt) as sum FROM billdb.bills WHERE userID ='".$this->session->userdata("userID")."'GROUP BY billOrg");

        return $query->result_array();
    }
    
     public function get_billOrgMonths($billOrg)
    {
        
        $query = $this->billdb->query("SELECT billOrg, MONTHNAME(billDueDate) as month, SUM(totalAmt) as sum FROM billdb.bills WHERE userID ='".$this->session->userdata("userID")."' AND billOrg = '".$billOrg."' GROUP BY MONTHNAME(billDueDate)");
                                      
        return $query->result_array();
    }
  
    
}