<?php
class Templates_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
		
		$CI = &get_instance();
		$this->templatedb = $CI->load->database('templatedb', TRUE);
	}
	
	public function insert_templates_table()
	
	{	
		$data = array(
			'billOrg' => $this->input->post('billingOrgName'),
            'dateCreated' => date("Y-m-d h:m:s",time()),
		);

		$this->templatedb->insert('templates', $data);
		return $id = $this->db->insert_id();
	}
    
    public function insert_datafields_table()        
    {
        $logodata = array(
            'templateID' => 2,
            'dataFieldLabel' => "logo",
            'coordinateLabelX' => $_POST['submissionArray'][0][0],
            'coordinateLabelY' => $_POST['submissionArray'][0][1],
            'coordinateLabelX2' => $_POST['submissionArray'][0][2],
            'coordinateLabelY2' => $_POST['submissionArray'][0][3],
        );
        $this->templatedb->insert('datafields', $logodata);
            
        $datedata = array(
            'templateID' => 2,
            'dataFieldLabel' => "duedate",
            'coordinateLabelX' => $_POST['submissionArray'][1][0],
            'coordinateLabelY' => $_POST['submissionArray'][1][1],
            'coordinateLabelX2' => $_POST['submissionArray'][1][2],
            'coordinateLabelY2' => $_POST['submissionArray'][1][3],
        );
        $this->templatedb->insert('datafields', $datedata);
            
        $amountdata = array(
            'templateID' => 2,
            'dataFieldLabel' => "amount",
            'coordinateLabelX' => $_POST['submissionArray'][2][0],
            'coordinateLabelY' => $_POST['submissionArray'][2][1],
            'coordinateLabelX2' => $_POST['submissionArray'][2][2],
            'coordinateLabelY2' => $_POST['submissionArray'][2][3],
        );
        $this->templatedb->insert('datafields', $amountdata);
        
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