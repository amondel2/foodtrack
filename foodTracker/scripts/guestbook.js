function populate_guestbook()
{
	$.ajax({
		cache: false,
		type: 'GET',
		url: "guestbook_ajax.php",
		data: {"action":"populate_guestbook"},
		success: function (data) {
			$('#guestbook_msg_container').html(data);
		}
	});
}

$(document).ready(function() {
	$( "button" ).button();
	$( "#guest_sign" ).dialog({ modal: true, title: "Please Sign Our GuestBook",autoOpen:false,minWidth: 430,
		close: function( event, ui ) {close_diag();},
		buttons: [ { text: "Sign The Book", id:"add_new_item", click: function() { submit_new_entry()  } },
		           
		           
		           { text: "Cancel", click: function() { $( this ).dialog( "close" ); } } ]

	});
    $('#sign_guest_btn').on('click',function()
    	{
    		sign_guestbook();
    	}
    );
    populate_guestbook();

});

function close_diag(){
	$('#guest_new_entry_name').val("");
	$('#guest_new_entry_msg').val("");
	 $("#add_new_item").removeAttr("disabled");
	 $( "#guest_new_entry_error" ).html("");
	 $( "#guest_sign" ).dialog( "close" );
	
}

function sign_guestbook()
{
	$( "#guest_sign" ).dialog("open");
}
function submit_new_entry()
{
	var error_cnt = 0;
	var error_msg = '';
	$("#add_new_item").attr("disabled","disabled");
	if( $('#guest_new_entry_name').val().trim().length < 3 )
	{
		error_cnt++;
		error_msg += 'Your Name Must Be At Least 3 Characters<br />';
	}
	if( $('#guest_new_entry_msg').val().trim().length < 10 )
	{
		error_cnt++;
		error_msg += 'Your Message Must Be At Least 10 Characters';
	}
	if(error_cnt > 0 )
	{
		$('#guest_new_entry_error').html(error_msg);
		$("#add_new_item").removeAttr("disabled");
		return false;
	}
	else
	{
		$.ajax({
			type: 'POST',
			cahce: false,
			url: 'guestbook_ajax.php',
			 error: function(){
				 $('#guest_new_entry_error').text("Could Not Add Item Because Of Server Failure.  Please Try Again Later.");
			 },
			 data: {"action":"add_new_guest_entry",
				 	"name":$('#guest_new_entry_name').val().trim(),
				 	"msg":  $('#guest_new_entry_msg').val().trim()
			 
			 },
			 success: function(data){
				 if( 'success' == data) {
					 	populate_guestbook();				
					 	$( "#guest_sign" ).dialog( "close" );
					}
			 },
			 error:function(data) {
				 $('#guest_new_entry_error').text("something went wrong please try again");
			 },
			 complete:function(){
				 $("#add_new_item").removeAttr("disabled");
			 }
		
		});
	}
}
