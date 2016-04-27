<?php

	session_start();

	include("../../db_security/security.php");
	
	
	//	First connect to the database using values from the included file
	$db_conn = new mysqli(constant("DB_HOST"), constant("DB_USERNAME"), constant("DB_PASSWORD"), constant("DB_DATABASE"));
	
	if ($db_conn->error_code) {
		
		//	This should be replace PHP that sets the HTTP status code to 500 and
		//	sets the body to the JSON object that contains the error_code and
		//	error_string as defined by the API
		die("The connection to the database failed: " . $db_conn->connect_error);
	}

	if(isset($_POST['btn-signup']))
	{
 		$fname = mysql_real_escape_string($_POST['fname']);
		$lname = mysql_real_escape_string($_POST['lname']);
 		$email = mysql_real_escape_string($_POST['email']);
 		$gender= mysql_real_escape_string($_POST['gender']);
 		$birth = mysql_real_escape_string($_POST['birthday']);
 		$upass = md5(mysql_real_escape_string($_POST['password']));
 
 		if(mysql_query("INSERT INTO users(firstname,lastname,email,gender,birthday,password) VALUES('$fname','$lname','$email','$gender','$birth','$upass')"))
 		{
  		?>
        		<script>alert('successfully registered ');</script>
        		<?php
 		}
 		else
 		{
  		?>
        		<script>alert('error while registering you...');</script>
        		<?php
 		}
	}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Group 5 | LinkedIn</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link href="style.css" rel="stylesheet" type="text/css">
    
    <!-- CUSTOM STYLES
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <style type="text/css">
    
    body, html {
      background-color: #e7e9ec;
      font-family: Helvetica, Arial, sans-serif;
    }
    .box {
      margin-top: 10px;
      margin-bottom: 100px;
      padding-top: 40px;
      padding-bottom: 65px;
      background-color: white;
      -webkit-box-shadow: 0 1px 1px rgba(0,0,0,0.15),-1px 0 0 rgba(0,0,0,0.03),1px 0 0 rgba(0,0,0,0.03),0 1px 0 rgba(0,0,0,0.12);
      -moz-box-shadow: 0 1px 1px rgba(0,0,0,0.15),-1px 0 0 rgba(0,0,0,0.03),1px 0 0 rgba(0,0,0,0.03),0 1px 0 rgba(0,0,0,0.12);
      box-shadow: 0 1px 1px rgba(0,0,0,0.15),-1px 0 0 rgba(0,0,0,0.03),1px 0 0 rgba(0,0,0,0.03),0 1px 0 rgba(0,0,0,0.12);
    }
    .location {
      margin-top: 0px;
      margin-bottom: 10px;
    }
    .company-name {
      margin-bottom: 0px;
      font-size: 60px;
    }
    .company-desc {
      text-align: left;
    }
    .footer p {
      margin: 15px 0px;
    }
    .form-group label {
      color: #66696A;
      font-weight: 500;
      margin-bottom: 0px;
    }
    .create-button {
      margin-top: 30px;
      font-size: 31px;
      font-weight: 300;
    }
    input[type=text] {
      margin-top: 0px;
    }
    
    </style>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>

    <!-- NAVBAR
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <nav class="navbar navbar-inverse navbar-static-top" style="margin-bottom:0px">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="http://52.165.38.69/index.php">LinkedIn</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li><a href="http://52.165.38.69/home.php">Home</a></li>
            <li><a href="http://52.165.38.69/user.php">My Profile</a></li>
            <li><a href="http://52.165.38.69/search.php">Search</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li><a href="http://52.165.38.69/login.php">Login</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <!-- REGISTER
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <div class="container-fluid" style="background-image: url('http://www.hdwallpapera.com/wp-content/uploads/wallpaper/dmhdwallpapers/wallpapers-005/Beautiful%20City%20Background%20Hd.jpg');
    background-size: cover;padding-top:20px;">
      <div class="row">
        <div class="col-md-6 text-center col-md-offset-3 box">
          <h1 class="company-name">LinkedIn</h1>
          <h2 class="lead location">Create An Account</h2>
          
          <div class="col-md-10 col-md-offset-1 text-left">
            <form>
              <div class="form-group">
                <label for="fname">First Name</label>
                <input type="text" class="form-control" id="fname" name="fname">
              </div>
              <div class="form-group">
                <label for="lname">Last Name</label>
                <input type="text" class="form-control" id="lname" name="lname">
              </div>
              <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email">
              </div>
              <div class="form-group">
                <label for="gender">Gender</label>
                <select class="form-control">
                  <option>Male</option>
                  <option>Female</option>
                </select>
              </div>
              <div class="form-group">
                <label for="birthday">Birthday</label>
                <input type="text" class="form-control" id="birthday" name="birthday">
              </div>
              <div class="form-group">
                <label for="password">Password (6 or more characters)</label>
                <input type="password" class="form-control" id="password" name="password">
              </div>
              <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password">
              </div>
              <button type="submit" class="btn btn-primary btn-block btn-lg create-button">Create Account</button>
            </form>
          </div>

        </div>
      </div>
    </div>
    


    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
  </body>
</html>
