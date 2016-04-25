angular.module("myApp", ["chart.js"]).controller("CompanyController", function ($scope, $http) {

	//	CONSTANTS
	var base_url = "http://40.86.85.30/cs4320_v2/";

	
	$scope.ageChartInfo = {
		"labels" : ["January", "February", "March", "April", "May", "June", "July"],
		"series" : ['Series A'],
		"data" : [[65, 59, 80, 81, 56, 55, 40]]
	};
	
	
  $scope.onClick = function (points, evt) {
    console.log(points, evt);
  };
  
  set_session_auth_token();
  pull_age_chart_data();
  
  
  
  
  function pull_age_chart_data() {
	  
	  var api_url = base_url + "api/company.php?" +
	  			"req_type=analytics&" +
	  			"cid=2&" +
	  			"graph-type=age-bar-chart&" +
	  			"auth_token=" + sessionStorage.auth_token;
	  			
	  $http({
		  method : 'GET',
		  url : api_url
	  }).then( function successCallback(response) {
		  
		  console.log(api_url);
		  	
	  		var fixed = convert_return_dictionary_to_chart_dict( response.data );
	  		
	  		console.log( fixed );
	  		console.log( $scope.ageChartInfo );

	  		$scope.ageChartInfo = fixed;
	  		
	  }, function errorCallback(response) {
		  
	  });
  }
  
  function convert_return_dictionary_to_chart_dict( ret_dict ) {
	  
	  	
  		var fixed_dict = {
	  		"labels" : ret_dict["bar-chart-data"]["labels"],
	  		"series" : ret_dict["bar-chart-data"]["series"],
	  		"data" : [ret_dict["bar-chart-data"]["datasets"][0]["data"], ret_dict["bar-chart-data"]["datasets"][1]["data"]]
  		};
  		console.log( fixed_dict );
  		
  		return fixed_dict;
  }
  function set_session_auth_token() {
	  
	  sessionStorage.auth_token = "0FlYqDrCsQ71NUxd6On6yWw6dG9OpydpezoFcPhFGoGtjdHq15wA12GeIQ28ogyD";
	  
  }
});