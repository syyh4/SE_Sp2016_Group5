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
		
		
	</body>
</html>