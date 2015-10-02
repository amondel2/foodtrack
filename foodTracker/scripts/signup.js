$(document).ready(function(){
	$("button.btn-primary").on('click',function(){
		var f = $('form')[0];
		if( f.checkValidity && f.checkValidity()){
			//form is valid
			$.post( "createUser.php", $( "form" ).serialize(), function(data) {
					if(data.status != "SUCCESS") {
						
							alert("ERROR: " + data.message);
						} else {
						alert("User created..Please Log In");
						window.location.replace("login.php");
					}}
				,"json");
		}
	});
	$("#inputUserName").on("blur",function(){
		if($(this).val().trim().length > 0) {
		$.post( "createUser.php", {'inputUserName':$(this).val(),'action':'verify_user_id'}, function(data) {
					if(data.status != "SUCCESS") {
						$("#inputUserName").val("");
							alert("ERROR: " + data.message);
						} }
				,"json");
		}
	});
	
	$("#email").on("blur",function(){
		if($(this).val().trim().length > 0) {
		$.post( "createUser.php", {'email':$(this).val(),'action':'verify_email'}, function(data) {
					if(data.status != "SUCCESS") {
						$("#email").val("");
							alert("ERROR: " + data.message);
						} }
				,"json");
		}
	});
	
});