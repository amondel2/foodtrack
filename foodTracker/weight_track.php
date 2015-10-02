<?php
require_once('libs/common/global_inc.php');
requries_login();

$db_connection = new pg_database_class ();
if (false === $db_connection->open_connection ( FUNCTION_LIBRARY_POSTGRES_DB_NAME, FUNCTION_LIBRARY_POSTGRES_USER, FUNCTION_LIBRARY_POSTGRESS_PASSWORD )) {
	trigger_error ( 'Database DEAD...' . $db_connection->get_last_error_message () );
	exit ();
}
display_html_start();
echo '
<script src="http://code.highcharts.com/highcharts.js"></script>
<script src="http://code.highcharts.com/highcharts-3d.js"></script>
<script src="http://code.highcharts.com/modules/exporting.js"></script>
</head><body>',
get_header_html()
,'
		<h1>',$_SESSION['user']['first_name'],', Welcome To Your Dashboard</h1>
		</div>
		<div class="table-responsive">
        <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
		</div>
		<div class="table-responsive">
		<div id="container2" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
		</div>
		<div class="table-responsive">
				<div id="container3"></div>
		</div>
				<div class="table-responsive"> 
				<div id="sliders">
        <table class="table">
		 <thead></thead>
			<tbody>
				<tr><td>Alpha Angle</td><td><input id="R0" type="range" min="0" max="45" value="15"> <span id="R0-value"></span></td></tr>
	    		<tr><td>Beta Angle</td><td><input id="R1" type="range" min="0" max="45" value="15"> <span id="R1-value"></span></td></tr>
			</tbody>
		</table>
		</div>
		
	';
display_footer(array('wt_track'));


?>
