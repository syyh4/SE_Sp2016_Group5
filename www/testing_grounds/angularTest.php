<!doctype html>
<html>
	<head>
		<script src="../bower_components/angular/angular.js"></script>
		<script src="../bower_components/Chart.js/Chart.js"></script>
		<script src="../bower_components/angular-chart.js/dist/angular-chart.js"></script>
		<script src="app.js"></script>
		<link rel="stylesheet" href="bower_components/angular-chart.js/dist/angular-chart.css">
	</head>

	<body ng-app='app'>
		<div ng-controller = 'LineCtrl'>
			<canvas id="line" class="chart chart-line" data="data"
				labels="labels" legend="true" series="series" click="onClick">
			</canvas>
		</div>
	</body>

</html>