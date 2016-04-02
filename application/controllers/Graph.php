<?php
/* Controller for getting and displaying bill data
** @author Qiu Yunhan
** @reviewer Daryl Lim
*/
class Graph extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Graph_model');
		$this->load->model('Maddbill_model');
		
		// Check if username exists in session
		if ($this->session->userdata('userID') === NULL)
		{
			// User is not logged in, redirect to login screen
			$data['msg'] = "Please log in to access page";
			redirect('login', $data);
		}
	}

	public function index()
	{
		// Retrieve all billing data for user
		$data['bills'] = $this->Graph_model->get_graphdata();
		
		// Retrieve all billing data, grouped by billOrgs
		$data['billOrgs'] = $this->Graph_model->get_billOrgs();
		
		// Retrieve all monthly data for each billOrg
		$billOrgByMths = array();

		foreach ( $data['billOrgs'] as $billOrg)
		{
			array_push($billOrgByMths, $this->Graph_model->get_billOrgMonths($billOrg['billOrg']));  
		}
		
		$data['billOrgMths'] = $billOrgByMths;
		$data['title'] = "Bill Overview";
		$data['headline'] = "Bill Overview";
		$data['include'] = 'testGraph';
		$data['billOrgsJSON'] = json_encode($data['billOrgs']);
		$data['billOrgMthsJSON'] = json_encode($data['billOrgMths']);
        
		$this->load->vars($data);
		$this->load->view('template');
	}

	/* Shows view to update bills
	** @author Qiu Yunhan, modified by Daryl Lim
	** @Parameter: billID to be updated
	*/
	public function viewBill($billID = NULL)
	{
		//Retrieves bill data from db
		$data['bills_id'] = $this->Graph_model->get_graphdata($billID);

		// Retrieve tags from DB
		$data['tags'] = $this->Maddbill_model->get_tags($billID);

		if (empty($data['bills_id']))
		{
			show_404();
		}
		
		$data['title'] = "View Bill ".$data['bills_id']['billID'];
		$data['headline'] = "";
		$data['include'] = 'viewBill_view';
		$this->load->vars($data);
		$this->load->view('template');
	}
    
	/* Shows view to update bills
	** @author Qiu Yunhan, modified by Daryl Lim
	** @Parameter: billID to be updated
	*/
	public function updateBill($billID = NULL)
	{
		$data['bills_id'] = $this->Graph_model->get_graphdata($billID);
		
		// Retrieve tags from DB
		$data['tags'] = $this->Maddbill_model->get_tags($billID);
		
		if (empty($data['bills_id']))
		{
			show_404();
		}

		$data['title'] = "Update Bill ".$data['bills_id']['billID'];
        
		$data['headline'] = "";
		$data['include'] = 'updateBill_view';
		$this->load->vars($data);
		$this->load->view('template'); 
	}
}
?>
