<?php
require_once('libs/common/global_inc.php');
if (is_loged_in ()) {
	header ( 'Location: ' . WEB_BASE_COMMON . 'index.php' );
	die ();
}

$db_connection = new pg_database_class ();
if (false === $db_connection->open_connection ( FUNCTION_LIBRARY_POSTGRES_DB_NAME, FUNCTION_LIBRARY_POSTGRES_USER, FUNCTION_LIBRARY_POSTGRESS_PASSWORD )) {
	trigger_error ( 'Database DEAD...' . $db_connection->get_last_error_message () );
	exit ();
}

$pass = sha1(strip_tags(trim($_REQUEST['password'])));

$sql = "SELECT * from wl_users where password='$pass' and  user_id='" .  make_database_safe(strip_tags(trim($_REQUEST ['inputUserName']))) . "'";
$rs = $db_connection->db_query ( $sql );

if(pg_num_rows ( $rs ) > 0 ) {
		$row = pg_fetch_assoc( $rs );
		$_SESSION['user'] = $row;
		$_SESSION['login_id'] = $row['id'];
		echo json_encode(array("status"=>"SUCCESS"));
} else {
	echo json_encode(array("status"=>"FAIL","message"=>"User and Password combination not found"));
}
?>