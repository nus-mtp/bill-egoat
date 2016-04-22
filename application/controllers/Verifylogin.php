<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
	/* Login verification controller
	** @author James Ho
	** @reviewer Daryl Lim
	*/
	 
	class VerifyLogin extends CI_Controller 
	{
		public function __construct()
		{
			parent::__construct();
			$this->load->model('user','',TRUE);
		}
	 
		public function index()
		{
			//This method will have the credentials validation
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('userEmail', 'Email', 'trim|required');
			$this->form_validation->set_rules('password', 'Password', 'trim|required');
			$this->form_validation->set_message('required', '%s is required. Please enter a value.');
	 
			if($this->form_validation->run() == FALSE)
			{
				//Field validation failed.  User redirected to login page
				$this->load->view('login_view');
			}
			else
			{
				//Go to private area  
				redirect('home', 'refresh');
			}
		}
	 
		public function check_database($password)
		{
			//Field validation succeeded.  Validate against database
			$email = $this->input->post('userEmail');

			//query the database
			$result = $this->user->login($email, $password);
				 
			if($result)
			{
				$sess_array = array();
				
				foreach($result as $row)
				{
					$sess_array = array
					(
						'id' => $row->userID,
						'email' => $row->userEmail
					);
			
					$this->session->set_userdata('logged_in', $sess_array);
				}
			
				return TRUE;
			}
			else
			{
				$this->form_validation->set_message('check_database', 'Invalid username or password');
				return false;
			}
		}
	}
?>