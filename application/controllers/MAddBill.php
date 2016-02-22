<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MAddBill extends CI_Controller {

 public function index()
 {
      $data['title'] = "Manually Add Bill";
	  $data['headline'] = "Add a Bill Manually";
	  $data['include'] = 'maddbill_view';
	  $this->load->vars($data);
	  $this->load->view('template');
 }
 
 public function addManualBill()
 {
    // Load the model
    $this->load->model('Maddbill_model','',TRUE);
	$imageName = $this->Maddbill_model->upload();
	$this->Maddbill_model->insert_bills_table($imageName);
	//$this->Maddbill_model->insert_bill_amts_table();
	//$this->Maddbill_model->insert_bill_tags_table();
	
   
	redirect('graph','refresh');
}
    
public function updateBill()
{
    $this->load->model('Maddbill_model','',TRUE);
    $this->Maddbill_model->update_bills_table($this->input->post('billID'));
    redirect('graph','refresh');
}

}

?>