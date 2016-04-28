<?php
	
?>

<html>
	<head lang="en">
		<title>Company</title>
		
		
		<script src="bower_components/angular/angular.js"></script>
		<script src="bower_components/Chart.js/Chart.js"></script>
		<script src="bower_components/angular-chart.js/dist/angular-chart.js"></script>
		<script src="js/controllers/companyController.js"></script>
		<link rel="stylesheet" href="bower_components/angular-chart.js/dist/angular-chart.css">
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
    .company-name {
      margin-bottom: 0px;
    }
    .company-desc {
      text-align: left;
    }
    .employeesheader {
      margin-top: 0px;
      margin-bottom: 30px;
    }
    
    .employeesItem {
	    margin-top: 10px;
	    margin-bottom: 10px;
    }
    .positionsheader {
      margin-top: 0px;
      margin-bottom: 30px;
    }
    .employeelist {
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
    
    .graphContainer {
	    width: 200px;
	    height: 200px
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
    
    </style>
	</head>
	
	<body ng-app="myApp" ng-controller="CompanyController">

    <nav class="navbar navbar-inverse navbar-static-top">
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
            <li><a href="http://52.165.38.69/login.php">Logout</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
		
		<!-- ABOUT COMPANY
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <div class="container">
      <div class="row">
        <div class="col-md-10 col-md-offset-1 box">
          <div class="col-sm-5 text-center">
            <img ng-src="{{ basicInfo.image_url }}" class="img-responsive" style="display:inline-block;">
          </div>
          <div class="col-md-7 text-center">
            <h1 class="company-name">{{ basicInfo.name }}</h1>
            <h2 class="lead location">{{ basicInfo.pretty_location }}</h2>
            <p class="company-desc">{{ basicInfo.description }}</p>
          </div>
        </div>
      </div>
    </div>

          <!--  Begin Graphs  -->
          <div class="container">
  <div class="row">
        <div class="col-lg-5 col-lg-offset-1 box" id="age-bar-chart">
          <div class="panel panel-default">
            <div class="panel-heading">Company Age Distribution (%)</div>
            <div class="panel-body">
              <canvas id="line" class="chart chart-bar" chart-data="ageChartInfo.data" chart-labels="ageChartInfo.labels" chart-legend="false"
                      chart-click="onClick" chart-hover="onHover" chart-series="ageChartInfo.series"></canvas>
            </div>
          </div>
        </div>
        
         <div class="col-lg-5 box" id="gender-pie-chart">
          <div class="panel panel-default">
            <div class="panel-heading">Company Gender Distribution</div>
            <div class="panel-body">
              <canvas id="pie" class="chart chart-pie" chart-data="genderChartInfo.data" chart-labels="genderChartInfo.labels"></canvas>
            </div>
          </div>
        </div>
  </div>
  </div>
    
    <!-- EMPLOYEES
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <div class="container">
      <div class="row">
        <div class="col-md-5 col-md-offset-1 box">
          <h1 class="text-center employeesheader">Current Employees</h1>
          <div style="max-height: 301px;overflow: auto;">
          <div class="employeelist" ng-repeat="emp in employees"  >
			<div class="media employeesItem">
				<div class="media-left">
					<a href="#">
						<img class="media-object" style="height: 70px; border-radius: 50%;" ng-src="{{ emp.emp_image }}">
					</a>
				</div>
				<div class="media-body">
					<h4 class="media-heading">{{ emp.fname }} {{ emp.lname }}</h4>
					<small>Just some basic info about this employee</small>
				</div>
			</div>

          </div>
          </div>
        </div>

        <!-- POSITIONS
        –––––––––––––––––––––––––––––––––––––––––––––––––– -->
        <div class="col-md-5 box">
          <h1 class="text-center positionsheader">Available Positions</h1>
          <div class="positionlist"  style="max-height: 500px;overflow: auto;">
            <div class="media">
              <div class="media-left">
                <a href="#">
                  <img class="media-object" src="http://placehold.it/64x64">
                </a>
              </div>
              <div class="media-body">
                <h4 class="media-heading">Position One</h4>
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
                <h4 class="media-heading">Position Two</h4>
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
                <h4 class="media-heading">Position Three</h4>
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
                <h4 class="media-heading">Position Four</h4>
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Id expedita autem illo voluptates velit tempora iste, culpa!
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>

	
	
	
	
	
	
	<!--	End Graphs	-->
    <!-- MAP
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <div class="container">
      <div class="row">
        <div class="col-md-10 col-md-offset-1 box">
          <h1 class="text-center" style="margin-top: 0px;">Location</h1>
          <img src="http://placehold.it/1000x500" class="img-responsive">
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
	
	
	
	
	
	
	
	
	
	
	<!--	jQuery and Bootstrap Includes	-->
	
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	
	</body>
</html>