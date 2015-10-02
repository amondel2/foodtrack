<?php
require_once ('libs/common/global_inc.php');
requries_login ();

$db_connection = new pg_database_class ();
if (false === $db_connection->open_connection ( FUNCTION_LIBRARY_POSTGRES_DB_NAME, FUNCTION_LIBRARY_POSTGRES_USER, FUNCTION_LIBRARY_POSTGRESS_PASSWORD )) {
	trigger_error ( 'Database DEAD...' . $db_connection->get_last_error_message () );
	exit ();
}
if ($_REQUEST && isset ( $_REQUEST ['type'] ) && $_REQUEST ['type'] == 'wieghtlbsdelta') {
	$sql = "SELECT wuf.id,wuf.intial_investment,wuf.intial_weight,wc.name,wc.start_date,wc.end_date
		from wlc_user_xref wuf
		inner join wl_challenage wc on wc.id = wuf.wlc_id
		where user_id='" . $_SESSION ['user'] ['id'] . "' order by start_date asc";
	$rs = $db_connection->db_query ( $sql );
	$tbody = '';
	
	$rtn = array ();
	
	$int = date ( 'Z' );
	
	while ( $row = pg_fetch_assoc ( $rs ) ) {
		$item_array = array ();
		$sql2 = "Select * from wlc_weight_entry where wtc_xref_id='{$row['id']}' order by date asc";
		$rs2 = $db_connection->db_query ( $sql2 );
		while ( $row2 = pg_fetch_assoc ( $rs2 ) ) {
			$item_array [] = array (
					'weight' => $row2 ['weight'],
					'time' => (strtotime ( ($row2 ['date']) ) + $int) * 1000 
			);
		}
		$rtn [] = $item_array;
	}
	
	if (pg_num_rows ( $rs ) > 0) {
		echo json_encode ( array (
				"status" => "SUCCESS",
				"dataPoints" => $rtn 
		) );
	} else {
		echo json_encode ( array (
				"status" => "FAIL",
				"message" => "User and Password combination not found" 
		) );
	}
} elseif ($_REQUEST && isset ($_REQUEST ['type'] ) && $_REQUEST ['type'] == 'wieghtlcPercent') {
	$int = date ( 'Z' );
	$rtn = array ();
	$sql = "SELECT wc.id  
	from wl_challenage wc
	inner join wlc_user_xref wuf on wc.id = wuf.wlc_id and user_id='{$_SESSION ['user'] ['id']}'";
	$rs = $db_connection->db_query ( $sql );
	$row = pg_fetch_assoc ( $rs );
	$sql = "SELECT wuf.id,wlu.first_name, wlu.last_name 
	from wlc_user_xref wuf  
	inner join  wl_users wlu on wuf.user_id = wlu.id
	where wlc_id = '{$row['id']}'
	order by wlu.first_name, wlu.last_name desc
	";
	$rs = $db_connection->db_query ( $sql );
	while ( $row = pg_fetch_assoc ( $rs ) ) {
		$item_array = array ();
		$sql2 = "Select * from wlc_weight_entry where wtc_xref_id='{$row['id']}' order by date asc";
		$rs2 = $db_connection->db_query ( $sql2 );
		$previousW = null;
		while ( $row2 = pg_fetch_assoc ( $rs2 ) ) {
			if(!$previousW) {
				$newWeight = 0;
			} else {
				$newWeight = round( (( ($row2 ['weight'] / $previousW) - 1) * 100),2);
			}
			$previousW = $row2 ['weight'];
			$item_array [] = array (
									
					'weight' => $newWeight,
					'time' => (strtotime ( ($row2 ['date']) ) + $int) * 1000
			);
		}
		$rtn [] = array("name"=> $row['first_name'] . ' ' . $row['last_name'], "percentPoints" => $item_array);
	}
	if (pg_num_rows ( $rs ) > 0) {
		echo json_encode ( array (
				"status" => "SUCCESS",
				"dataPoints" => $rtn
		) );
	} else {
		echo json_encode ( array (
				"status" => "FAIL",
				"message" => "User and Password combination not found"
		) );
	}
} elseif ($_REQUEST && isset ($_REQUEST ['type'] ) && $_REQUEST ['type'] == 'wieghtlcPBar') {
	$int = date ( 'Z' );
	$rtn = array ();
	$sql = "SELECT wc.id
	from wl_challenage wc
	inner join wlc_user_xref wuf on wc.id = wuf.wlc_id and user_id='{$_SESSION ['user'] ['id']}'";
	$rs = $db_connection->db_query ( $sql );
	$row = pg_fetch_assoc ( $rs );
	$sql = "SELECT wuf.id,wlu.first_name, wlu.last_name, wuf.intial_weight
	from wlc_user_xref wuf
	inner join  wl_users wlu on wuf.user_id = wlu.id
	where wlc_id = '{$row['id']}'
	order by wlu.first_name, wlu.last_name desc
	";
	$rs = $db_connection->db_query ( $sql );
	while ( $row = pg_fetch_assoc ( $rs ) ) {
		$previousW = $row['intial_weight'];
		$sql2 = "Select * from wlc_weight_entry where wtc_xref_id='{$row['id']}' order by date desc";
		$rs2 = $db_connection->db_query ( $sql2 );
		
		if(pg_num_rows ( $rs2 ) == 0) {
			$newWeight = 0;
		} else {
			$row2 = pg_fetch_assoc ( $rs2 );
				$newWeight = round( (( ($row2['weight'] / $previousW) - 1) * 100),2) * -1;
			}
		$rtn [] = array("name"=> $row['first_name'] . ' ' . $row['last_name'], "percentPoints" => $newWeight);
			
		
	}
	if (pg_num_rows ( $rs ) > 0) {
		echo json_encode ( array (
				"status" => "SUCCESS",
				"dataPoints" => $rtn
		) );
	} else {
		echo json_encode ( array (
				"status" => "FAIL",
				"message" => "User and Password combination not found"
		) );
	}
}
?>