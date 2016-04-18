var app = angular.module('linkedinApp', [])
  .controller('LoginController', function($scope, $http, $window) {
  	  	
	$scope.credentials = {
		'username' : '',
		'password' : ''
		
	};
	
	
	
	$scope.authenticate = function() {
		
		var auth_url = "http://40.86.85.30/cs4320_v2/api/authorization.php";
		
		var auth_url = auth_url + "?authtype=initial&" + "username=" + $scope.credentials.username + "&password=" + $scope.credentials.password;
		
		$http({
			method : 'GET',
			url : auth_url
		}).then(function successCallBack(response) {
			
			$window.location.href = "http://40.86.85.30/cs4320_v2/register.php";
			console.log("data -> " + JSON.stringify(response.data));
			
		}, function errorCallback(response) {
			
			console.log("There was an error");
			
		});
		
		
		
	}
	
	
	
  });