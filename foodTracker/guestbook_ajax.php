<?php
/**************************************************************************
 * guestbook_ajax.php -  One Line summary description
 * Copyright (C) 2008 by Aaron Mondelblatt All Right Reserved
 *
 *
 *
 *Revision History
 *
 *  Date        	Version     BY     			Purpose of Revision
 * ---------	    --------	--------       	--------
 * Mar 8, 2008		    1.01a 		aaron			Initial Draft
 *
 **************************************************************************/
require_once('libs/common/global_inc.php');
require_once('libs/classes/pg_database.php');
extract($_REQUEST);
$db_connection = new pg_database_class();
if ( false === $db_connection->open_connection(FUNCTION_LIBRARY_POSTGRES_DB_NAME, FUNCTION_LIBRARY_POSTGRES_USER,
												FUNCTION_LIBRARY_POSTGRESS_PASSWORD)) {
	trigger_error('Database DEAD...' . $db_connection->get_last_error_message());
	exit();
}
switch (strtolower($action)) {
	case 'populate_guestbook':
		$sql = 'SELECT msg,date,username from guestbook order by date DESC';
		$rs = $db_connection->db_query($sql);
		if($rs === false) {
			die("bad sql" . pg_last_error($db_connection->get_pg_resource()));
		}
		$output = '';
		while( $row = pg_fetch_assoc($rs) ) 	{
			$output .= '<div class="guestbook_item_container">
							<div class="guestbook_userinfo">
								<div>
								Post by: '.htmlspecialchars($row['username'],ENT_QUOTES).'
								</div>
								<div>
								Date Posted: '.date('m-d-Y g:i:s A',strtotime($row['date'])).'
								</div>
							</div>
							<div syle="clear:both;"> </div>
							<div class="guestbook_msg_area">
							'.nl2br(htmlspecialchars($row['msg'],ENT_QUOTES)).'
							</div>
						</div>';
		}
		echo $output;
		exit();
	break;
	case 'add_new_guest_entry':
		$name = make_database_safe(strip_tags(trim($name)));
		$msg = make_database_safe(strip_tags(trim($msg)));
		$sql = 'Insert into guestbook (username,msg,ip_address) VALUES (\''.$name.'\',\''.$msg.'\',\''.$_SERVER['REMOTE_ADDR'].'\')';
		$rs = $db_connection->db_query($sql);
		if( $rs === false || pg_affected_rows($rs) == 0)
		{
			echo 'false';
		}
		else
		{
			echo 'success';
		}
		exit();
	break;
	default:
		//this was to cover a bug in some old versions of browsers I think safari and Opera had issues if an ajax return
		//nothing.
		echo ' ';
	break;
}
?>