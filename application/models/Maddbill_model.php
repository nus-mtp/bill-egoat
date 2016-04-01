<?php
class Maddbill_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
		
		$CI = &get_instance();
		$this->billdb = $CI->load->database('billdb', TRUE);
	}
	
	public function insert_bills_table()
	
	{	
		$data = array(
			'billFilePath' => NULL,
			'revisionNo' => 1,
			'templateID' => 1,
			'billSentDate' => $this->input->post('billSentDate'),
			'billDueDate' => $this->input->post('billDueDate'),
			'totalAmt' => $this->input->post('totalAmt'),
			'billIsComplete' => FALSE,
			'billIsVerified' => TRUE,
			'billIsCopy' => FALSE,
			'billCompleteDateTime' => NULL,	
		);

		$this->billdb->insert('bills', $data);
		return $id = $this->db->insert_id();
	}
    
    public function update_bills_table($billID) 
    {
        $data = array(
            'billSentDate' => $this->input->post('billSentDate'),
			'billDueDate' => $this->input->post('billDueDate'),
			'totalAmt' => $this->input->post('amt'),
        );
            
        $this->billdb->where('billID',$billID);
        return $this->billdb->update('bills',$data);
        
    }
	
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
	}
	
}