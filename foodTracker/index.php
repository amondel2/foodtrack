<?php
/**************************************************************************
 * index.php -  One Line summary description
 * Copyright (C) 2008 by Aaron Mondelblatt All Right Reserved
 *
 *
 *
 *Revision History
 *
 *  Date        	Version     BY     			Purpose of Revision
 * ---------	    --------	--------       	--------
 * Feb 28, 2008		    1.01a 		aaron			Initial Draft
 *
 **************************************************************************/
require_once('libs/common/global_inc.php');
if(is_loged_in()) {
	header('Location: ' . WEB_BASE_COMMON . 'dash.php');
	die();
}
display_html_start();
echo '
</head><body>',
	get_header_html()
	,'
		<h1>Welcome To The Food Tracker</h1>
		<p> Get Started By Logging in Checking in to your profile and competation</p>
	';
display_footer();
?>