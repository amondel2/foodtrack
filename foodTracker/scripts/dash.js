
var wupdate = function() {
	var f = $('form')[0];
	if( f.checkValidity && f.checkValidity()){
		if($("#origWeight").val() != $("#weight").val()) {
		$.post( "dash.php", $( "form" ).serialize(), function(data) {
			if(data.status != "SUCCESS") {
					alert("ERROR: " + data.message);
				} else {
				window.location.reload(true);
			}}
		,"json").always(function() {
			dialog.dialog( "close" );
		});
		} else {
			dialog.dialog( "close" );
		}
	}
};
var dialog;

$(document).ready(function(){
	dialog =  $( "#dialog-form" ).dialog({
		autoOpen: false,
		height: 300,
		width: 350,
		modal: true,
		buttons: {
		"Update Weight": wupdate,
		Cancel: function() {
		dialog.dialog( "close" );
		}
		},
		close: function() {
		}
		});
	
	$("tr td:first-child").on('click',function(){
		$("#uxref").val($(this).attr('uuid'));
		$("#weight").val($(this).attr('weight'));
		$("#origWeight").val($(this).attr('weight'));
		 dialog.dialog( "open" );
	});
});