<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MAddBill extends CI_Controller {

 public function index()
 {
      $data['title'] = "Manual Add Bill Success";
	  $data['headline'] = "Manual Add Bill Success";
	  $data['include'] = 'maddbill_view';
	  $this->load->vars($data);
	  $this->load->view('template');
 }
    
 public function addManualBill()
 {
    // Load the model
    $this->load->model('Maddbill_model','',TRUE);
	$this->Maddbill_model->insert_bills_table();
	$this->Maddbill_model->insert_bill_amts_table();
	$this->Maddbill_model->insert_bill_tags_table();
	redirect('welcome/thankyou','refresh');
}
}

?>