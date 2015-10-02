$(document).ready(function(){
	$("button.btn-primary").on('click',function(){
		var f = $('form')[0];
		if( f.checkValidity && f.checkValidity()){
			//form is valid
			$.post( "forget_user.php", $( "form" ).serialize(), function(data) {
					if(data.status != "SUCCESS") {
						
							$("#message").html(data.message);
						} else {
							$("#message").html("Your User Id is " + data.uid);
					}}
				,"json");
		}
	});	
});