<?php

//@Author James Ho, refactored by Tan Tack Poh

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
	
    // @Author James Ho
	// @Reviewer Daryl Lim, Tack Poh
    // Write into SQL table the relationship between Template ID and organisation name (table is templatedb -> templates)
	public function insert_templates_table()
	{	
		$data = array
		(
			'billOrg' => $this->input->post('billingOrgName'),
            'dateCreated' => date("Y-m-d h:m:s",time()),
		);

		$this->templatedb->insert('templates', $data);
		return $id = $this->db->insert_id();
	}

    // @Author James Ho
	// @Reviewer Daryl Lim, Tack Poh
    // Write into SQL table the coordinates data of parts of template (table is templatedb -> datafields)
    public function insert_datafields_table($templateCoords,$templateID)
    {
        $dataLogo = array
		(
            'templateID' => $templateID,
            'dataFieldLabel' => "logo",
            'coordinateLabelX' => $templateCoords[0][0],
            'coordinateLabelY' => $templateCoords[0][1],
            'coordinateLabelX2' => $templateCoords[0][2],
            'coordinateLabelY2' => $templateCoords[0][3],
        );

        $dataDueDate = array
		(
            'templateID' => $templateID,
            'dataFieldLabel' => "duedate",
            'coordinateLabelX' => $templateCoords[1][0],
            'coordinateLabelY' => $templateCoords[1][1],
            'coordinateLabelX2' => $templateCoords[1][2],
            'coordinateLabelY2' => $templateCoords[1][3],
        );

        $dataAmount = array
		(
            'templateID' => $templateID,
            'dataFieldLabel' => "amount",
            'coordinateLabelX' => $templateCoords[2][0],
            'coordinateLabelY' => $templateCoords[2][1],
            'coordinateLabelX2' => $templateCoords[2][2],
            'coordinateLabelY2' => $templateCoords[2][3],
        );

        $this->templatedb->insert('datafields', $dataLogo);
        $this->templatedb->insert('datafields', $dataDueDate);
        $this->templatedb->insert('datafields', $dataAmount);
    }

    // @Author Tan Tack Poh
	// @Reviewer Daryl Lim
    // Create a logo for the database for a first time template creation for an organisation
    public function insert_logodb_logo($billFilePath, $templateID)
    {
		$im = imagecreatefromjpeg($billFilePath);
		$LogoDBFilesDirectory = "images/logos_DB/";
		list($width, $height) = getimagesize($billFilePath);

		$query = $this->templatedb->query("SELECT coordinateLabelX, coordinateLabelY, coordinateLabelX2, coordinateLabelY2 FROM datafields WHERE templateID = '" . $templateID ."' AND dataFieldLabel = 'logo'");
		$row = $query->row(0);
		$x1 = $row->coordinateLabelX;
		$y1 = $row->coordinateLabelY;
		$x2 = $row->coordinateLabelX2 - $row->coordinateLabelX;
		$y2 = $row->coordinateLabelY2 - $row->coordinateLabelY;

		$x1 = $x1 * $width;
		$y1 = $y1 * $height;
		$x2 = $x2 * $width;
		$y2 = $y2 * $height;
		
		//cropping the image using the coordinate data
		$to_crop_array = array('x' => $x1 , 'y' => $y1, 'width' => $x2, 'height'=> $y2);
		$thumb_im = imagecrop($im, $to_crop_array);

		imagejpeg($thumb_im, $LogoDBFilesDirectory . $templateID . '.jpg', 100);

		$dataLogoFilePath = array
		(
			'templateID' => $templateID,
			'logodbImgPath' => $LogoDBFilesDirectory . $templateID . '.jpg',
		);
		
		$this->templatedb->insert('logo', $dataLogoFilePath);
	}
    
    // @Author James Ho
	// @reviewer Daryl Lim
    // Obtain Template ID
    public function get_template_id($billOrgName)        
    {
        $this->templatedb->select('templateID');
        $this->templatedb->where('billOrg', $billOrgName);
        $query = $this->templatedb->get('templates');
        $result = $query->result();
		if (empty($result))
		{
			$template_id = 0;
		}
		else
		{
			$template_id = $result[0]->templateID;
		}
        return $template_id;
        
    }
    
    //@Author and refactored by Tan Tack Poh
    /*Use Template ID to obtain already existing template details, then perform logo recognition
    */   
    public function logoRecognition ($billID, $templateID)      
    {
        // Bill Image
        $this->billdb->select('billFilePath');
        $this->billdb->where('billID', $billID);
        $query1 = $this->billdb->get('bills');//$this->billdb->query("SELECT billFilePath from bills where billID = " . $billID);
        $ini_filename = $query1->result()[0]->billFilePath;
        $im = imagecreatefromjpeg($ini_filename);

        list($width, $height) = getimagesize($ini_filename);

        // Query Logo
        $detectionInputDirectory = "images/detection_result/"; // where your input will be placed for the py file logo detection to find and process your image
        $detectionInputImgName = "queryImage.jpg"; // Hardcoded query input as OpenCV doesn't handle arguments

        // Training Logo Directory
        $LogoDBFilesDirectory = "images/logos_DB/";
        $detectionInputTrainingImgName = "trainingImage.jpg"; // Hardcoded training input as OpenCV doesn't handle arguments

        // Parameters for the recognition result image's directory and file name, and the recognition algorithm file's too
        $detectionFileDirectory = "application/views/OpenCV_Main/";
        $detectionFileName = "logo_recognition.py";  
        $resultFileImgName = "recognitionResult.jpg"; // Hardcoded output as OpenCV doesn't handle arguments

        // Get correct coordinate for logo, scaled down to between 0 to 1
        $query = $this->templatedb->query("SELECT coordinateLabelX, coordinateLabelY, coordinateLabelX2, coordinateLabelY2 FROM datafields WHERE templateID = '" . $templateID ."' AND dataFieldLabel = 'logo'");
        $row = $query->row(0);
        $x1 = $row->coordinateLabelX;
        $y1 = $row->coordinateLabelY;
        $x2 = $row->coordinateLabelX2 - $row->coordinateLabelX;
        $y2 = $row->coordinateLabelY2 - $row->coordinateLabelY;

        // Scale up coordinates from 0 to 1 to actual coordinates
        $x1 = $x1 * $width;
        $y1 = $y1 * $height;
        $x2 = $x2 * $width;
        $y2 = $y2 * $height;

        //cropping the image using the coordinate data
        $to_crop_array = array('x' => $x1 , 'y' => $y1, 'width' => $x2, 'height'=> $y2);
		
		if ($templateID != 0)
		{
			$thumb_im = imagecrop($im, $to_crop_array);
		}
		else
		{
			$thumb_im = $im;
		}

        imagejpeg($thumb_im, $detectionInputDirectory . $detectionInputImgName, 100);
        $logo_DB = scandir($LogoDBFilesDirectory);

        foreach($logo_DB as &$logoDBImgName){   
            // copy current database logo to detection folder as different name for py file to process
            $command = escapeshellcmd('cp ' . $LogoDBFilesDirectory . $logoDBImgName . " " . $detectionInputDirectory . $detectionInputTrainingImgName);
            shell_exec($command);

            // Run Detection
            $command = escapeshellcmd('python ' . $detectionFileDirectory . $detectionFileName);
            shell_exec($command);

            // remove the image once the check is complete.
            $command = escapeshellcmd('rm -f ' . $detectionInputDirectory . $resultFileImgName);
            shell_exec($command);

            // delete current database logo from detection folder when done
            $command = escapeshellcmd('rm -f ' . $detectionInputDirectory . $detectionInputTrainingImgName);
            shell_exec($command);
        }
        // delete current query logo from detection folder when done
        $command = escapeshellcmd('rm -f ' . $detectionInputDirectory . $detectionInputImgName);
        shell_exec($command); 
    }

    //@Author Justin Doan, refactored/modified for security by Tan Tack Poh

    /*OCR processing, including reading the image, retrieving the coordinate data from the database,

      cropping out that part of the image and run OCR on it

      */   

    public function ocr($billID, $template)        

    {
        $amountImgFileDirectory = "images/detection_result/";

        $dueDateImgFileDirectory = "images/detection_result/";

        $amountImgFileName = "croppedAmt.jpg";

        $dueDateImgFileName = "croppedDueDate.jpg";

        //connect to mysql and getting the coordinate data
        require_once('TesseractOCR.php');

        $this->billdb->select('billFilePath');
        $this->billdb->where('billID', $billID);
        $query1 = $this->billdb->get('bills');//$this->billdb->query("SELECT billFilePath from bills where billID = " . $billID);
        $ini_filename = $query1->result()[0]->billFilePath;
        $im = imagecreatefromjpeg($ini_filename);

        list($width, $height) = getimagesize($ini_filename);

        $query2 = $this->templatedb->query("SELECT coordinateLabelX, coordinateLabelY, coordinateLabelX2, coordinateLabelY2 FROM datafields WHERE templateID = '" . $template ."' AND dataFieldLabel = 'amount'");
        $row = $query2->row(0);
        $x1 = $row->coordinateLabelX;
        $y1 = $row->coordinateLabelY;
        $x2 = $row->coordinateLabelX2 - $row->coordinateLabelX;
        $y2 = $row->coordinateLabelY2 - $row->coordinateLabelY;

        // Scale Up coordinates
        $x1 = $x1 * $width;

        $y1 = $y1 * $height;

        $x2 = $x2 * $width;

        $y2 = $y2 * $height;

        //cropping the image using the coordinate data
        $to_crop_array = array('x' => $x1 , 'y' => $y1, 'width' => $x2, 'height'=> $y2);
		
		if ($template != 0)
		{
			$thumb_im = imagecrop($im, $to_crop_array);
		}
		else
		{
			$thumb_im = $im;
		}

        imagejpeg($thumb_im, $amountImgFileDirectory . $amountImgFileName, 100);

        //run OCR on the cropped section
        $tesseract = new TesseractOCR($amountImgFileDirectory . $amountImgFileName);
        $tesseract->setLanguage('eng');
        $amount = $tesseract->recognize();
		$amount = preg_replace("/[^0-9,.]/", "", $amount);

        $query3 = $this->templatedb->query("SELECT coordinateLabelX, coordinateLabelY, coordinateLabelX2, coordinateLabelY2 FROM datafields WHERE templateID = '" . $template ."' AND dataFieldLabel = 'duedate'");
        $row = $query3->row(0);
        $x1 = $row->coordinateLabelX;
        $y1 = $row->coordinateLabelY;
        $x2 = $row->coordinateLabelX2 - $row->coordinateLabelX;
        $y2 = $row->coordinateLabelY2 - $row->coordinateLabelY;

        // Scale Up coordinates
        $x1 = $x1 * $width;

        $y1 = $y1 * $height;

        $x2 = $x2 * $width;

        $y2 = $y2 * $height;
        
        //cropping the image using the coordinate data
        $to_crop_array = array('x' => $x1 , 'y' => $y1, 'width' => $x2, 'height'=> $y2);
        
		if ($template != 0)
		{
			$thumb_im = imagecrop($im, $to_crop_array);
		}
		else
		{
			$thumb_im = $im;
		}

        imagejpeg($thumb_im, $dueDateImgFileDirectory . $dueDateImgFileName, 100);

        //run OCR on the cropped section
        $tesseract = new TesseractOCR($dueDateImgFileDirectory . $dueDateImgFileName);
        $tesseract->setLanguage('eng');
        $duedate = $tesseract->recognize();

        $amount = strtok($amount, " ");

        $day = strtok($duedate, " ");
        $month = strtok(" ");
        $year = strtok(" ");
        
        str_replace(array(",", "."), "", $day);
        str_replace(array(",", "."), "", $month);
        str_replace(array(",", "."), "", $year);
        
        if (ctype_alpha($day)) {
        	$temp = $day;
        	$day = $month;
        	$month = $temp;
        }

        switch ($month) {
            case 'Jan':
            case 'January':
                $month = "01";
                break;
            case 'Feb':
            case 'February':
                $month = "02";
                break;
            case 'Mar':
            case 'March':
                $month = "03";
                break;
            case 'Apr':
            case 'April':
                $month = "04";
                break;
            case 'May':
                $month = "05";
                break;
            case 'Jun':
            case 'June':
                $month = "06";
                break;
            case 'Jul':
            case 'July':
                $month = "07";
                break;
            case 'Aug':
            case 'August':
                $month = "08";
                break;
            case 'Sep':
            case 'September':
                $month = "09";
                break;
            case 'Oct':
            case 'October':
                $month = "10";
                break;
            case 'Nov':
            case 'November':
                $month = "11";
                break;
            case 'Dec':
            case 'December':
                $month = "12";
                break;
        }
        
        $data = array(
                    'totalAmt' => $amount,
                    'billDueDate' => $year . "-" . $month . "-" . $day
                );
        $this->billdb->where('billID', $billID);
        $this->billdb->update('bills',$data);

        /* remove the cropped images once the check is complete.

        $command = escapeshellcmd('rm -f ' . $amountImgFileDirectory . $amountImgFileName);

        shell_exec($command);
        $command = escapeshellcmd('rm -f ' . $dueDateImgFileDirectory . $dueDateImgFileName);

        shell_exec($command);
		*/
		
        return $ini_filename;
    }
    
    
}