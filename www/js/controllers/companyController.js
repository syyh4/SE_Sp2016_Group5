angular.module("myApp", ["chart.js"]).controller("CompanyController", function ($scope, $http, $window) {

	//	CONSTANTS
	var base_url_main = "http://52.165.38.69/";
	var base_url_dev = "http://40.86.85.30/cs4320_v2/";
	var base_url = base_url_main;

	
	/*
		Other Information
	*/
	var queryParams = {};
	
	
	/*
		Company Information
	*/
	
	//	Basic Info
	$scope.basicInfo = {
		"cid" 				: -1,
		"name" 				: "",
		"description" 		: "",
		"lid" 				: -1,
		"coord_lat" 		: -1,
		"coord_long" 		: -1,
		"street_address" 	: "",
		"city" 				: "",
		"state" 			: "", 
		"country" 			: "",
		"zip" 				: "",
		"pretty_location"	: ""
	};
	
	//	Employees Information
	$scope.employees = [];
	
	//	Chart - Age Info
	$scope.ageChartInfo = {
		"labels" : ["January", "February", "March", "April", "May", "June", "July"],
		"series" : ['Series A'],
		"data" : [[65, 59, 80, 81, 56, 55, 40]]
	};
	
	//	Chart - Gender Info
	$scope.genderChartInfo = {
		"labels" : [],
		"data" : []
	};
	
  $scope.onClick = function (points, evt) {
    console.log(points, evt);
  };
  
  
  
  
  
  
  
	/*
	  Main Onload Code
	*/
	pull_query_params();
	pull_age_chart_data();
	pull_basic_company_info();
	pull_company_employees();
	pull_company_gender_information();
  
	function pull_company_gender_information() {
		
		var token_info = get_token_information();
		
		
		var req_url = 	base_url + "api/company.php?"
						+ "cid=" + $scope.queryParams["cid"] + "&"
						+ "req_type=analytics&"
						+ "graph-type=gender-pie-chart&"
						+ "auth_token=" + token_info["auth_token"];
						
		console.log("Gender api req url -> " + req_url);
		
		$http({
			method	:	'GET',
			url		:	req_url
		}).then( function successCallback( response ) {
			
			clean_company_gender_dict( response.data );
						
			console.log( $scope.genderChartInfo );
			
		}, function errorCallback( response ) {
			
			var error_string = "There was an error processing the request";
			console.log( error_string );
		});
		
		
		
		
	}
	function pull_company_employees() {
		
		
		var token_info = get_token_information();
		
		var req_url = 	base_url + "api/company.php?"
						+ "cid=" + $scope.queryParams["cid"] + "&"
						+ "req_type=company-employees&"
						+ "auth_token=" + token_info["auth_token"];
						
		
		$http({
			method	:	'GET',
			url		:	req_url
		}).then( function successCallback( response ) {
			
			$scope.employees = response.data;
			
		}, function errorCallback( response ) {
			
			var error_string = "There was an error processing the request";
			console.log( error_string );
		});
		
		
		
		
	}
	function pull_query_params() {
		
		var url_string = $window.location.href;
		
		//	Check to make sure there are query parameters
		
		var quest_mark_pos = url_string.indexOf('?');
		
		var params = {};
		
		if (quest_mark_pos !== -1 ) {
			//	There are some parameters
			
			
			
			var amp_mark_pos = url_string.indexOf('&');
			
			if (amp_mark_pos !== -1 ) {
				
				params = url_string.split('?')[1].split('&');
				
				var arr_len = params.length;
				
				for( var i = 0; i < arr_len; i++ ) {
					
					var param_value = params[i];
					
					var param_value_split = param_value.split('=');
					
					var key_val = param_value_split[0];
					var val_val = param_value_split[1];
					
					params[key_val] = val_val;
					
					
					
				}
			}
			else {
				
				var params_str = url_string.split('?')[1];
				var param_value_split = params_str.split('=');
				
				var key_val = param_value_split[0];
				var val_val = param_value_split[1];
				
				params[key_val] = val_val;
				
			}
		}
		
		$scope.queryParams = params;
		
	}
	function pull_basic_company_info() {
		
		var token_info = get_token_information();
		
		
		var api_url = 	base_url + "api/company.php?" + 
						"req_type=company-info&" + 
						"cid=" + $scope.queryParams["cid"] + "&" + 
						"auth_token=" + token_info["auth_token"];

		console.log( api_url );
		$http({
			method 	: 'GET',
			url 	: api_url
		}).then( function successCallback( response ) {
			
			var fixed_dict = clean_company_info_dict( response.data );
			
			
		}, function errorCallback( response ) {
			
			var error_string = "There was an error pulling the basic company information...";
		});
		
	}
	
	
	
	
	function pull_age_chart_data() {
	  
	  var auth_token = sessionStorage.auth_token;
	  
	  var api_url = base_url + "api/company.php?" +
	  			"req_type=analytics&" +
	  			"cid=" + $scope.queryParams["cid"] + "&" +
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
	
	function clean_company_gender_dict( ret_dict ) {
		
		console.log(JSON.stringify( ret_dict ));
		
		$scope.genderChartInfo.labels = ret_dict.labels;
		$scope.genderChartInfo.data = ret_dict.data
		
		
		
		
	}
	function clean_company_info_dict( ret_dict ) {
		
		//	Put all values from the response dictionary into the
		//	controllers 'basicInfo' scope dictionary
		$scope.basicInfo.cid 			= ret_dict["company_id"];
		$scope.basicInfo.name 			= ret_dict["company_name"];
		$scope.basicInfo.description 	= ret_dict["company_description"];
		$scope.basicInfo.lid 			= ret_dict["location_id"];
		$scope.basicInfo.coord_lat 		= ret_dict["latitude"];
		$scope.basicInfo.coord_long 	= ret_dict["longitutde"];
		$scope.basicInfo.street_address = ret_dict["street_address"];
		$scope.basicInfo.city 			= ret_dict["city"];
		$scope.basicInfo.state 			= ret_dict["state"];
		$scope.basicInfo.country 		= ret_dict["country"];
		$scope.basicInfo.zip 			= ret_dict["zip"];
		$scope.basicInfo.image_url		= ret_dict["company_image"];
		
		//	Set the pretty location value
		var pretty_location = $scope.basicInfo.street_address + ", " + $scope.basicInfo.city + ", " + $scope.basicInfo.state + " " + $scope.basicInfo.zip;
		
		$scope.basicInfo.pretty_location = pretty_location;
		
		console.log("Scope Basic Info " + $scope.basicInfo);
		
		
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
	
	
	/*
		Utility Functions
	*/
	function get_token_information() {
		
		var ret_dict;
		
		if (typeof(Storage) !== "undefined") {
			
			var auth_token 	= sessionStorage.getItem( "auth_token" );
			var expires_in 	= sessionStorage.getItem( "expires_in" );
			var user_id		= sessionStorage.getItem( "user_id" );
			
			ret_dict = {
				"auth_token" 	: auth_token,
				"expires_in" 	: expires_in,
				"user_id"		: user_id
			};
		}
		
		return ret_dict;
	}
});