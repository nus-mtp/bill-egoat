<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Login system by James Ho
 * Description: Login controller class
 */
class Login extends CI_Controller{
	
	function __construct(){
		parent::__construct();
	}
	
	public function index($msg = NULL){
		// Load our view to be displayed
		// to the user
		$data['msg'] = $msg;
		$this->load->view('login_view', $data);
	}
    
	public function process(){
		// Load the model
		$this->load->model('login_model');
		// Validate the user can login
		$result = $this->login_model->validate();
		// Now we verify the result
		if($result > 0){
            if($result==1)
            {
                // If user did not validate, then show them login page again
                $msg = '<font color=red>Invalid username and/or password.</font><br />';
                $this->index($msg);
            }
            if($result==2)
            {
                // If user failed login 3 times, make them wait 5 minutes
                $msg = '<font color=red>You have input the wrong password three times. Please wait five minutes before trying again.</font><br />';
                $this->index($msg);
            }
		}else{
			// If user did validate, 
			// Send them to members area
			redirect('Home');
		}		
	}
}
?>