<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 class Home extends CI_Controller{
	function __construct(){
		parent::__construct();
		$this->check_isvalidated();
	}
	
	public function index(){
		$data['title'] = "Dashboard";
	    $data['headline'] = "Dashboard";
	    $data['include'] = 'home_view';
	    $this->load->vars($data);
	    $this->load->view('template');
	}
	
	private function check_isvalidated(){
		if(! $this->session->userdata('validated')){
			redirect('login');
		}
	}
	
	public function do_logout(){
		$this->session->sess_destroy();
		redirect('login');
	}
 }
 ?>