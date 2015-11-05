<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="apple-touch-icon" sizes="57x57" href="favicons/apple-icon-57x57.png" />
    <link rel="apple-touch-icon" sizes="60x60" href="favicons/apple-icon-60x60.png" />
    <link rel="apple-touch-icon" sizes="72x72" href="favicons/apple-icon-72x72.png" />
    <link rel="apple-touch-icon" sizes="76x76" href="favicons/apple-icon-76x76.png" />
    <link rel="apple-touch-icon" sizes="114x114" href="favicons/apple-icon-114x114.png" />
    <link rel="apple-touch-icon" sizes="120x120" href="favicons/apple-icon-120x120.png" />
    <link rel="apple-touch-icon" sizes="144x144" href="favicons/apple-icon-144x144.png" />
    <link rel="apple-touch-icon" sizes="152x152" href="favicons/apple-icon-152x152.png" />
    <link rel="apple-touch-icon" sizes="180x180" href="favicons/apple-icon-180x180.png" />
    <link rel="icon" type="image/png" sizes="192x192" href="favicons/android-icon-192x192.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="favicons/favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="96x96" href="favicons/favicon-96x96.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="favicons/favicon-16x16.png" />
    <link rel="icon" href="favicons/favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="favicons/favicon.ico" type="image/x-icon" />
    <link rel="manifest" href="favicons/manifest.json" />
    <meta name="msapplication-TileColor" content="#ffffff" />
    <meta name="msapplication-TileImage" content="favicons/ms-icon-144x144.png" />
    <meta name="theme-color" content="#ffffff" />

	<meta name="description" content="Bill.eGoat is an automatic bill sorting, tracking and analysis system for individuals or organisations." />

	<!-- Open Graph data -->
	<meta property="og:title" content="Bill.eGoat" />
	<meta property="og:type" content="article" />
	<meta property="og:url" content="https://www.billegoat.gq/" />
	<meta property="og:image" content="https://www.billegoat.gq/images/landing/logo.png" />
	<meta property="og:description" content="Bill.eGoat is an automatic bill sorting, tracking and analysis system for individuals or organisations." />

	<link href='https://fonts.googleapis.com/css?family=Ropa+Sans' rel='stylesheet' type='text/css'>
	
    <title>Bill.eGoat</title>
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/landing.css" rel="stylesheet" />
  </head>
  <body>
  <!--Navbar-->
  <div class="row">
    <nav class="navbar navbar-default">
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
      <!-- Responsive nav bar settings -->
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
      </button> 
      <a class="navbar-brand" href="https://www.billegoat.gq">
        <img class="img-responsive" style="max-height:87px" src="images/landing/logo.png" alt="Logo" />
      </a></div>
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <!-- Nav bar content -->
        <ul class="nav navbar-nav navbar-right">
          <li>
            <a href="#" id="login" data-toggle="modal" data-target="#myModal">Log In</a>
          </li>
          <li>
            <a href="#signupForm">
              <button class="btn-primary btn-lg" type="button" id="signup">Sign Up</button>
            </a>
          </li>
        </ul>
      </div>
      <!-- /.navbar-collapse -->
    </nav>
  </div>
  <!--End of Navbar-->
  

  
  
  
  
  
  <!--Main text headers-->
  <div class="container center-block text-center">
    <h1>Bill management, made simple.</h1>
    <br />
    <h2>Bill.eGoat lets you access your bills anywhere, on any device.
    <br />Best of all? No downloads needed.</h2>
  </div>
  <!---->
  <div class="col-md-6 text-right">
    <img class="img-responsive" src="images/landing/promo1.png" alt="Multi-device accessible" />
  </div>
  <div class="col-md-6" id="signupForm">
    <div class="col-md-9 text-center">
      <div class="well col-md-12">
	  
	  <!--Signup Form-->
        <form data-toggle="validator" action="" method="post">
          <div class="form-group col-md-6">
            <input type="text" class="form-control" id="firstName" name="firstName" pattern="^[,A-Za-z]{1,}$" placeholder="First Name"
            data-error="Must be alphabetical" required />
            <div class="help-block with-errors"></div>
          </div>
          <div class="form-group col-md-6">
            <input type="text" class="form-control" id="lastName" name="lastName" pattern="^[,A-Za-z]{1,}$" placeholder="Last Name"
            data-error="Must be alphabetical" required/>
            <div class="help-block with-errors"></div>
          </div>
          <div class="form-group col-md-12">
            <input type="email" class="form-control" id="userEmail" name="userEmail" placeholder="Email Address"
            data-error="This email address is invalid." required />
            <div class="help-block with-errors"></div>
          </div>
          <div class="form-group col-md-12">
            <input type="password" class="form-control" id="userPassword" name="userPassword" placeholder="Password" pattern="^[A-Za-z0-9@\.+_\-]{6,}$"
            data-error="Passwords must be minimum 6 characters, alphanumeric or contain the following symbols only: @.-+_" required />
            <div class="help-block with-errors">Minimum 6 characters</div>
          </div>
          <div class="form-group col-md-12">
            <button type="submit" class="btn btn-primary" id="signupbtn" name="signupbtn">Sign Up</button>
          </div>	  
        </form>
		
		
			  <!-- start PHP code -->
<?php
if(isset($_POST['signupbtn']) && !empty($_POST['firstName']) && !empty($_POST['lastName']) && !empty($_POST['userEmail']) && !empty($_POST['userPassword'])  )
{
    $firstName = mysql_escape_string($_POST['firstName']);
	$lastName = mysql_escape_string($_POST['lastName']);
    $userEmail = mysql_escape_string($_POST['userEmail']);
    $userPassword = mysql_escape_string($_POST['userPassword']);
	
	if(eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $userEmail))
	{
    	mysql_connect("localhost", "root", "ysAb7cEkjvOa") or die(mysql_error()); 
		mysql_select_db("userDB") or die(mysql_error()); 
		$hash=md5( rand(0,1000) );
		
		mysql_query("INSERT INTO users (passwd, userEmail, isPartnerOrg, failedLoginNo, isActivated, activationCode) VALUES(
		'". mysql_escape_string(md5($userPassword)) ."', 
		'". mysql_escape_string($userEmail) ."', 
		FALSE,
		0,
		FALSE,
		'". mysql_escape_string($hash) ."') ") or die(mysql_error());
		
		mysql_query("INSERT INTO emails (userID, userEmail, isReminderEmail, isRecoveryEmail) VALUES(
		(SELECT userID FROM users WHERE userEmail='".$userEmail."'),
		'". mysql_escape_string($userEmail) ."', 
		TRUE,
		TRUE)")or die(mysql_error());	

		mysql_query("INSERT INTO userprefs (userID, realName) VALUES(
		(SELECT userID FROM users WHERE userEmail='".$userEmail."'),
		CONCAT_WS(' ','". mysql_escape_string($firstName) ."', '". mysql_escape_string($lastName) ."'
		))")or die(mysql_error());
		
		require 'vendor/autoload.php';
		use Mailgun\Mailgun;
		
		# First, instantiate the SDK with your API credentials and define your domain. 
			$mg = new Mailgun("key-506dc93af1bb187966c4d10581f47896");
			$domain = "billegoat.gq";

# Now, compose and send your message.
$mg->sendMessage($domain, array('from'    => 'noreply@billegoat.gq', 
                                'to'      => 'daryl2158@hotmail.com', 
                                'subject' => 'The PHP SDK is awesome!', 
                                'text'    => 'It is so simple to send a message.'));
}
             
?>
<!-- stop PHP Code -->
		
		
        <div class="col-md-12">
          <h3>By clicking &quot;Sign Up&quot; I agree to Bill.eGoat&#39;s 
          <a href="#">Terms of Service</a></h3>
        </div>
      </div>
    </div>
  </div>
  <a href="#">
    <div class="row" id="scroller1">
      <div class="row" id="triangle1"></div>
    </div>
  </a> 
  <!-- Modal -->
  <div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header text-center">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
        <h2 class="modal-title">Login</h2>
      </div>
      <div class="modal-body row">
        <div class="col-md-2"></div>
        <div class="col-md-8 text-center">
          <form data-toggle="validator">
            <div class="form-group col-md-12">
              <input type="email" class="form-control" id="loginEmail" placeholder="Email Address"
              data-error="This email address is invalid." />
              <div class="help-block with-errors"></div>
            </div>
            <div class="form-group col-md-12">
              <input type="password" class="form-control" id="loginPassword" placeholder="Password"
              pattern="^[A-Za-z0-9@\.+_\-]{6,}$"
              data-error="Passwords must be minimum 6 characters, alphanumeric or contain the following symbols only: @.-+_" />
              <div class="help-block with-errors">Minimum 6 characters</div>
            </div>
            <div class="form-group col-md-12 text-left">
              <label>
              <input type="checkbox" value="" /> Keep me logged in</label>
            </div>
            <div class="form-group col-md-12">
              <button type="submit" class="btn btn-primary" id="loginbtn">Log In</button>
            </div>
          </form>
        </div>
        <div class="col-md-2"></div>
        <div class="col-md-12 text-left">
          <h3>
            <a href="#">I forgot my password</a>
          </h3>
        </div>
      </div>
    </div>
  </div>
  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script> 
  <!-- Include all compiled plugins (below), or include individual files as needed -->
   
  <script src="js/bootstrap.min.js"></script> 
  <script src="js/validator.min.js"></script></div></body>
</html>

