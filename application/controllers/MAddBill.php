<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* Controller to manually add/update bills
** @author Daryl Lim
*/
class MAddBill extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
		// Check if username exists in session
		if ($this->session->userdata('userID') === NULL)
		{
			// User is not logged in, redirect to login screen
			$data['msg'] = "Please log in to access page";
			redirect('login', $data);
		}
		
		$this->load->model('Graph_model');
		$this->load->model('Maddbill_model');
	}

	// Load pages
	public function index()
	{
		$data['title'] = "Manually Add Bill";
		$data['headline'] = "";
		$data['include'] = 'maddbill_view';
		$this->load->vars($data);
		
		// Unit testing: Change to 'TRUE' to run
		$this->load->library('unit_test');
		$this->unit->active(FALSE);
		
		$this->load->view('template');
	 }
	 
	/* Function to manually add bills, called by form
	** @author Daryl Lim
	*/
	public function addManualBill()
	{	
		// Form Validation
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		// Validation Rules
		$this->form_validation->set_rules('totalAmt', 'Total Amount', 'decimal');
		$this->form_validation->set_rules('billSentDate','Bill Sent Date','callback_regex_check');
		$this->form_validation->set_rules('billDueDate','Bill Due Date','callback_regex_check');
		$this->form_validation->set_rules('dateCompleted','Date Bill Completed','callback_regex_check');
		
		// Attempt upload of image
		$imgName = $this->Maddbill_model->upload();
		
		// Validate image extension
		if ($this->chk_ext($imgName) == FALSE)
		{
			$this->form_validation->set_rules('image','Bill Image','callback_ext');
		}
		
		// Validate image size
		if ($this->chk_size($imgName) == FALSE)
		{
			$this->form_validation->set_rules('image','Bill Image','callback_fsize');
		}
		
		// Routing after failing validation
		if ($this->form_validation->run() == FALSE)
		{
			$data['title'] = "Manually Add Bill";
			$data['headline'] = "";
			$data['include'] = 'maddbill_view';
			$this->load->vars($data);
			$this->load->view('template');
		}
		else
		{
			// Post other text fields to DB
			$billID = $this->Maddbill_model->insert_bills($imgName);
			
			// Retrieve bill info
			$data['bills_id'] = $this->Graph_model->get_graphdata($billID);
			$data['tags'] = $this->Maddbill_model->get_tags($billID);
			
			// Redirect to verification/update bill
			$data['title'] = "Update/Verify Bill";
			$data['headline'] = "";
			$data['include'] = 'updateBill_view';
			$this->load->vars($data);
			$this->load->view('template');
		}
	}
	
	/* Function to manually update bills
	** @author Daryl Lim
	*/
	public function updateBill()
	{
		$data['bills_id'] = $this->Graph_model->get_graphdata($this->input->post('billID'));
		
		// Form Validation
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		// Attempt upload of image
		$imgName = $this->Maddbill_model->upload();
		
		// Validate image extension
		if ($this->chk_ext($imgName) == FALSE)
		{
			$this->form_validation->set_rules('image','Bill Image','callback_ext');
		}
		
		// Validate image size
		if ($this->chk_size($imgName) == FALSE)
		{
			$this->form_validation->set_rules('image','Bill Image','callback_fsize');
		}
		
		// Validation Rules
		$this->form_validation->set_rules('totalAmt', 'Total Amount', 'decimal');
		$this->form_validation->set_rules('billSentDate','Bill Sent Date','callback_regex_check');
		$this->form_validation->set_rules('billDueDate','Bill Due Date','callback_regex_check');
		$this->form_validation->set_rules('dateCompleted','Date Bill Completed','callback_regex_check');
		
		// Routing after validation
		if ($this->form_validation->run() == FALSE)
		{
			$data['title'] = "Update Bill";
			$data['headline'] = "";
			$data['include'] = 'updateBill_view';
			$this->load->vars($data);
			$this->load->view('template');
		}
		else
		{
			// Post fields to DB and go to bill view
			$this->Maddbill_model->update_bills_table($this->input->post('billID'), $imgName);
			$data['title'] = "View Bill ".$data['bills_id']['billID'];
			$data['headline'] = "";
			$data['include'] = 'viewBill_view';
			$this->load->vars($data);
			$this->load->view('template');
		}
	}
	
	/* Function to manually delete bills
	** @author Daryl Lim
	*/
	public function deleteBill()
	{
		$this->Maddbill_model->delete_bills_table($this->input->post('billID'));
		redirect('graph','refresh');
	}
	
	// ==================================== Helper functions
	
	/* Helper function to check valid file extension
	** @author Daryl Lim
	** @Output boolean value if valid/not
	*/
	public function chk_ext($imgName)
	{
		if ($imgName == "EXT_ERR")
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	/* Helper function to check valid file size
	** @author Daryl Lim
	** @Output boolean value if valid/not
	*/
	public function chk_size($imgName)
	{
		if ($imgName == "SIZE_ERR")
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	// ==================================== Validation callback functions
	
	/* Helper callback function to set error message for wrong extension
	** @author Daryl Lim
	*/
	public function ext()
	{
		$this->form_validation->set_message('ext', 'Please upload only accepted image formats (jpeg, png, bmp, gif, pdf, tiff)');
		return FALSE;
	}
	
	/* Helper callback function to set error message for file size
	** @author Daryl Lim
	*/
	public function fsize()
	{
		$this->form_validation->set_message('fsize', 'File size must be less than 5 MB');
		return FALSE;
	}
	
	/* Helper callback function to do regex validation for dates YYYY-MM-DD
	** @author Daryl Lim
	** @Parameter string input by user to be checked
	** @Output boolean true if input is null or valid date
	*/
	public function regex_check($str)
	{
		if (1 !== preg_match("/^(19|20)\d\d[\-\/.](0[1-9]|1[012])[\-\/.](0[1-9]|[12][0-9]|3[01])$/", $str))
		{
			// Accept empty strings
			if ($str == NULL)
			{
				return TRUE;
			}
			else
			{
				$this->form_validation->set_message('regex_check', 'The date must be in YYYY-MM-DD format.');
				return FALSE;
			}
		}
		else
		{
			return TRUE;
		}
	}
	
	/* Helper callback function to do regex validation for money
	** @author Daryl Lim
	** @Parameter string input by user to be checked
	** @Output boolean true if input is null or valid money amount
	*/
	public function money_check($str)
	{
		// First check if decimal
		if(parent::decimal($str))
		{
			return TRUE;
		}
		else // Check if integer
		{
			if (parent::integer($str))
			{
				return TRUE;
			}
			else
			{
				$this->form_validation->set_message('money_check', 'Value must be in integer or decimal form.');
				return FALSE;
			}
		}
	}
}
?>