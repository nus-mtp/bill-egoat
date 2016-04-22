<?php
	/* Controller for searching bills
	** @author Daryl Lim
	** @reviewer Justin
	*/
	class Search extends CI_Controller
	{
		public function __construct()
		{
			parent::__construct();
			$this->load->model('Search_model');
			
			// Check if username exists in session
			if ($this->session->userdata('userID') === NULL)
			{
				// User is not logged in, redirect to login screen
				$data['msg'] = "Please log in to access page";
				redirect('login', $data);
			}
		}

		/* Main search page
		** @author Daryl Lim
		*/
		public function index()
		{		
			$data['title'] = "Search Results";
			$data['headline'] = "";
			$data['include'] = 'search_view';
			
			$this->load->vars($data);
			$this->load->view('template');
		}
		
		public function searchBill()
		{
			$results = $this->Search_model->search_bills();
			$data['results'] = $results;
			
			$data['title'] = "Search Results";
			$data['headline'] = "";
			$data['include'] = 'search_view';
			
			$this->load->vars($data);
			$this->load->view('template');
		}
	}
?>
