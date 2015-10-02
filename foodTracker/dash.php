<?php
require_once('libs/common/global_inc.php');
requries_login();

$db_connection = new pg_database_class ();
if (false === $db_connection->open_connection ( FUNCTION_LIBRARY_POSTGRES_DB_NAME, FUNCTION_LIBRARY_POSTGRES_USER, FUNCTION_LIBRARY_POSTGRESS_PASSWORD )) {
	trigger_error ( 'Database DEAD...' . $db_connection->get_last_error_message () );
	exit ();
}

if($_REQUEST && isset($_REQUEST['action']) &&  $_REQUEST['action'] == 'weight_change') {
	
	$sql = "Insert into wlc_weight_entry (wtc_xref_id,weight)
	VALUES ('{$_REQUEST['uxref']}','{$_REQUEST['weight']}')";
	$rs = $db_connection->db_query($sql);
	if( $rs === false || pg_affected_rows($rs) == 0)
	{
		echo json_encode(array("status"=>"FAIL","message"=>pg_last_error ( $db_connection->get_pg_resource () )));
		die();
	}
	echo json_encode(array("status"=>"SUCCESS"));
	die();
}
$sql = "SELECT wuf.id,wuf.intial_investment,wuf.intial_weight,wc.name,wc.start_date,wc.end_date
		from wlc_user_xref wuf
		inner join wl_challenage wc on wc.id = wuf.wlc_id
		where user_id='" . $_SESSION['user']['id'] . "' order by start_date asc";
$rs = $db_connection->db_query ( $sql );
$tbody = '';

while($row = pg_fetch_assoc( $rs )) {

	$sql2  = "Select * from wlc_weight_entry where wtc_xref_id='{$row['id']}' order by date desc";
	$rs2 = $db_connection->db_query ( $sql2 );
	$row2 = pg_fetch_assoc( $rs2 );
	$orig_wieght = $row['intial_weight'];
	$current_weight = $row2['weight'];
	$loss = '';
	$weightChange = $current_weight / $orig_wieght;
	if($orig_wieght == $current_weight) {
		$loss = '0';
	} elseif ($orig_wieght > $current_weight) {
		$loss = "Lost " .  round(($weightChange - 1) * -100,2);
	} else {
		$loss = "Gained " . round(($weightChange - 1) * 100,2);
	}


	$tbody .= "<tr><td style='cursor:hand;cursor:pointer;' uuid='{$row['id']}' weight='$current_weight'>Update Weight</td><td>{$row['name']}</td><td>{$row['start_date']}</td>
	<td>{$row['end_date']}</td><td>$loss% ($orig_wieght,$current_weight) </td><td>{$row['intial_investment']}</td>";

}

display_html_start();
echo '
</head><body>',
	get_header_html()
	,'
		<h1>',$_SESSION['user']['first_name'],', Welcome To Your Dashboard</h1>
		</div>
		<div class="table-responsive"> 
        <table class="table table-bordered table-hover">
		 <thead>
                <tr>
                    <th></th>
                    <th>Chanllane Name</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Current Wieght Percent</th>
					<th>Total Invested</th>
                </tr>
            </thead>
				<tbody>',$tbody,'</tbody>
        </table>
		<div id="dialog-form" title="Update Weight">
		
		<form>
			<fieldset>
				<label for="weight">Weight</label>
				<input type="number" class="form-control" id="weight" value="" name="weight" placeholder="Weight" min="80" max="400" step="any" required>
				<input type="hidden" class="form-control" id="uxref" value="" name="uxref">
				<input type="hidden" class="form-control" id="action" name="action" value="weight_change">
				<input type="hidden" class="form-control" id="origWeight" name="origWeight" value="">
			</fieldset>
						
		</form>
		</div>
	';
display_footer(array('dash'));
?>