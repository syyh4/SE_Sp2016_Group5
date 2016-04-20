var app = angular.module('linkedinApp', [])
  .controller('RegistrationController', function($scope, $http) {
  	  	
  	$scope.reg_info = {
	  	'fname' : '',
	  	'mname' : '',
	  	'lname' : '',
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
	  	
	$scope.registerButtonDisabled = false;
	
	$scope.passwordsAreEqual = true;
	
	$scope.isButtonDisabled = function() {
		
		var retVal = $scope.registerButtonDisabled;
		
		console.log("Returning -> " + retVal);
		return retVal;
		
	}
	$scope.registerUser = function() {
		
		var reg_url = "http://40.86.85.30/cs4320_v2/api/register.php";
		
		var reg_url = reg_url + "?action=reguser&" +
						"fname=" + $scope.reg_info.fname + 
						"&mname=" + $scope.reg_info.mname + 
						"&lname=" + $scope.reg_info.lname +
						"&email=" + $scope.reg_info.email +
						"&username=" + $scope.reg_info.username +
						"&password=" + $scope.reg_info.password +
						"&gender=" + $scope.reg_info.gender +
						"&birthdate=" + clean_date($scope.reg_info.birthdate);
		
		console.log("Register URL = " + reg_url);
		$http({
			method : 'GET',
			url : reg_url
		}).then(function successCallBack(response) {
			
			console.log("data -> " + JSON.stringify(response.data));
			
		}, function errorCallback(response) {
			
			console.log("There was an error");
			
		});
		
		
		
	}
	
	$scope.$watch('reg_info', function(newVal, oldVal) {
		
//		console.log("The old dictionary -> " + JSON.stringify(oldVal) + "... New Val -> " + JSON.stringify(newVal));
		
		var isValidInfo = validate_registration_dictionary();
		
		//console.log("isValidInfo -> " + isValidInfo);
		
		$scope.registerButtonDisabled = !isValidInfo;
		
		console.log($scope.registerButtonDisabled);
	}, true);
	
	function clean_date( date_str ) {
		
		var clean_date;
		
		var split_str = date_str.split("/");
		
		clean_date = split_str[0] + "-" + split_str[1] + "-" + split_str[2];
		
		return clean_date;
	}
	function validate_registration_dictionary() {
		
		var isValid = {};
		var isValidBool = false;
		
		//	First Name
		isValid.fname = isValidName( $scope.reg_info.fname );
		
		//	Middle Name
		isValid.mname = isValidName( $scope.reg_info.mname );
		
		//	Last Name
		isValid.lname = isValidName( $scope.reg_info.lname );
		
		//	Birth Date
		isValid.birthdate = isValidDate( $scope.reg_info.birthdate );
		
		//	Email
		isValid.email = isValidEmail( $scope.reg_info.email );
		
		//	Gender 
		isValid.gender = isValidGender( $scope.reg_info.gender );
		
		//	Passwords
		isValid.passwords = areValidPasswords( $scope.reg_info.password , $scope.reg_info.confirm_password);
		
		console.log( isValid );

		if (isValid.fname && isValid.mname && isValid.lname && isValid.birthdate && isValid.email && isValid.gender && isValid.passwords)
		{
			isValidBool = true;
		}
		return isValidBool;
	}
	
	function areValidPasswords( pass , pass_conf) {
		
		if (pass == pass_conf) {
			
			if (isValidPassword( pass )) {
				return true;
			}
			
			return false;
		}
		
		return false;
		
	}
	function isValidPassword( pass ) {
		
		if (pass.length >= 1)
			return true;
	}
	function isValidGender( gender_str ) {
		
		if (gender_str.length >= 1)
			return true;
			
		return false;
	}
	function isValidEmail( email_str ) {
		
		var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

		var test_val = re.test( email_str );
		
		return test_val;
	}
	function isValidName( name_str ) {
		
		//console.log( "the name string " + name_str);
		var isValid = false;
		
		if (name_str.length > 5)
			isValid = true;
		
		return isValid;
		
	}
	
	function isValidDate( date_str ) {
		
		var regexString = "^(19|20)\d\d[- /.](0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])$";
		var re = new RegExp(regexString);
		
		var ret_arr = re.exec(date_str);
		
		//console.log( ret_arr );
		return true;
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
	            
	            var compare_value = modelValue == scope.otherModelValue;
	            console.log(( compare_value ? "They're equal!" : "They're not equal!"));
	            
	            console.log( ngModel );
	            scope.passwordsAreEqual = compare_value;
	            console.log("The pass are equal value is " + scope.passwordsAreEqual);
                return modelValue == scope.otherModelValue;
            };
 
            scope.$watch("otherModelValue", function() {
                ngModel.$validate();
            });
        }
    };
};
 
app.directive("compareTo", compareTo);