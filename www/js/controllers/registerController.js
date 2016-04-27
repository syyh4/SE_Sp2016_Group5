var app = angular.module('linkedinApp', ['angularSpinner'])
  .controller('RegistrationController', ['$scope', '$http', '$window', 'usSpinnerService', function($scope, $http, usSpinnerService, $window) {
  	 
  	/*
	  	CONSTANTS
	*/
	var base_url_main = "http://52.165.38.69/";
	var base_url_dev = "http://40.86.85.30/cs4320_v2/";
	var base_url = base_url_main;
  	
  	
  	
  	
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
  	
  	$scope.registerButtonDisabled = false;
	$scope.showLoadSpinner = false;
	$scope.passwordsAreEqual = true;
	
	$scope.isButtonDisabled = function() {
		
		var retVal = $scope.registerButtonDisabled;
		
		return retVal;
		
	}
	$scope.registerUser = function() {
		
		var reg_url = base_url + "api/register.php";
		
		
		//	Gather the user's registration POST data
		var post_data = {
			"firstname"		:	$scope.reg_info.fname,
			"middlename" 	: $scope.reg_info.mname,
			"lastname" 		: $scope.reg_info.lname,
			"email" 		: $scope.reg_info.email,
			"username" 		: $scope.reg_info.username,
			"password" 		: $scope.reg_info.password,
			"gender" 		: $scope.reg_info.gender,
			"birthdate" 	: clean_date($scope.reg_info.birthdate)
		};
		
		
		//	Show the load spinner while the data is being posted
		show_load_spinner();
		
		
		$http.post( reg_url , post_data ).then( function successCallback( response ) {
			
			
			//	First stop the load spinner
			hide_load_spinner();
			
			
			if (response.status == 200 ) {
				
				//	Gather the auth token data
				var auth_token 		= response.data.auth_info.auth_token;
				var auth_expires_in = response.data.auth_info.expires_in;
				var user_id 		= response.data.auth_info.uid;
				
				//	Store the token information in session storage
				store_value_for_key_in_session_storage( "auth_token" , auth_token);
				store_value_for_key_in_session_storage( "expires_in" , auth_expires_in );
				store_value_for_key_in_session_storage( "user_id" , user_id );
				
				
				//	Redirect the user to the home page ... or to the company page if you're doing your debug stuff
				var debug_company_uid = 2;
				
				var redirect_url = base_url + "company.php?uid=" + debug_company_uid;
				
				$window.location.href = redirect_url;
			}
			else {
				
				var error_message = "I got a bad status code " + response.status;
				
				alert( error_message );
				console.log( error_message );
				
			}
			
			
			
		}, function errorCallback( response ) {
			
			var error_string = "There was some error posting the registration data";
			
			alert( error_string );
			console.log( error_string );
			
		});
		
		
	}
	
	$scope.$watch('reg_info', function(newVal, oldVal) {
		
		var isValidInfo = validate_registration_dictionary();
		
		$scope.registerButtonDisabled = !isValidInfo;
	}, true);
	
	function store_value_for_key_in_session_storage( key , value ) {
		
		if(typeof(Storage) !== "undefined" ) {
			
			sessionStorage.key = value;
			
			return true;
		}
		else {
			return false;
		}
		
		
	}
	function clean_reg_dict() {
		
		
		for (var key in $scope.reg_info) {
			if ($scope.reg_info.hasOwnProperty(key)) {
				
				var old_value = $scope.reg_info.key;
				
				var new_value = remove_returns( old_key );
				
				
				$scope.reg_info.key = new_value;
			}
		}
	}
	function remove_returns( str ) {
		
		return str.replace('\r', '');
	}
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
		
		if (name_str.length > 0)
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
	
	
	function show_load_spinner() {
		
		if (!$scope.showLoadSpinner) {
			if (!$scope.registerButtonDisabled) {
				$scope.registerButtonDisabled = true;
			}
			$scope.showLoadSpinner = true;
			usSpinnerService.spin('reg-user-request-spinner');
			
		}
		
		
	}
	
	function hide_load_spinner() {
		
		if ($scope.showLoadSpinner) {
			if ($scope.registerButtonDisabled) {
				$scope.registerButtonDisabled = false;
			}
			$scope.showLoadSpinner = false;
			usSpinnerService.stop('reg-user-request-spinner');
		}
	}
	
	
	function get_base_url() {
		
		if (use_main_url) {
			return settings.base_url;
		}
		else {
			return settings.dev_base_url;
		}
	}
	
  }]);
  
var compareTo = function() {
    return {
        require: "ngModel",
        scope: {
            otherModelValue: "=compareTo"
        },
        link: function(scope, element, attributes, ngModel) {
             
            ngModel.$validators.compareTo = function(modelValue) {
	            
	            var compare_value = modelValue == scope.otherModelValue;
	            scope.passwordsAreEqual = compare_value;
                return modelValue == scope.otherModelValue;
            };
 
            scope.$watch("otherModelValue", function() {
                ngModel.$validate();
            });
        }
    };
};
 
app.directive("compareTo", compareTo);