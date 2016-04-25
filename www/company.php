<?php
	
?>

<html>
	<head lang="en">
		<title>Charts</title>
		
		
		<script src="bower_components/angular/angular.js"></script>
		<script src="bower_components/Chart.js/Chart.js"></script>
		<script src="bower_components/angular-chart.js/dist/angular-chart.js"></script>
		<script src="js/controllers/companyController.js"></script>
		<link rel="stylesheet" href="bower_components/angular-chart.js/dist/angular-chart.css">
		<!-- Bootstrap -->
	    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

	</head>
	
	<body ng-app="myApp">
		
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
	          <a class="navbar-brand" href="http://52.165.38.69/index.php">LinkedIn</a>
	        </div>
	        <div id="navbar" class="navbar-collapse collapse">
	          <ul class="nav navbar-nav">
				<li><a href="/cs4320_v2/home.php">Home</a></li>
				<li><a href="/cs4320_v2/search.php">Search</a></li>
	          </ul>
	          <ul class="nav navbar-nav navbar-right">
	            <li><a href="http://52.165.38.69/index.php">Register</a></li>
	          </ul>
	        </div>
	      </div>
	    </nav>
    
    
    
		<div ng-controller="CompanyController">
			
			<div class="row">
				<div class="col-md-8">
					<canvas id="bar" class="chart chart-bar" chart-data="ageChartInfo.data" chart-labels="ageChartInfo.labels" chart-legend="true" chart-series="ageChartInfo.series"></canvas>
				</div>
			
			
			</div>
		</div>
		
	
	
	
	
	
	
	
	
	
	
	<!--	jQuery and Bootstrap Includes	-->
	
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	
	</body>
</html>