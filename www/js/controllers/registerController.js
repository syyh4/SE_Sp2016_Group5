angular.module('linkedinApp', [])
  .controller('RegistrationController', function($scope) {
  	  	
  	$scope.regInfo = {
	  	'fname' : '',
	  	'lname' : '',
	  	'birthdate' : ''
	  	};

	$scope.$watch('registrationInformation', function( newValue, oldValue) {
		
		var isValid = true;
		
		isValid = isValidName( $scope.regInfo.fname );
		isValid = isValidName( $scope.regInfo.lname );
		isValid = isValidDate( $scope.regInfo.birthdate );
		
		
		$scope.isValidInformation = isValid;
		
	});
	
	function isValidName( name_str ) {
		
		return (name.length > 5);
	}
	
	function isValidDate( date_str ) {
		
		return ^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[1,3-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$.test( date_str );
		
	}
	
	$scope.isValidInformation = false;
	
	
	
  });