<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* Controller to manually add/update bills
** @author Daryl Lim
*/
class MAddBill extends CI_Controller {

	// Load pages
	public function index()
	{
		$data['title'] = "Manually Add Bill";
		$data['headline'] = "Add a Bill";
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
		// Load the model
		$this->load->model('Maddbill_model','',TRUE);
	   	
		// Form Validation
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		// Validation Rules
		$this->form_validation->set_rules('totalAmt', 'Total Amount', 'decimal');
		$this->form_validation->set_rules('billSentDate','Bill Sent Date','callback_regex_check');
		$this->form_validation->set_rules('billDueDate','Bill Due Date','callback_regex_check');
		$this->form_validation->set_rules('dateCompleted','Date Bill Completed','callback_regex_check');
		
		// Routing after validation
		if ($this->form_validation->run() == FALSE)
		{
			$data['title'] = "Manually Add Bill";
			$data['headline'] = "Add a Bill";
			$data['include'] = 'maddbill_view';
			$this->load->vars($data);
			$this->load->view('template');
		}
		else
		{
			// Upload file prompt and actual upload
			$imgName = $this->Maddbill_model->upload();
		
			// Post other text fields to DB
			$this->Maddbill_model->insert_bills($imgName);
			echo "<h1>Successfully added bill!</h1>";
			redirect('graph','refresh');
		}
	}
	
	/* Function to manually update bills
	** @author Daryl Lim
	*/
	public function updateBill()
	{
		// Load the model
		$this->load->model('Maddbill_model','',TRUE);
		
		// Upload replacement file
		$imageName = $this->Maddbill_model->upload();
		
		// Post fields to DB
		$this->Maddbill_model->update_bills_table($this->input->post('billID'), $imageName);
		
		// Validation Rules
		$this->form_validation->set_rules('totalAmt', 'Total Amount', 'decimal');
		$this->form_validation->set_rules('billSentDate','Bill Sent Date','callback_regex_check');
		$this->form_validation->set_rules('billDueDate','Bill Due Date','callback_regex_check');
		$this->form_validation->set_rules('dateCompleted','Date Bill Completed','callback_regex_check');
		
		// Routing after validation
		if ($this->form_validation->run() == FALSE)
		{
			$data['title'] = "Update Bill";
			$data['headline'] = "Update Existing Bill";
			$data['include'] = 'updateBill_view';
			$this->load->vars($data);
			$this->load->view('template');
		}
		else
		{
			echo "<h1>Successfully updated bill!</h1>";
			redirect('graph','refresh');
		}
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
}
?>