<?php
require_once('libs/common/global_inc.php');
requries_login();

$db_connection = new pg_database_class ();
if (false === $db_connection->open_connection ( FUNCTION_LIBRARY_POSTGRES_DB_NAME, FUNCTION_LIBRARY_POSTGRES_USER, FUNCTION_LIBRARY_POSTGRESS_PASSWORD )) {
	trigger_error ( 'Database DEAD...' . $db_connection->get_last_error_message () );
	exit ();
}

if($_REQUEST && isset($_REQUEST['action']) &&  $_REQUEST['action'] == 'UpdateWeek') {

	$sql = "Update wl_week set calorie_goal={$_REQUEST['dcal']},activity_mins={$_REQUEST['dactive']} where id={$_REQUEST['uxref']}";

	$rs = $db_connection->db_query($sql);
	if( $rs === false || pg_affected_rows($rs) == 0)
	{
		echo json_encode(array("status"=>"FAIL","message"=>pg_last_error ( $db_connection->get_pg_resource () )));
		die();
	}
	echo json_encode(array("status"=>"SUCCESS"));
	die();
} elseif ($_REQUEST && isset($_REQUEST['action']) &&  $_REQUEST['action'] == 'AddWeek') {
    $sql5  = "Select count(*) as total from wl_week where wl_user_id ={$_REQUEST['uxref']}";
    $rs5 = $db_connection->db_query ( $sql5 );
    $row5 = pg_fetch_assoc( $rs5 );
    $total_weeks =  $row5['total'] + 1;
    $sql = "Insert into wl_week (calorie_goal,activity_mins,week_number,wl_user_id,start_date,end_date)
    VALUES ('{$_REQUEST['dcal']}','{$_REQUEST['dactive']}','$total_weeks',{$_REQUEST['uxref']},to_timestamp('{$_REQUEST['start_date']} 00:00','MM/DD/YYYY HH24:MI'),to_timestamp('{$_REQUEST['end_date']} 23:59','MM/DD/YYYY HH24:MI'))";
    $rs = $db_connection->db_query($sql);
    if( $rs === false || pg_affected_rows($rs) == 0)
    {
        echo json_encode(array("status"=>"FAIL","message"=>pg_last_error ( $db_connection->get_pg_resource () )));
        die();
    } else {
        //A week is 7 days

        /*
         *
         */
        $wl_id =  pg_fetch_assoc($db_connection->db_query("Select id from wl_week where week_number =$total_weeks and wl_user_id={$_REQUEST['uxref']}"));
        $wl_id = $wl_id['id'];
        $timestamp = strtotime($_REQUEST['start_date']);
        $endStamp = strtotime($_REQUEST['end_date']);
        $day = intval(date("j",$timestamp));
        while($timestamp < $endStamp) {
              $timestamp = mktime(0,0,0,date("n",$timestamp),$day++ , date("Y",$timestamp));
              $sql_date = date ('m/d/Y',$timestamp);
              $sql6 = "Insert into wl_day (wl_week_id,day_name,date)  VALUES ($wl_id,'". date("l",$timestamp) . "',to_date('$sql_date','MM/DD/YYYY'))";
              $rs = $db_connection->db_query($sql6);
    }
    echo json_encode(array("status"=>"SUCCESS"));
    die();
    }
}  elseif ($_REQUEST && isset($_REQUEST['action']) &&  $_REQUEST['action'] == 'addType') {
    $sql5  = "Select id from wl_week where id ={$_REQUEST['uxref']}";
    $rs5 = $db_connection->db_query ( $sql5 );

   if( $rs5 === false || pg_affected_rows($rs5) == 0) {
        $timestamp = strtotime($_REQUEST['date']);
        $sql_date = date ('m/d/Y',$timestamp);
        $row5 = pg_fetch_assoc( $rs5 );
        $sql6  = "Select id from wl_day where wl_week_id ={$row5['id']} and date = to_date('$sql_date','MM/DD/YYYY')";
        $rs6 = $db_connection->db_query ( $sql6 );
        if( $rs6 && pg_affected_rows($rs6) > 0) {
         $row6 = pg_fetch_assoc( $rs6 );

           $sql = "Insert into wl_item_type (wl_day_id,time,description,amount,type)
                VALUES ({$row6['id']},to_timestamp('{$_REQUEST['date']}','MM/DD/YYYY HH24:MI'),'{$_REQUEST['itemName']}','{$_REQUEST['amount']}',{$_REQUEST['addType']})";

            $rs = $db_connection->db_query($sql);
        } else {
            echo json_encode(array("status"=>"FAIL","message"=>"No Days Found"));
            die();
        }



    } else {
        echo json_encode(array("status"=>"FAIL","message"=>pg_last_error ( $db_connection->get_pg_resource () )));
        die();
    }
    echo json_encode(array("status"=>"SUCCESS"));
    die();
}
$sql = "SELECT *
		from wl_week
		where wl_user_id='" . $_SESSION['user']['id'] . "' order by week_number desc";
$rs = $db_connection->db_query ( $sql );
$tbody = '';
$row3 = $row2= null;
while($row = pg_fetch_assoc( $rs )) {

	$sql4  = "Select * from wl_day where wl_week_id ='{$row['id']}'";
	$rs4 = $db_connection->db_query ( $sql4 );

    $day_str = null;
	if(pg_num_rows($rs4) > 0){
        while($row4 = pg_fetch_assoc( $rs4 )) {
    		$day_id[] = $row4['id'];
    	}
        $day_str = "('" . join("','",$day_id) . "')";
    $sql2  = "Select sum(amount) as cal from wl_item_type where wl_day_id in $day_str and type=0";
    $rs2 = $db_connection->db_query ( $sql2 );
    if(pg_num_rows($rs2) > 0){
        $row2 = pg_fetch_assoc( $rs2 );
    }

    if (pg_num_rows($rs2) == 0 || is_null($row2['cal']))  {
        $row2['cal'] = 0;
    }

    $sql3  = "Select sum(amount) as mins from wl_item_type where wl_day_id in $day_str and type=1";
    $rs3 = $db_connection->db_query ( $sql3 );

    if(pg_num_rows($rs3) > 0){
       $row3 = pg_fetch_assoc( $rs3 );
    }

     if (pg_num_rows($rs3) == 0 || is_null($row3['mins']))  {
         $row3['mins'] = 0;
    }

    } else {
        $row2['cal'] = 0;
        $row3['mins'] = 0;
    }

	$tbody .= "<tr><td style='cursor:hand;cursor:pointer;' uuid='{$row['id']}' dcal='{$row['calorie_goal']}' dactive='{$row['activity_mins']}'>Update Items</td><td>{$row['week_number']}</td><td>{$row['start_date']}</td><td>{$row['end_date']}</td><td>{$row['calorie_goal']}</td><td>{$row['activity_mins']}</td><td>{$row2['cal']}</td><td>{$row3['mins']}</td>";
    $tbody .= '<td><span title="delete" class="ui-icon ui-icon-trash" style="float: left;" name="delete"></span><span style="float: left;" title="Add" class="ui-icon ui-icon-plus" name="add"></span></td></tr>';
}

display_html_start();
echo '
</head><body>',
	get_header_html()
	,'
		<h1>',$_SESSION['user']['first_name'],', Welcome To Your Dashboard</h1>
		</div>
        <button type="submit" class="btn btn-primary">Add Week</button>
		<div class="table-responsive">
        <table class="table table-bordered table-hover">
		 <thead>
                <tr>
                    <th></th>
                    <th>Week Number</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Weekly Calorie Goal</th>
					<th>Weekky Activity Goal</th>
					<th>Calories So Far</th>
                    <th>Activities So Far</th>
                    <th>Actions</th>
                </tr>
            </thead>
				<tbody>',$tbody,'</tbody>
        </table>
        <div style="display:none;">
		<div id="dialog-form" title="Update Weight">
		<form id="upWight">
			<fieldset>
				<label for="dcal">Daily Calorie Updated</label>
				<input type="number" class="form-control" id="dcal" value="" name="dcal" placeholder="Daily Calorie Goal" min="400" max="4000" step="any" required>
                <label for="dactive">Weekly Activity Updated</label>
                <input type="number" class="form-control" id="dactive" value="" name="dactive" placeholder="Weekly Activity Goal" min="0" max="8000" step="any" required>
				<input type="hidden" class="form-control" id="uxref" value="" name="uxref">
				<input type="hidden" class="form-control" id="action" name="action" value="UpdateWeek">
			</fieldset>
		</form>
		</div>
        <div id="add-week-form" title="Add Week">
        <form id="addWight">
            <fieldset>
                <label for="dcal">Daily Calorie Updated</label>
                <input type="number" class="form-control" id="dcal" value="" name="dcal" placeholder="Daily Calorie Goal" min="400" max="4000" step="any" required>
                <label for="dactive">Weekly Activity Updated</label>
                <input type="number" class="form-control" id="dactive" value="" name="dactive" placeholder="Weekly Activity Goal" min="0" max="8000" step="any" required>
                <label for="start_date">Start Date</label><input type="text" id="start_date" name="start_date" required>
                <label for="end_date">End Date</label><input type="text" id="end_date" name="end_date" required>
                <input type="hidden" class="form-control" id="uxref" value="',$_SESSION['user']['id'],'" name="uxref">
                <input type="hidden" class="form-control" id="action" name="action" value="AddWeek">
            </fieldset>
        </form>
        </div>
        <div id="add-food-form" title="Add Item">
        <form id="add_food">
            <fieldset>
                <label for="addType">Item Type</label>
                <select id="addType" name="addType"><option value="0">Food</option><option value="1">Activity</option></select><br>
                <label for="itemName">Item Name</label>
                <input type="text" class="form-control" id="itemName" value="" name="itemName" placeholder="Item Name" required>
                <label for="amount"><span id="type0">Calories</span><span id="type1"># of Minutes</span></label>
                <input type="number" class="form-control" id="amount" value="" name="amount" min="0" max="8000" step="any" required>
                <label for="date">Date</label><input type="text" id="date" name="date" required>
                <input type="hidden" class="form-control" id="uxref_add" value="',$_SESSION['user']['id'],'" name="uxref">
                <input type="hidden" class="form-control" id="action" name="action" value="addType">
            </fieldset>
        </form>
        </div>
        </div>
	';
display_footer(array('dash'));
?>