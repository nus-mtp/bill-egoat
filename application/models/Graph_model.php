<?php
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
                $query = $this->billdb->get('bills');
                return $query->result_array();
        }

        $query = $this->billdb->get_where('bills', array('billID' => $billID));
        return $query->row_array();
    }
}