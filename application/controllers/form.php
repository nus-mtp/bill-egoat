<?php

/* Controller to perform form validation for signup form
** @author James Ho
** @reviewer Daryl Lim
*/

class Form extends CI_Controller {

	public function index()
	{
		$this->load->helper(array('form', 'url'));

		$this->load->library('form_validation');

		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('myform');
		}
		else
		{
			$this->load->view('signupSuccess');
		}
	}
}
?>