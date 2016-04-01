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
		$this->load->view('welcome_message');
	}
}
?>