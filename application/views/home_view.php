<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
   <title>Simple Login with CodeIgniter - Private Area</title>
 </head>
 <body>
   <h1>Home</h1>
   <h2>Welcome. User: <?php $userEmail = $this->session->userdata('userEmail');
                            echo $userEmail; ?>!</h2>
     
     <?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
     // If the user is validated, then this function will run
		echo 'Congratulations, you are logged in.';
        
        //Add a link to manual add bill
        echo '<br /><a href="MAddBill">Manual Add Bill</a>';
    ?>
     
   <a href="home/do_logout">Logout</a>
 </body>
</html>