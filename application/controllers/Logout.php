<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Controller to logout
** @author Daryl Lim
*/
class Logout extends CI_Controller{
	
	function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$this->session->sess_destroy();
		
		$data['title'] = "";
		$data['headline'] = "";
		$data['include'] = 'welcome_message';
		$this->load->vars($data);
		
		$this->load->view('template');
	}
}
?>