<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* Controller to manually add/update bills
** @author Daryl Lim
*/
class MAddBill extends CI_Controller {

	// Load pages
	public function index()
	{
		$data['title'] = "Manually Add Bill";
		$data['headline'] = "Add a Bill Manually";
		$data['include'] = 'maddbill_view';
		$this->load->vars($data);
		
		// Unit testing: Change to 'TRUE' to run
		$this->load->library('unit_test');
		$this->unit->active(FALSE);
		
		$this->load->view('template');
	 }
	 
	/* Function to manually add bills
	** @author Daryl Lim
	*/
	 public function addManualBill()
	 {
		// Load the model
		$this->load->model('Maddbill_model','',TRUE);
		
		// Upload file prompt and actual upload
		$imageName = $this->Maddbill_model->upload();
		
		// Post other text fields to DB
		$this->Maddbill_model->insert_bills_table($imageName);
		
		//Inputs to be added
		//$this->Maddbill_model->insert_bill_amts_table();
		//$this->Maddbill_model->insert_bill_tags_table();
	   
	   // Load bill summary page
		redirect('graph','refresh');
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
		
		// Load bill summary page
		redirect('graph','refresh');
	}
}

?>