<?php
	/* Model for signup
	** @author James Ho
	** @reviewer Daryl Lim
	*/
	class signup_model extends CI_Model 
	{
		public function __construct()
		{
				$this->load->database();
		}
		
		public function insert_users_table()
		{
			$hash=md5(rand(0,1000));
			
			$data = array
			(
				'userEmail' => $this->input->post('userEmail'),
				'passwd' => $this->input->post('userPassword'),
				'isPartnerOrg' => FALSE,
				'failedLoginNo' => 0,
				'isActivated' => FALSE,
				'activationCode' => $hash
			);
			
			return $this->db->insert('users', $data);
		}
		
		public function insert_emails_table()
		{
			$query = $this->db->get_where('users', array('userEmail' => $this->input->post('userEmail')));
			$row = $query->row();
			$data2 = array(
				'userID' => $row->userID, 
				'userEmail' => $this->input->post('userEmail'),
				'isReminderEmail' => TRUE,
				'isRecoveryEmail' => TRUE
			);
			
			return $this->db->insert('emails', $data2);
		}
		
		public function insert_userprefs_table()
		{
			
			$query = $this->db->get_where('users', array('userEmail' => $this->input->post('userEmail')));
			$row = $query->row();
			$data3 = array
			(
				'userID' => $row->userID, 
				'realName' => $this->input->post('lastName') . "', '" .  $this->input->post('firstName')
			);
			
			return $this->db->insert('userprefs', $data3);
		}
	}
?>