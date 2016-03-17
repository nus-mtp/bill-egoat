<!--?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');</p
/* Controller to draw charts
** (Not yet integrated/WIP)
** @author Qiu Yunhan
*/
-->

class Chart extends CI_Controller {

public function __construct()
{
parent::__construct();
$this->load->model('data');
}

public function index()
{
$this->load->view('chart');
}

public function data()
{

$data = $this->data->get_data();

$category = array();
$category['name'] = 'Category';

$series1 = array();
$series1['name'] = 'WordPress';

$series2 = array();
$series2['name'] = 'Code Igniter';

$series3 = array();
$series3['name'] = 'Highcharts';

foreach ($data as $row)
{
$category['data'][] = $row->month;
$series1['data'][] = $row-->wordpress;
$series2['data'][] = $row->codeigniter;
$series3['data'][] = $row-->highcharts;
}

$result = array();
array_push($result,$category);
array_push($result,$series1);
array_push($result,$series2);
array_push($result,$series3);

print json_encode($result, JSON_NUMERIC_CHECK);
}

}

/* End of file chart.php */
/* Location: ./application/controllers/chart.php */
