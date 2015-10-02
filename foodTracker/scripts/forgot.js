$(document).ready(function(){
	$("button.btn-primary").on('click',function(){
		var f = $('form')[0];
		if( f.checkValidity && f.checkValidity()){
			//form is valid
			$("#message").html("");
			$.post( "forget.php", $( "form" ).serialize(), function(data) {
					if(data.status != "SUCCESS") {
							$("#message").html(data.message);
						} else if( data.sec_question) {
							$("#secq").html(data.sec_question);
							$("#myfrm div.row").html($("#step2").html());
							$("#uid").val(data.user_id);
							$("button.btn").html("Answer Questions");
					} else if(data.pass_change) {
						$("#message").html("Password Successfully Updated");
						
					} else if (data.ans_correct ) {
						$("#myfrm div.row").html($("#step3").html());
						$("button.btn").html("Set Password");
						
					} else {
						$("#message").html(data.message);
					}
					
			}
				,"json");
		}
	});	
});