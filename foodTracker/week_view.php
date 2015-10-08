<?php
/**************************************************************************
 * guestbook.php -  One Line summary description
 * Copyright (C) 2008 by Aaron Mondelblatt All Right Reserved
 *
 *
 *
 *Revision History
 *
 *  Date        	Version     BY     			Purpose of Revision
 * ---------	    --------	--------       	--------
 * Mar 7, 2008		    1.01a 		aaron			Initial Draft
 *
 **************************************************************************/
require_once('libs/common/global_inc.php');
requries_login();
$db_connection = new pg_database_class ();
if (false === $db_connection->open_connection ( FUNCTION_LIBRARY_POSTGRES_DB_NAME, FUNCTION_LIBRARY_POSTGRES_USER, FUNCTION_LIBRARY_POSTGRESS_PASSWORD )) {
    trigger_error ( 'Database DEAD...' . $db_connection->get_last_error_message () );
    exit ();
}

if ($_REQUEST && isset($_REQUEST['action']) &&  $_REQUEST['action'] == 'detialViewMaker') {
     $sql2  = "Select * from wl_item_type where wl_day_id = {$_REQUEST['id']} order by time";
     $rs2 = $db_connection->db_query ( $sql2 );
     $calories = 0;
     $activity=0;

     $tbody='<table class="table table-bordered table-hover"><thead>
                <tr>
                    <th></th>
                    <th>Type</th>
                    <th>Description</th>
                    <th>Time</th>
                    <th>Amount</th>
                    <th>Actions</th>
                </tr>
            </thead>
                <tbody>';
     while($row = pg_fetch_assoc( $rs2 )) {

        $type="Food";
        if($row['type'] == 0 ) {
            $calories += $row['amount'];
        } else {
            $activity += $row['amount'];
             $type="Activity";
        }

        $tbody .= "<tr><td style='cursor:hand;cursor:pointer;' uuid='{$row['id']}'>Update Items</td><td>$type</td><td>{$row['description']}</td><td>{$row['time']}</td><td>{$row['amount']}</td>";
        $tbody .=  '<td><span title="delete" class="ui-icon ui-icon-trash" style="float: left;" name="delete"></td></tr>';
     }

        $sql = "Select wk.calorie_goal from wl_day wd inner join wl_week wk on wk.id=wd.wl_week_id where wd.id = {$_REQUEST['id']}";
        $row = pg_fetch_assoc( $db_connection->db_query ( $sql ) );
        $tbody = '<div>Total Calories: <span style="color:' . ($row['calorie_goal'] <= $calories ? 'red':'green' ) . ';">'  . $calories . '</span></div><div>Total Activity: '. $activity . '</div>'.$tbody . ' </tbody></table>';
     echo json_encode(array("status"=>"SUCCESS","html" => $tbody));
     die();
}  elseif ($_REQUEST && isset($_REQUEST['action']) &&  $_REQUEST['action'] == 'addType') {

        $timestamp = strtotime($_REQUEST['date']);
        $sql6  = "Select id from wl_day where id ={$_REQUEST['uxref']}";
        $rs6 = $db_connection->db_query ( $sql6 );
        if( $rs6 && pg_num_rows($rs6) > 0) {
         $row6 = pg_fetch_assoc( $rs6 );

           $sql = "Insert into wl_item_type (wl_day_id,time,description,amount,type)
                VALUES ({$row6['id']},to_timestamp('{$_REQUEST['date']}','MM/DD/YYYY HH24:MI'),'{$_REQUEST['itemName']}','{$_REQUEST['amount']}',{$_REQUEST['addType']})";

            $rs = $db_connection->db_query($sql);
        } else {
            echo json_encode(array("status"=>"FAIL","message"=>"No Days Found"));
            die();
        }

    echo json_encode(array("status"=>"SUCCESS"));
    die();
}

$sql = "SELECT *
        from wl_week
        where wl_user_id={$_SESSION['user']['id']} and id={$_REQUEST['id']}";
$rs = $db_connection->db_query ( $sql );
$row5 = pg_fetch_assoc( $rs );
$sql4  = "Select * from wl_day where wl_week_id ='{$row5['id']}' ORDER BY date ASC";
$rs4 = $db_connection->db_query ( $sql4 );
 $day_str = null;
 $day_id;
 $row3 = $row2= null;
$tbody = '<div class="panel-group" role="tablist" aria-multiselectable="true"" id="accordion2">';
while($row = pg_fetch_assoc( $rs4 )) {
     $day_id[] = $row['id'];
    $tbody .= "
     <div class='panel panel-default'>
    <div class='panel-heading' role='tab' id='headingOne'>
      <h4 class='panel-title'>
        <a role='button' data-toggle='collapse' data-parent='#accordion' href='#collapse{$row['id']}' aria-expanded='true' aria-controls='collapseOne'>
          " . date('m/d/Y',strtotime($row['date'])) . " <span style= title='Add' class='ui-icon ui-icon-plus' name='add' myid='{$row['id']}' date='" . (strtotime($row['date']) * 1000) . "'></span>

        </a>
      </h4>
    </div>
    <div id='collapse{$row['id']}' class='panel-collapse collapse' role='tabpanel' aria-labelledby='headingOne'>
      <div class='panel-body'>
        <img src='" . WEB_BASE_COMMON ."/images/spinner.gif' class='fillInData' myid='{$row['id']}'>
        </div>
    </div>
  </div>

";
}
$tbody .= '</div>';
if(count($day_id) > 0){
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



display_html_start();
echo '
</head><body>',
    get_header_html()
    ,'<H1>Week #', $row5['week_number'] ,'<br>Dates: ',date('m/d/Y',strtotime($row5['start_date'])),' - ', date('m/d/Y',strtotime($row5['end_date'])), '<br>Calories Consumer:',$row2['cal'],
    '<br>Activity Minutes:',$row3['mins'],'<br>Weekly Activity Goal:', $row5['activity_mins'] ,'<br>Weekly Calorie Goal:', ($row5['calorie_goal'] * pg_num_rows($rs4)) ,'</H1></div>',$tbody,'
      <div style="display:none;">
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
        </div>';
display_footer(array('week_view'));
?>