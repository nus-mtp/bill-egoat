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
    $data['bills_id'] = $this->Graph_model->get_graphdata($billID, 0);
        
        
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
	$this->Templates_model->insert_templates_table();
     
    if (isset($_GET['submissionArray'])){
     $data['templateCoords'] = $_GET['submissionArray'];
    }
    
    $data['bill_data'] = $this->Graph_model->get_graphdata($billID, 0);
    $data['billFilePath'] = $data['bill_data']['billFilePath'];
    $data['templateID'] = $this->Templates_model->get_template_ID($data['bill_data']['billOrg']);
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
    
    if (isset($_GET['submissionArray'])&&isset($_GET['templateID'])&&isset($_GET['billFilePath'])){
        $data['templateCoords'] = $_GET['submissionArray'];
        $this->Templates_model->insert_datafields_table($_GET['submissionArray'],$_GET['templateID']);
        $this->Templates_model->insert_logodb_logo($_GET['billFilePath'], $_GET['templateID']);
        echo implode(" ", $data['templateCoords'][0])."/n".implode(" ", $data['templateCoords'][1])."/n".implode(" ", $data['templateCoords'][2]);
        echo "Coordinates saved.";  
    }
    else {
        echo "Coordinates failed to save.";
    }
   //echo implode(" ", $data['templateCoords'][0])."/n".implode(" ", $data['templateCoords'][1])."/n".implode(" ", $data['templateCoords'][2]);
    
    
}
    
public function logoMatching()
{
    
    // Load the model
    $this->load->model('Templates_model','',TRUE);
    
    $data['headline'] = "Logo Matching";
    $data['include'] = 'logomatching';
    $this->load->vars($data);
    $this->load->view('template');
    
}
    
public function updateBill()
{
    $this->load->model('Maddbill_model','',TRUE);
    $this->Maddbill_model->update_bills_table($this->input->post('billID'));
    redirect('graph','refresh');
}
}
?>