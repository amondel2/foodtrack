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

if ($_REQUEST && isset ( $_REQUEST ['email'] )) {
	$sql = "Select user_id from wl_users where email_address ='{$_REQUEST ['email']}'";
	$rs = $db_connection->db_query ( $sql );
	if (pg_num_rows ( $rs ) == 0) {
		echo json_encode ( array (
				"status" => "FAIL",
				"message" => "No User Found" 
		) );
	} else {
		$row = pg_fetch_assoc ( $rs );
		$uid = $row ['user_id'];
		
		echo json_encode ( array (
				"status" => 'SUCCESS',
				"uid" => $uid 
		) );
	}
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
		<form class="form-vertical"  role="form">
			<div class="form-group">
				 <label class="sr-only" for="email">Email Address:</label>
            	 <input type="email" class="form-control" id="email" name="email"  placeholder="E-Mail" required>
			 </div>
		<button type="submit" class="btn btn-primary">Get User Name</button>
</form>
';
display_footer(array('forgot_user'));

?>