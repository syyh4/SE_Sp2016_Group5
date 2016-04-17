var app = angular.module('chart_testing', ['chart.js']);


app.config(function (ChartJsProvider) {
		// Configure all charts
		ChartJsProvider.setOptions({
		  colours: ['#97BBCD', '#DCDCDC', '#F7464A', '#46BFBD', '#FDB45C', '#949FB1', '#4D5360'],
		  responsive: true
		});
		// Configure all doughnut charts
		ChartJsProvider.setOptions('Doughnut', {
		  animateScale: true
		});
	});
	
app.controller('LineChartController', ['$scope', '$timeout', function ($scope, $timeout) {
		
		$scope.dict = {
			"a" : 944828.43,
			"b" : "hello there"
		};
		
		$timeout( function() {
			console.log("What the what!");
		}, 3000);
	
		$scope.labels = ['January', 'February', 'March', 'April', 'May', 'June', 'July'];
	    $scope.series = ['Series A', 'Series B'];
	    $scope.data = [
	      [65, 59, 80, 81, 56, 55, 40],
	      [28, 48, 40, 19, 86, 27, 90]
	    ];
	    $scope.onClick = function (points, evt) {
	      console.log(points, evt);
	    };
	    $scope.onHover = function (points) {
	      if (points.length > 0) {
	        console.log('Point', points[0].value);
	      } else {
	        console.log('No point');
	      }
	    };
	}]);