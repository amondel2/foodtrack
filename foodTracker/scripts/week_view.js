var dialog_item;

var ditem = function() {
	var f = $('#add_food')[0];
	if( f.checkValidity && f.checkValidity()){
		$.post( "week_view.php", $( "#add_food" ).serialize(), function(data) {
			if(data.status != "SUCCESS") {
					alert("ERROR: " + data.message);
				} else {
				window.location.reload(true);
			}}
		,"json").always(function() {
			dialog_item.dialog( "close" );
		});
	}
};

$(document).ready(function(){

	$.each($(".fillInData"),function(x,y){
		var data = "id=" + $(y).attr('myid') + "&action=detialViewMaker";
		var that = $(y);
		$.post( "week_view.php",data, function(data) {
			if(data.status != "SUCCESS") {
					alert("ERROR: " + data.s);
				} else {
				$(that).replaceWith(data.html);
		}},'json');
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

	$(".ui-icon-plus").on('click',function(){
		$("#uxref_add").val($(this).attr('myid'));
		$("#addType").val(0);
		$("#type1").css({"display":"none"});
		$("#type0").css({"display":"inline"});
		$("#itemName").val("");
		$("#amount").val("");
		$( "#date" ).val("");
		$( "#date" ).datetimepicker( "destroy" );
		var nowd = new Date();
		var d = new Date(parseInt($(this).attr('date')));
		$( "#date" ).val(("0" + (d.getMonth() + 1)).slice(-2) + "/" + ("0" + (d.getDate() + 1)).slice(-2) + "/" + d.getFullYear() + " " +  ("0" + nowd.getHours()).slice(-2) +":" +  ("0" + nowd.getMinutes()).slice(-2));

		$("#date").datetimepicker({
			'hour': nowd.getHours(),
			'minute': nowd.getMinutes()});
		dialog_item.dialog( "open" );
	});
});