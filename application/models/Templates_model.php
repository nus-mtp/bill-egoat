<?php

//@Author James Ho
/*Bill Template System
*/   
class Templates_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
		
		$CI = &get_instance();
        $this->billdb = $CI->load->database('billdb', TRUE); // Load DB to be used
		$this->templatedb = $CI->load->database('templatedb', TRUE);
	}
	
	public function insert_templates_table()
	
	{	
		$data = array(
			'billOrg' => $this->input->post('billingOrgName'),
            'dateCreated' => date("Y-m-d h:m:s",time()),
		);

		$this->templatedb->insert('templates', $data);
		return $id = $this->db->insert_id();
	}
    
    public function get_template_id($billOrgName)        
    {
        $this->templatedb->select('templateID');
        $this->templatedb->where('billOrg', $billOrgName);
        $query = $this->templatedb->get('templates');
        $result = $query->result();
        $template_id = $result[0]->templateID;
        return $template_id;
        
    }
    
    //@Author Tan Tack Poh
    /*OCR processing, including reading the image, retrieving the coordinate data from the database,
      cropping out that part of the image and run OCR on it
      */   
    public function logoRecognition ($id, $template_id)      
    {
        
    }
    
    //@Author Justin Doan
    /*OCR processing, including reading the image, retrieving the coordinate data from the database,
      cropping out that part of the image and run OCR on it
      */   
    public function ocr($id, $template)        
    {
        $amountImgFileDirectory = "images/";
        $dueDateFileDirectory = "images/";
        $amountImgFileName = "cropped1.jpg";
        $dueDateImgFileName = "cropped2.jpg";

        //connect to mysql and getting the coordinate data
        require_once('TesseractOCR.php');

        //$link = mysql_connect('localhost:3306', 'root', 'ysAb7cEkjvOa');
        //mysql_select_db('billdb');
        //$id = 735;
        //$template = 54;

        $this->billdb->select('billFilePath');
        $this->billdb->where('billID', $id);
        $query1 = $this->billdb->get('bills');//$this->billdb->query("SELECT billFilePath from bills where billID = " . $id);
        //$result = mysql_query($sql);
        //$row = print_r($query1->result());//mysql_fetch_array($result, MYSQL_NUM);
        $ini_filename = $query1->result()[0]->billFilePath;
        $im = imagecreatefromjpeg($ini_filename);

        //echo $ini_filename;
        //echo $id;
        //echo $template;

        //mysql_select_db('templatedb');

        $query2 = $this->templatedb->query("SELECT coordinateLabelX, coordinateLabelY, coordinateLabelX2, coordinateLabelY2 FROM datafields WHERE templateID = " . $template ." AND dataFieldLabel = 'amount'");
        //$result = mysql_query($sql);
        $row = $query2->row(0);
        $x1 = $row->coordinateLabelX;
        $y1 = $row->coordinateLabelY;
        $x2 = $row->coordinateLabelX2 - $row->coordinateLabelX;
        $y2 = $row->coordinateLabelY2 - $row->coordinateLabelY;

        //cropping the image using the coordinate data
        $to_crop_array = array('x' => $x1 , 'y' => $y1, 'width' => $x2, 'height'=> $y2);
        $thumb_im = imagecrop($im, $to_crop_array);

        imagejpeg($thumb_im, $amountImgFileDirectory . $amountImgFileName, 100);

        //run OCR on the cropped section
        $tesseract = new TesseractOCR($amountImgFileDirectory . $amountImgFileName);
        $tesseract->setLanguage('eng');
        $amount = $tesseract->recognize();

        $query3 = $this->templatedb->query("SELECT coordinateLabelX, coordinateLabelY, coordinateLabelX2, coordinateLabelY2 FROM datafields WHERE templateID = " . $template ." AND dataFieldLabel = 'duedate'");
        //$result = mysql_query($sql);
        $row = $query3->row(0);
        $x1 = $row->coordinateLabelX;
        $y1 = $row->coordinateLabelY;
        $x2 = $row->coordinateLabelX2 - $row->coordinateLabelX;
        $y2 = $row->coordinateLabelY2 - $row->coordinateLabelY;
        
        //cropping the image using the coordinate data
        $to_crop_array = array('x' => $x1 , 'y' => $y1, 'width' => $x2, 'height'=> $y2);
        $thumb_im = imagecrop($im, $to_crop_array);

        imagejpeg($thumb_im, $dueDateFileDirectory . $dueDateImgFileName, 100);

        //run OCR on the cropped section
        $tesseract = new TesseractOCR($dueDateFileDirectory . $dueDateImgFileName);
        $tesseract->setLanguage('eng');
        $duedate = $tesseract->recognize();

        $amount = strtok($amount, " ");

        $day = strtok($duedate, " ");
        $month = strtok(" ");
        $year = strtok(" ");

        switch ($month) {
            case 'Jan':
                $month = "01";
                break;
            case 'Feb':
                $month = "02";
                break;
            case 'Mar':
                $month = "03";
                break;
            case 'Apr':
                $month = "04";
                break;
            case 'May':
                $month = "05";
                break;
            case 'Jun':
                $month = "06";
                break;
            case 'Jul':
                $month = "07";
                break;
            case 'Aug':
                $month = "08";
                break;
            case 'Sep':
                $month = "09";
                break;
            case 'Oct':
                $month = "10";
                break;
            case 'Nov':
                $month = "11";
                break;
            case 'Dec':
                $month = "12";
                break;
        }

        //echo "<br>" . $amount . "<br>";
        //echo $year;
        //echo $month;
        //echo $day;

        //mysql_select_db('billdb');
        //$query4 = $this->billdb->query("UPDATE bills SET totalAmt = " . $amount . ", billDueDate = '" . $year . "-" . $month . "-" . $day . "' WHERE billID = " . $id);
        
        $data = array(
                    'totalAmt' => $amount,
                    'billDueDate' => $year . "-" . $month . "-" . $day
                );
        $this->billdb->where('billID', $id);
        $this->billdb->update('bills',$data);

        //echo "<br>" . $sql;


        //mysql_close($link);
        return $ini_filename;
    }
    
    
}