angular.module('linkedinApp', ["chart.js"])
.config(['ChartJsProvider', function (ChartJsProvider) {
    // Configure all charts
    ChartJsProvider.setOptions({
      colours: ['#FF5252', '#FF8A80'],
      responsive: false
    });
    // Configure all line charts
    ChartJsProvider.setOptions('Line', {
      datasetFill: false
    });
  }])
  .controller('CompanyController', ['$scope', '$http', function($scope, $http) {
  	
  	$scope.ageBarChartData = [];
  	$scope.ageBarChartLabels = [];
  	$scope.ageBarChartSeries = [];
  	
  	var base_url = "http://40.86.85.30/cs4320_v2/";
  	
  	var auth_token = sessionStorage.auth_token;
  	
  	var chart_data_url = base_url + "api/company.php?req_type=analytics&"
  						+ "cid=2&"
  						+ "graph-type=age-bar-chart&"
  						+ "auth_token=" + auth_token;
  						
  						
  	$http({
			method : 'GET',
			url : chart_data_url
		}).then(function successCallBack(response) {
			
			$scope.ageBarChartData = response.data.bar-chart-data.data;
			$scope.ageBarChartLabels = response.data.labels;
			$scope.ageBarChartSeries = ["Series A"];
			
		}, function errorCallback(response) {
			
			console.log("There was an error");
			
		});
}]);