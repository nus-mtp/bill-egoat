<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// Templates controller written by James
// For the creation of the templates by user
class Templates extends CI_Controller {
    
public function __construct()
{
    parent::__construct();
    $this->load->model('Graph_model');
    $this->load->model('Templates_model');
}

 public function index()
 {
      $data['title'] = "Create Bill Template";
	  $data['headline'] = "Create Bill Template";
	  $data['include'] = 'createTemplate_billorg';
	  $this->load->vars($data);
	  $this->load->view('template');
 }
    
 public function addTemplateFromBill($billID = NULL)
 {
    $data['bills_id'] = $this->Graph_model->get_graphdata($billID);
        
        
        if (empty($data['bills_id']))
        {
            show_404();
        }

         $data['title'] = $data['bills_id']['billID'];
     
    $data['headline'] = "Create Template from Bill ".$data['bills_id']['billID'];
    $data['include'] = 'createTemplate_billorg';
    $this->load->vars($data);
    $this->load->view('template');

     
 }
     
 public function addTemplate($billID = NULL)
 {
    // Load the model
    $this->load->model('Templates_model','',TRUE);
	$data['templateID'] = $this->Templates_model->insert_templates_table();
     
    if (isset($_POST['submissionArray'])){
     $data['templateCoords'] = $_POST['submissionArray'];
    }
    
    $data['bill_data'] = $this->Graph_model->get_graphdata($billID);
    $data['billFilePath'] = $data['bill_data']['billFilePath'];
    $data['title'] = "Create Template from Bill ".$data['bill_data']['billID'];
    $data['headline'] = "Create Template from Bill ".$data['bill_data']['billID'];
	$data['include'] = 'drawtemplate';
    $this->load->vars($data);
    $this->load->view('template');
}
    
public function saveTemplateCoords()
{
    // Load the model
    $this->load->model('Templates_model','',TRUE);
    
    if (isset($_POST['submissionArray'])){
     $data['templateCoords'] = $_POST['submissionArray'];
        $this->Templates_model->insert_datafields_table($_POST['submissionArray']);
    }
    
   //echo implode(" ", $data['templateCoords'][0])."/n".implode(" ", $data['templateCoords'][1])."/n".implode(" ", $data['templateCoords'][2]);
    echo "Coordinates saved.";
    
}
    
public function updateBill()
{
    $this->load->model('Maddbill_model','',TRUE);
    $this->Maddbill_model->update_bills_table($this->input->post('billID'));
    redirect('graph','refresh');
}

}

?>