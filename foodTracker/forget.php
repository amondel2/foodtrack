<?php
require_once ('libs/common/global_inc.php');
if (is_loged_in ()) {
	header ( 'Location: ' . WEB_BASE_COMMON . 'index.php' );
	die ();
}

$db_connection = new pg_database_class ();
if (false === $db_connection->open_connection ( FUNCTION_LIBRARY_POSTGRES_DB_NAME, FUNCTION_LIBRARY_POSTGRES_USER, FUNCTION_LIBRARY_POSTGRESS_PASSWORD )) {
	trigger_error ( 'Database DEAD...' . $db_connection->get_last_error_message () );
	exit ();
}

foreach($_REQUEST as $key=>$val) {
	$_REQUEST[$key] =  make_database_safe(strip_tags(trim($val)));
}

if ($_REQUEST && isset ( $_REQUEST ['user_id'] )) {
	$sql = "Select sec_question,user_id from wl_users where user_id ='{$_REQUEST ['user_id']}'";
	$rs = $db_connection->db_query ( $sql );
	if (pg_num_rows ( $rs ) == 0) {
		echo json_encode ( array (
				"status" => "FAIL",
				"message" => "No User Found" 
		) );
	} else {
		$row = pg_fetch_assoc ( $rs );
		
		echo json_encode ( array (
				"status" => 'SUCCESS',
				"sec_question" =>  $row ['sec_question'],
				"user_id" => $row ['user_id']
		) );
	}
	die ();
} elseif ($_REQUEST && isset ( $_REQUEST ['seca'] )) {
	$sql = "Select sec_question from wl_users where user_id ='{$_REQUEST ['uid']}' and sec_ans='{$_REQUEST ['seca']}'";
	$rs = $db_connection->db_query ( $sql );
	if (pg_num_rows ( $rs ) == 0) {
		echo json_encode ( array (
				"status" => "FAIL",
				"message" => "Anwser is not correct"
		) );
	} else {
		$row = pg_fetch_assoc ( $rs );
		$uid = $row ['sec_question'];

		echo json_encode ( array (
				"status" => 'SUCCESS',
				"ans_correct" => true
		) );
	}
	die ();
} elseif ($_REQUEST && isset ( $_REQUEST ['inputPassword'] )) {
	if($_REQUEST['inputConPassword'] != $_REQUEST['inputPassword']){
		echo json_encode(array("status"=>"FAIL","message"=>"Password Don't Match"));
		die();
	}
	$_REQUEST['inputPassword'] = sha1($_REQUEST['inputPassword']);
	$sql = "Update wl_users set password = '{$_REQUEST['inputPassword']}' where user_id = '{$_REQUEST ['uid']}'";
	$rs = $db_connection->db_query($sql);
	if( $rs === false || pg_affected_rows($rs) == 0)
	{
		echo json_encode(array("status"=>"FAIL","message"=>pg_last_error ( $db_connection->get_pg_resource () )));
		die();
	}
	
		echo json_encode ( array (
				"status" => 'SUCCESS',
				"pass_change" => true
		) );
	
	die ();
}

display_html_start();
echo '
</head><body>',
get_header_html(),'
		<h3>Create user</h3>
	</div>
		<div class="row" id="message"></div>
		<div class="row">
		<form class="form-vertical"  role="form" id="myfrm">
			<input type="hidden" class="form-control" id="uid" name="uid" value="" required>
			<div class="row">
			<div class="form-group">
				 <label class="sr-only" for="user_id">User Id:</label>
            	 <input type="text" class="form-control" id="user_id" name="user_id"  placeholder="User Id" required>
			 </div>
			</div>
		<button type="submit" class="btn btn-primary">Get Security Question</button>
		</form>
		<div id="step2" style="display:none;">
			<div class="form-group">
				 <label for="seca" id="secq"></label>
            	 <input type="text" class="form-control" id="seca" name="seca"  placeholder="Security Answer" required>
			 </div>
		</div>
		<div id="step3" style="display:none;">
			<div class="form-group">
            <label class="sr-only" for="inputPassword">Password:</label>
            <input type="password" class="form-control" id="inputPassword"  name="inputPassword" placeholder="Password" min="5" max="100" required>
        	</div>
			<div class="form-group">
            <label class="sr-only" for="inputConPassword">Confirm Password:</label>
            <input type="password" class="form-control" id="inputConPassword"  name="inputConPassword" placeholder="Password" min="5" max="100" required>
        	</div>
		</div>
		
';
display_footer(array('forgot'));

?>