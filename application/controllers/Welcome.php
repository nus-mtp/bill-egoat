<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/* Index page controller
** @author James Ho
** @reviewer Daryl Lim
*/

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->view('welcome_message');
	}

	function signup(){
		if ($this->input->post('userEmail')){
			$this->load->model('signup_model','',TRUE);
			$this->signup_model->insert_users_table();
			$this->signup_model->insert_emails_table();
			$this->signup_model->insert_userprefs_table();
			redirect('welcome/thankyou','refresh');
		}
		else{
			redirect('refresh');
		}
	}

	function thankyou(){
	  $data['title'] = "Thank You!";
	  $data['headline'] = "Thanks!";
	  $data['include'] = 'signupthanks';
	  $this->load->vars($data);
	  $this->load->view('template');
	}
}
