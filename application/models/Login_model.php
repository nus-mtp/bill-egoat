<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// Login system by James Ho
class Login_model extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	
	public function validate(){
		// grab user input
        echo $this->input->post('password');
		$userEmail = $this->security->xss_clean($this->input->post('userEmail'));
		$password = $this->security->xss_clean($this->input->post('password'));
        echo $userEmail;
        echo $password;
		
		// Prep the query
		$this->db->where('userEmail', $userEmail);
		$this->db->where('passwd', $password);
		
		// Run the query
		$query = $this->db->get('users');
		// Let's check if there are any results
		if($query->num_rows() == 1)
		{
			// If there is a user, then create session data
			$row = $query->row();
			$data = array(
					'userID' => $row->userID,
					'userEmail' => $row->userEmail,
                    'isPartnerOrg' => $row->isPartnerOrg,
					'validated' => true
					);
			$this->session->set_userdata($data);
			return true;
		}
		// If the previous process did not validate
		// then return false.
		return false;
	}
}
?>