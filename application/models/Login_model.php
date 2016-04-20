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
        
        // Check user has not failed login multiple times already
        $this->db->where('userEmail', $userEmail);
        $query = $this->db->get('users');
        $currentFailedLoginNo = 0;
        if($query->num_rows() > 0)
		{
			// If there is a user, retrieve failedLoginNo
			$loginFailedRow = $query->row();
			$currentFailedLoginNo = $loginFailedRow->failedLoginNo;
			
		}
        
        if($currentFailedLoginNo >= 3)
        {
            // if the session data for last login time not set, set it here
            if(!isset($_SESSION['last_login_time']))
            {
                $_SESSION['last_login_time'] = time();
            }
            if(time() - $_SESSION['last_login_time'] < 10*60*60 ) 
            {
                // alert to user wait for 10 minutes
                return 2; 
            }
            else
            {
                $resetFailedLoginNo = array(
                   'failedLoginNo' => 0
                );
                $this->db->where('userEmail', $userEmail);
                $this->db->update('users', $resetFailedLoginNo);
            }
        }
        
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
			return 0;
		}
		// If the previous process did not validate
		// then return check loginFailedNo of the userEmail. If user exists and loginFailedNo above certain number, temp lock login for user.
        $currentFailedLoginNo = $currentFailedLoginNo + 1;
        $increaseFailedLoginNo = array(
                   'failedLoginNo' => $currentFailedLoginNo
                );
        $this->db->where('userEmail', $userEmail);
        $this->db->update('users', $increaseFailedLoginNo);
        
        // If wrong pass for the 3rd time, start the lock out timer
        if($currentFailedLoginNo >= 3)
        {
            $_SESSION['last_login_time'] = time();
        }

		return 1;
	}
}
?>