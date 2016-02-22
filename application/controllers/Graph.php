<?php
class Graph extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Graph_model');
	}

	public function index()
	{
		$data['bills'] = $this->Graph_model->get_graphdata();
        //$data['title'] = 'Graph';
        
        $data['title'] = "View Bills";
	    $data['headline'] = "View Bills";
	    $data['include'] = 'testGraph';
	    $this->load->vars($data);
	    $this->load->view('template');

        //$this->load->view('header', $data);
        //$this->load->view('testGraph',$data);
        //$this->load->view('templates/footer');
	}

	public function viewBill($billID = NULL)
	{
		$data['bills_id'] = $this->Graph_model->get_graphdata($billID);
        
        
        if (empty($data['bills_id']))
        {
            show_404();
        }

         $data['title'] = $data['bills_id']['billID'];
        
        $data['headline'] = "View Bill ".$data['bills_id']['billID'];
	    $data['include'] = 'testGraph_id';
	    $this->load->vars($data);
	    $this->load->view('template');
        
        //$this->load->view('header', $data);
        //$this->load->view('testgraph_id', $data);
        //$this->load->view('templates/footer');
	}
    
    public function updateBill($billID = NULL)
    {
        
        $data['bills_id'] = $this->Graph_model->get_graphdata($billID);
        
        
        if (empty($data['bills_id']))
        {
            show_404();
        }

         $data['title'] = $data['bills_id']['billID'];
        
        $data['headline'] = "Update Bill ".$data['bills_id']['billID'];
	    $data['include'] = 'updateBill_view';
	    $this->load->vars($data);
	    $this->load->view('template');
        
    }
}
?>