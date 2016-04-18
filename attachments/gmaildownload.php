<?php
set_time_limit(3000); 
 

//connect to gmail
$hostname = '{imap.gmail.com:993/imap/ssl}INBOX';
$username = 'bill.egoat.mtp@gmail.com'; # e.g somebody@gmail.com
$password = 'qwe12345678';

$inbox = imap_open($hostname,$username,$password) or die('Cannot connect to Gmail: ' . imap_last_error());

//check through unread mails only
$emails = imap_search($inbox,'UNSEEN');

$max_emails = 9999;

if($emails) {
    
    $count = 1;
    rsort($emails);
    
    foreach($emails as $email_number) 
    {

        //get header and sender name
        $overview = imap_fetch_overview($inbox,$email_number,0);
        $sender = $overview[0]->from;	
        
        echo $sender;
        
        $structure = imap_fetchstructure($inbox, $email_number);
        $attachments = array();
        
        //if attachment is found
        if(isset($structure->parts) && count($structure->parts)) 
        {
            for($i = 0; $i < count($structure->parts); $i++) 
            {
                $attachments[$i] = array(
                    'is_attachment' => false,
                    'filename' => '',
                    'name' => '',
                    'attachment' => ''
                );
            
                if($structure->parts[$i]->ifdparameters) 
                {
                    foreach($structure->parts[$i]->dparameters as $object) 
                    {
                        if(strtolower($object->attribute) == 'filename') 
                        {
                            $attachments[$i]['is_attachment'] = true;
                            $attachments[$i]['filename'] = $object->value;
                        }
                    }
                }
            
                if($structure->parts[$i]->ifparameters) 
                {
                    foreach($structure->parts[$i]->parameters as $object) 
                    {
                        if(strtolower($object->attribute) == 'name') 
                        {
                            $attachments[$i]['is_attachment'] = true;
                            $attachments[$i]['name'] = $object->value;
                        }
                    }
                }
            
                if($attachments[$i]['is_attachment']) 
                {
                    $attachments[$i]['attachment'] = imap_fetchbody($inbox, $email_number, $i+1);
                    
                    /* 4 = QUOTED-PRINTABLE encoding */
                    if($structure->parts[$i]->encoding == 3) 
                    { 
                        $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
                    }
                    /* 3 = BASE64 encoding */
                    elseif($structure->parts[$i]->encoding == 4) 
                    { 
                        $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
                    }
                }
            }
        }
        
        //check each attachment and save
        foreach($attachments as $attachment)
        {
            if($attachment['is_attachment'] == 1)
            {
                $filename = $attachment['name'];
                
                
                //filter non-pdf files 
                $filetype = $filename;
                $filetype = strrchr($filetype, ".");
                
                echo $filename . " ";
                echo $filetype . " ";
                if ($filetype == ".pdf")
                { 
                 	if (!is_dir($sender)) {
                 		mkdir($sender, 700);
                 	}
                 	
                 	/*
                 	$cwd = getcwd();
                 	echo $cwd;
                 	chdir($cwd . "/" . $sender);
                 	*/		
                 	
                	$fp = fopen("./". $email_number . "-" . $sender . "-" . $filename, "w+");
                	fwrite($fp, $attachment['attachment']);
               	 	fclose($fp);
                
                	echo "Attachment downloaded<br>";
                	
                	//chdir($cwd);
                }
            }
        }
        if($count++ >= $max_emails) break;
    }    
} 

imap_close($inbox);

echo "Done...?";

?>