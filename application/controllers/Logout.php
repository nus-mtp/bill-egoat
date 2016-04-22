<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	/* Controller to logout
	** @author Daryl Lim
	** @reviewer Yunhan
	*/
	class Logout extends CI_Controller{
		
		function __construct()
		{
			parent::__construct();
			
			$this->load->helper('url');
		}
		
		public function index()
		{
			$this->session->sess_destroy();
			
			$data['title'] = "Login";
			$data['headline'] = "";
			$data['msg'] = "";
			$data['include'] = 'login_view';
			$this->load->vars($data);
			
			$this->load->view('template');
		}
	}
?>