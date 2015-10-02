$(document).ready(function(){
	$("button.btn-primary").on('click',function(){
		var f = $('form')[0];
		if( f.checkValidity && f.checkValidity()){
			//form is valid
			$.post( "loginAjax.php", $( "form" ).serialize(), function(data) {
					if(data.status != "SUCCESS") {
						
							alert("ERROR: " + data.message);
						} else {
						window.location.replace("index.php");
					}}
				,"json");
		}
	})
});