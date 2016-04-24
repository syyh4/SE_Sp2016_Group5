<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>User Profile | LinkedIn</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

    <!-- CUSTOM STYLES
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <style type="text/css">
    
    body, html {
      background-color: #e7e9ec;
      font-family: Helvetica, Arial, sans-serif;
    }
    .box {
      margin-top: 10px;
      padding-top: 40px;
      padding-bottom: 40px;
      background-color: white;
      -webkit-box-shadow: 0 1px 1px rgba(0,0,0,0.15),-1px 0 0 rgba(0,0,0,0.03),1px 0 0 rgba(0,0,0,0.03),0 1px 0 rgba(0,0,0,0.12);
      -moz-box-shadow: 0 1px 1px rgba(0,0,0,0.15),-1px 0 0 rgba(0,0,0,0.03),1px 0 0 rgba(0,0,0,0.03),0 1px 0 rgba(0,0,0,0.12);
      box-shadow: 0 1px 1px rgba(0,0,0,0.15),-1px 0 0 rgba(0,0,0,0.03),1px 0 0 rgba(0,0,0,0.03),0 1px 0 rgba(0,0,0,0.12);
    }
    .location {
      margin-top: 0px;
      margin-bottom: 10px;
    }
    .user-name {
      margin-bottom: 0px;
    }
    .user-desc {
      text-align: left;
    }
    .employeesheader {
      margin-top: 0px;
      margin-bottom: 30px;
    }
    .positionsheader {
      margin-top: 0px;
      margin-bottom: 30px;
    }
    .skilllist {
      padding-left: 10px;
      padding-right: 10px;
    }
    .positionlist {
      padding-left: 10px;
      padding-right: 10px;
    }
    .salariesheader {
      margin-top: 0px;
      margin-bottom: 30px;
    }
    .footer {
      width: 100%;
      height: 50px;
      background-color: #222222;
      margin-top: 30px;
    }
    .footer p {
      margin: 15px 0px;
    }
    .employeelist {
      padding-left: 10px;
      padding-right: 10px;
    }
    .friendsheader {
      margin-top: 0px;
      margin-bottom: 30px;
    }
    .suggestedheader {
      margin-top: 0px;
      margin-bottom: 30px;
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
    <nav class="navbar navbar-inverse navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="http://52.165.38.69/home.php">LinkedIn</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li><a href="http://52.165.38.69/home.php">Home</a></li>
            <li><a href="http://52.165.38.69/user.php">My Profile</a></li>
            <li><a href="http://52.165.38.69/search.php">Search</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li><a href="#">Logout</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <!-- ABOUT PERSON
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <div class="container">
      <div class="row">
        <div class="col-md-10 col-md-offset-1 box">
          <div class="col-sm-5 text-center">
            <img src="http://placehold.it/250x250" class="img-responsive" style="display:inline-block;">
          </div>
          <div class="col-md-7 text-center">
            <h1 class="user-name">Full Name</h1>
            <h2 class="lead location">Contact Info</h2>
            <p class="user-desc">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Exercitationem nisi dicta deleniti quisquam nemo ipsam distinctio minima omnis harum temporibus nam necessitatibus facilis non modi nihil, labore, sapiente optio neque. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ratione labore fugiat ipsam omnis natus inventore, ullam saepe debitis adipisci laborum unde quisquam? Numquam ea omnis quae error, corporis incidunt aliquam.</p>
          </div>
        </div>
      </div>
    </div>
    
    <!-- SKILLS
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <div class="container">
      <div class="row">
        <div class="col-md-10 col-md-offset-1 box">
          <h1 class="text-center employeesheader">Skills</h1>
          <div class="skilllist">
            <div class="col-xs-6 col-md-3">
              <a href="#" class="thumbnail">
                <img src="http://placehold.it/171x180">
              </a>
            </div>
            <div class="col-xs-6 col-md-3">
              <a href="#" class="thumbnail">
                <img src="http://placehold.it/171x180">
              </a>
            </div>
            <div class="col-xs-6 col-md-3">
              <a href="#" class="thumbnail">
                <img src="http://placehold.it/171x180">
              </a>
            </div>
            <div class="col-xs-6 col-md-3">
              <a href="#" class="thumbnail">
                <img src="http://placehold.it/171x180">
              </a>
            </div>
            <div class="col-xs-6 col-md-3">
              <a href="#" class="thumbnail">
                <img src="http://placehold.it/171x180">
              </a>
            </div>
            <div class="col-xs-6 col-md-3">
              <a href="#" class="thumbnail">
                <img src="http://placehold.it/171x180">
              </a>
            </div>
            <div class="col-xs-6 col-md-3">
              <a href="#" class="thumbnail">
                <img src="http://placehold.it/171x180">
              </a>
            </div>
            <div class="col-xs-6 col-md-3">
              <a href="#" class="thumbnail">
                <img src="http://placehold.it/171x180">
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- FRIENDS
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <div class="container">
      <div class="row">
        <div class="col-md-10 col-md-offset-1 box">
          <h1 class="text-center friendsheader">Friends</h1>
          <div class="employeelist">
            <div class="media">
              <div class="media-left">
                <a href="#">
                  <img class="media-object" src="http://placehold.it/64x64">
                </a>
              </div>
              <div class="media-body">
                <h4 class="media-heading">Employee One</h4>
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Id expedita autem illo voluptates velit tempora iste, culpa!
              </div>
            </div>
            <div class="media">
              <div class="media-left">
                <a href="#">
                  <img class="media-object" src="http://placehold.it/64x64">
                </a>
              </div>
              <div class="media-body">
                <h4 class="media-heading">Employee Two</h4>
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Id expedita autem illo voluptates velit tempora iste, culpa!
              </div>
            </div>
            <div class="media">
              <div class="media-left">
                <a href="#">
                  <img class="media-object" src="http://placehold.it/64x64">
                </a>
              </div>
              <div class="media-body">
                <h4 class="media-heading">Employee Three</h4>
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Id expedita autem illo voluptates velit tempora iste, culpa!
              </div>
            </div>
            <div class="media">
              <div class="media-left">
                <a href="#">
                  <img class="media-object" src="http://placehold.it/64x64">
                </a>
              </div>
              <div class="media-body">
                <h4 class="media-heading">Employee Four</h4>
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Id expedita autem illo voluptates velit tempora iste, culpa!
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- MAP
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <div class="container">
      <div class="row">
        <div class="col-md-10 col-md-offset-1 box">
          <img src="http://placehold.it/1000x500" class="img-responsive">
        </div>
      </div>
    </div>

    <!-- SALARY INFORMATION
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <div class="container">
      <div class="row">
        <div class="col-md-10 col-md-offset-1 box">
          <h1 class="text-center suggestedheader">Suggested Companies</h1>
          <div class="row">
            <div class="col-sm-3">
              <div class="thumbnail">
                <img src="http://placehold.it/242x200">
                <div class="caption text-center">
                  <h3>Name</h3>
                  <p>Description</p>
                  <p><a href="#" class="btn btn-primary" role="button">More Information</a></p>
                </div>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="thumbnail">
                <img src="http://placehold.it/242x200">
                <div class="caption text-center">
                  <h3>Name</h3>
                  <p>Description</p>
                  <p><a href="#" class="btn btn-primary" role="button">More Information</a></p>
                </div>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="thumbnail">
                <img src="http://placehold.it/242x200">
                <div class="caption text-center">
                  <h3>Name</h3>
                  <p>Description</p>
                  <p><a href="#" class="btn btn-primary" role="button">More Information</a></p>
                </div>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="thumbnail">
                <img src="http://placehold.it/242x200">
                <div class="caption text-center">
                  <h3>Name</h3>
                  <p>Description</p>
                  <p><a href="#" class="btn btn-primary" role="button">More Information</a></p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- FOOTER
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <footer class="footer">
      <div class="container">
        <p class="text-muted">LinkedIn - Group 5 - Computer Engineering</p>
      </div>
    </footer>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
  </body>
</html>
