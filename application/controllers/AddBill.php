<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* Controller to automatically add/process bills
** @author Daryl Lim
*/
class AddBill extends CI_Controller {

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
        $this->load->model('Templates_model');
	}

	// Load pages
	public function index()
	{
		$data['title'] = "Upload Bill";
		$data['headline'] = "";
		$data['include'] = 'addbill_view';
		$this->load->vars($data);
		
		// Unit testing: Change to 'TRUE' to run
		$this->load->library('unit_test');
		$this->unit->active(FALSE);
		
		$this->load->view('template');
	 }
	 
	/* Function to add and automatically process bills, called by form
	** @author Daryl Lim, James Ho and (for logo recognition) Tan Tack Poh
	*/
	public function addBill()
	{	
		// Form Validation
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		// Attempt upload of image
		$imgName = $this->Maddbill_model->upload();
		
		// Validate Billing organisation
		$this->form_validation->set_rules('billOrg', 'Billing Organisation', 'alpha_numeric');
		
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
			$data['title'] = "Upload Bill";
			$data['headline'] = "";
			$data['include'] = 'addbill_view';
			$this->load->vars($data);
			$this->load->view('template');
		}
		else
		{
			// Post other text fields to DB
			$billID = $this->Maddbill_model->insert_bills($imgName);
			
			// Retrieve bill info
			$data['bills_id'] = $this->Graph_model->get_graphdata($billID, 0);
			$data['tags'] = $this->Maddbill_model->get_tags($billID);
            
            // Retrieve template info
            $templateID = $this->Templates_model->get_template_id($this->input->post('billOrg'));

            // Run logo recognition to retrieve the best matched billing organisation name
            $data['templateID'] = $this->Templates_model->logoRecognition($billID,$templateID);

            // Take billID and templateID, pass into and run ocr function in background
            $data['filename'] = $this->Templates_model->ocr($billID,$templateID);
            
			// Retrieve updated bill info
			$data['bills_id'] = $this->Graph_model->get_graphdata($billID, 0);
			$data['tags'] = $this->Maddbill_model->get_tags($billID);
            
			// Redirect to verification/update bill
			$data['title'] = "Update/Verify Bill";
			$data['headline'] = "";
			$data['include'] = 'updateBill_view';
			$this->load->vars($data);
			$this->load->view('template');
		}
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
	
}
?>