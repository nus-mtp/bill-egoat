<?php
class Data_model extends CI_Model{

	public function __construct()
	{
		parent::__construct();
		
		$CI = &get_instance();
		$this->billdb = $CI->load->database('billdb', TRUE);
	}

	public function get_data($billID = FALSE)
    {
        $this->billdb->select('billSentDate,totalAmt');
        $this->billdb->from('bills');
        $query = $this->billdb->get();
        return $query->result();
    }
}