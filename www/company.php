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
				
	</head>
	
	<body ng-app="myApp">
		
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