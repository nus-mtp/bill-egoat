<?php
/* Controller for getting and displaying bill data
** @author Qiu Yunhan, Daryl Lim
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

	/* Shows view for all bills (Bill Overview)
	** @author Qiu Yunhan, Daryl Lim
	*/
	public function index($select = 0)
	{
		// Retrieve all billing data for user
		$data['bills'] = $this->Graph_model->get_graphdata(FALSE, $select);
		
		// Retrieve all billing data, grouped by billOrgs
		$data['billOrgs'] = $this->Graph_model->get_billOrgs($select);
		
		// Retrieve all monthly data for each billOrg
		$billOrgByMths = array();

		foreach ( $data['billOrgs'] as $billOrg)
		{
			array_push($billOrgByMths, $this->Graph_model->get_billOrgMonths($billOrg['billOrg'], $select));  
		}
			
		// Retrieve years of bills
		$data['billYears'] = $this->Graph_model->get_billYears($select);
		
		// Retrieve months per year of bills
		$billMonths = array();
		$billMthBillOrg = array();
		
		// Retrieve years overall
		$billYearsAT = $this->Graph_model->get_Years($select);
		
		foreach ( $data['billYears'] as $row)
		{
			array_push($billMonths, $this->Graph_model->get_Months($row['year'], $select));
			array_push($billMthBillOrg, $this->Graph_model->get_MthlyBillOrg($row['year'], $select));
		}
		
		$data['billMthBillOrg'] = $billMthBillOrg;
		$data['billYearsAT'] = $billYearsAT;
		$data['billMonths'] = $billMonths;
		
		$data['billOrgMths'] = $billOrgByMths;
		
		if ($select == 0)
		{
			$data['title'] = "Bill Overview";
		}
		else if ($select == 1)
		{
			$data['title'] = "Unpaid Bills";
		}
		else
		{
			$data['title'] = "Recent Bills";
		}
		$data['headline'] = "";
		$data['include'] = 'testGraph';
        
		$this->load->vars($data);
		$this->load->view('template');
	}
	
	/* Shows view for unpaid/recent bills
	** @author Qiu Yunhan, Daryl Lim
	** @parameter TRUE for unpaid bill view, FALSE for recent bill view
	*/
	public function special($select)
	{
		// Retrieve all billing data for user
		$data['bills'] = $this->Graph_model->get_graphdata(FALSE, $select);
		
		// Retrieve all billing data, grouped by billOrgs
		$data['billOrgs'] = $this->Graph_model->get_billOrgs($select);
		
		// Retrieve all monthly data for each billOrg
		$billOrgByMths = array();

		foreach ( $data['billOrgs'] as $billOrg)
		{
			array_push($billOrgByMths, $this->Graph_model->get_billOrgMonths($billOrg['billOrg']));  
		}
			
		// Retrieve years of bills
		$data['billYears'] = $this->Graph_model->get_billYears();
		
		// Retrieve months per year of bills
		$billMonths = array();
		$billMthBillOrg = array();
		
		// Retrieve years overall
		$billYearsAT = $this->Graph_model->get_Years();
		
		foreach ( $data['billYears'] as $row)
		{
			array_push($billMonths, $this->Graph_model->get_Months($row['year']));
			array_push($billMthBillOrg, $this->Graph_model->get_MthlyBillOrg($row['year']));
		}
		
		$data['billMthBillOrg'] = $billMthBillOrg;
		$data['billYearsAT'] = $billYearsAT;
		$data['billMonths'] = $billMonths;
		
		$data['billOrgMths'] = $billOrgByMths;
		$data['title'] = "Bill Overview";
		$data['headline'] = "";
		$data['include'] = 'testGraph';
        
		$this->load->vars($data);
		$this->load->view('template');
	}

	/* Shows view to view bills
	** @author Qiu Yunhan, modified by Daryl Lim
	** @Parameter: billID to be updated
	*/
	public function viewBill($billID = NULL)
	{
		//Retrieves bill data from db
		$data['bills_id'] = $this->Graph_model->get_graphdata($billID, 0);

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
		$data['bills_id'] = $this->Graph_model->get_graphdata($billID, 0);
		
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
