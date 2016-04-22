<?php
	/* Controller to draw charts
	** (Not yet integrated/WIP)
	** @author Qiu Yunhan
	** @reviewer Daryl Lim
	*/
	class Chart extends CI_Controller 
	{
		public function __construct()
		{
			parent::__construct();
			$this->load->model('Data_model');
		}

		public function index()
		{
			$this->load->view('chart');
		}

		public function data()
		{
			$data = $this->data->get_data();

			$category = array();
			$category['name'] = 'billSentDate';

			$series1 = array();
			$series1['name'] = 'totalAmt';
		 
			foreach ($data as $row)
			{
				$category['data'][] = $row->billSentDate;
				$series1['data'][] = $row-->totalAmt;
			}

				$result = array();
				array_push($result,$category);
				array_push($result,$series1);
			  
				print json_encode($result, JSON_NUMERIC_CHECK);
		}
	}
?>