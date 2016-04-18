angular.module('linkedinApp', [])
  .controller('RegistrationController', function($scope, $http) {
  	  	
  	$scope.reg_info = {
	  	'firstname' : '',
	  	'middlename' : '',
	  	'lastname' : '',
	  	'birthdate' : '',
	  	'email' : '',
	  	'gender' : '',
	  	'password' : '',
	  	'confirm_password' : ''
  	};
  	
  	
  	$scope.test_info = {
	  	'fname' : 'Anthony',
	  	'lname' : 'Forsythe',
	  	'birthdate' : '06/22/1993',
	  	'email' : 'forsythetony@gmail.com',
	  	'gender' : 'male',
	  	'username' : 'forsythetony',
	  	'password' : 'justadefaultpassword',
	  	'auth_token' : 'auth_token'
	  	};
	  	
	$scope.val = 1;

	$scope.$watch('regInfo', function( newValue, oldValue) {
		
		var isValid = true;
		
		isValid = isValidName( $scope.regInfo.fname );
		isValid = isValidName( $scope.regInfo.lname );
		isValid = isValidDate( $scope.regInfo.birthdate );
		
		
		$scope.isValidInformation = isValid;
		
	});
	
	$scope.registerUser = function() {
		
		var auth_url = "http://40.86.85.30/cs4320_v2/api/authorization.php";
		
		var auth_url = auth_url + "?authtype=initial&" + "username=" + $scope.regInfo.username + "&password=" + $scope.regInfo.password;
		
		$http({
			method : 'GET',
			url : auth_url
		}).then(function successCallBack(response) {
			
			console.log("data -> " + JSON.stringify(response.data));
			
		}, function errorCallback(response) {
			
			console.log("There was an error");
			
		});
		
		
		
	}
	
	
	
	
	function isValidName( name_str ) {
		
		return (name.length > 5);
	}
	
	function isValidDate( date_str ) {
		
		
		var valid = true;
		
		console.log(( valid ? "Valid Date!" : "Not Valid!"));
		
	}
	
	$scope.isValidInformation = false;
	
	
	
  });
  
var compareTo = function() {
    return {
        require: "ngModel",
        scope: {
            otherModelValue: "=compareTo"
        },
        link: function(scope, element, attributes, ngModel) {
             
            ngModel.$validators.compareTo = function(modelValue) {
                return modelValue == scope.otherModelValue;
            };
 
            scope.$watch("otherModelValue", function() {
                ngModel.$validate();
            });
        }
    };
};
 
module.directive("compareTo", compareTo);