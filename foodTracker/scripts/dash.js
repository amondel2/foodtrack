
var wupdate = function() {
	var f = $('#upWight')[0];
	if( f.checkValidity && f.checkValidity()){
		$.post( "dash.php", $( "#upWight" ).serialize(), function(data) {
			if(data.status != "SUCCESS") {
					alert("ERROR: " + data.message);
				} else {
				window.location.reload(true);
			}}
		,"json").always(function() {
			dialog.dialog( "close" );
		});
	}
};

var wadd = function() {
	var f = $('#addWight')[0];
	if( f.checkValidity && f.checkValidity()){
		$.post( "dash.php", $( "#addWight" ).serialize(), function(data) {
			if(data.status != "SUCCESS") {
					alert("ERROR: " + data.message);
				} else {
				window.location.reload(true);
			}}
		,"json").always(function() {
			dialog.dialog( "close" );
		});
	}
};

var ditem = function() {
	var f = $('#add_food')[0];
	if( f.checkValidity && f.checkValidity()){
		$.post( "dash.php", $( "#add_food" ).serialize(), function(data) {
			if(data.status != "SUCCESS") {
					alert("ERROR: " + data.message);
				} else {
				window.location.reload(true);
			}}
		,"json").always(function() {
			dialog.dialog( "close" );
		});
	}
};

var dialog,dialog_add,dialog_item;

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

	dialog_add =  $( "#add-week-form" ).dialog({
		autoOpen: false,
		height: 300,
		width: 350,
		modal: true,
		buttons: {
		"Add Week": wadd,
		Cancel: function() {
			dialog_add.dialog( "close" );
		}
		},
		close: function() {
		}
		});

	dialog_item = $( "#add-food-form" ).dialog({
		autoOpen: false,
		height: 300,
		width: 350,
		modal: true,
		buttons: {
		"Add Item": ditem,
		Cancel: function() {
			$( "#date" ).datetimepicker( "destroy" );
			dialog_item.dialog( "close" );
		}
		},
		close: function() {
			$( "#date" ).datetimepicker( "destroy" );
		}
		});

	$("#addType").on('change',function(){
		if($(this).val() == 1 ) {
			$("#type0").css({"display":"none"});
			$("#type1").css({"display":"inline"});
		} else {
			$("#type1").css({"display":"none"});
			$("#type0").css({"display":"inline"});
		}
	})

	$("tr td:first-child").on('click',function(){
		$("#uxref").val($(this).attr('uuid'));
		$("#dcal").val($(this).attr('dcal'));
		$("#dactive").val($(this).attr('dactive'));
		 dialog.dialog( "open" );
	});

	$("button.btn-primary").on('click',function(){
		dialog_add.dialog( "open" );
		$('#start_date').datepicker();
		$('#end_date').datepicker();
	});

	$(".ui-icon-plus").on('click',function(){
		$("#uxref_add").val($(this).parent().parent().children('td').first().attr('uuid'));
		$("#addType").val(0);
		$("#type1").css({"display":"none"});
		$("#type0").css({"display":"inline"});
		$("#itemName").val("");
		$("#amount").val("");
		$( "#date" ).val("");
		$( "#date" ).datetimepicker( "destroy" );
		var d = new Date();
		$( "#date" ).val((d.getMonth() + 1) + "/" + d.getDate() + "/" + d.getFullYear() + " " +  d.getHours() +":" +  d.getMinutes());

		$("#date").datetimepicker({
			'hour': d.getHours(),
			'minute': d.getMinutes()});
		dialog_item.dialog( "open" );
	});
});