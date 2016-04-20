var app = angular.module('linkedinApp', [])
  .controller('LoginController', function($scope, $http, $window) {
  	
	var settings = {
		'base_url' : 'http://52.165.38.69/',
		'dev_base_url' : 'http://40.86.85.30/cs4320_v2/'
	};
	
	var use_main_url = false;  	
	
	$scope.credentials = {
		'username' : '',
		'password' : ''
		
	};
	
	checkIfLoggedIn();
	
	
	
	
	
	
	
	
	
	function checkIfLoggedIn() {
		
		if (typeof(Storage) !== "undefined") {
			
			if (sessionStorage.auth_token) {
				$window.location.href = base_url + "home.php";
			}
		}
		
	}
	$scope.authenticate = function() {
		
		var auth_url = get_base_url() + "api/authorization.php";
		
		var auth_url = auth_url + "?authtype=initial&" + "username=" + $scope.credentials.username + "&password=" + $scope.credentials.password;
		
		$http({
			method : 'GET',
			url : auth_url
		}).then(function successCallBack(response) {
			
			$window.location.href = base_url + "register.php";
			console.log("data -> " + JSON.stringify(response.data));
			
		}, function errorCallback(response) {
			
			console.log("There was an error");
			
		});
		
		
		
	}
	
	function get_base_url() {
		
		if (use_main_url) {
			return settings.base_url;
		}
		else {
			return settings.dev_base_url;
		}
	}

	
  });