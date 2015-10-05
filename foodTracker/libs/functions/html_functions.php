<?php
/* *************************************************************************
 * html_functions.php - One Line summary description
 * Copyright (C) 2006 by JACDOC.
 * All Right Reserved
 *
 *
 * SUMMARY:
 * <Summary Description of the module, give its purpose, a general
 * idea of the contents and, genreal comment. May take several lines to
 * write?
 *
 *Revision History
 *
 *  Date    Version     BY     Purpose of Revision
 *--------  -------  --------  -----------------------------
 *Feb 14, 2006  x.xxbx   aaron   Initial Draft
 *
 **************************************************************************/
require_once('libs/classes/pg_database.php');
function display_html_start($keywords = 'Food Tracker',
							$description = 'Food Tracker')
{
	header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', mktime(date('h'),date('i'),date('s'),date('m') + 4,date('d'),date('Y')) ) . ' GMT' );
	header( 'Last-Modified: Fri, 7 Feb 2014 05:00:00 GMT' );
	$title = 'Food Tracker - '. htmlspecialchars(ucwords(str_replace('_',' ',basename($_SERVER['SCRIPT_FILENAME'],'.php'))),ENT_QUOTES);
	echo '<!DOCTYPE html>
				<html lang="en" ng-app>
				<head>
					<meta name="viewport" content="width=device-width, initial-scale=1">
					<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
					<title>', trim($title), '</title>
					<meta name="keywords" content="', trim($keywords), '" />
					<meta name="description" content="' . trim($description) . '" />
					<link rel="icon" href="', WEB_BASE_COMMON, 'favicon.ico" type="image/x-icon">
					<link rel="shortcut icon" href="', WEB_BASE_COMMON, 'favicon.ico" type="image/x-icon">
					<link rel="Stylesheet" href="', WEB_BASE_COMMON, 'css/normal.css" type="text/css" />
					<link rel="Stylesheet" href="', WEB_BASE_COMMON, 'css/bootstrap.css" type="text/css" />

					<link rel="Stylesheet" href="', WEB_BASE_COMMON, 'css/bootstrap-theme.css" type="text/css" />
					<link rel="Stylesheet" href="', WEB_BASE_COMMON, 'css/jquery-ui.css" type="text/css" />
					<link rel="Stylesheet" href="', WEB_BASE_COMMON, 'css/jquery-ui.structure.css" type="text/css" />
					<link rel="Stylesheet" href="', WEB_BASE_COMMON, 'css/jquery-ui.theme.css" type="text/css" />
                    <link rel="Stylesheet" href="', WEB_BASE_COMMON, 'css/jquery-ui-timestamp.css" type="text/css" />
					<link rel="Stylesheet" href="', WEB_BASE_COMMON, 'css/jquery.lightbox-0.5.css" type="text/css" />
					<script language="JavaScript" type="text/javascript" src="', WEB_BASE_COMMON, 'scripts/jquery.js"></script>
					<script language="JavaScript" type="text/javascript" src="', WEB_BASE_COMMON, 'scripts/jquery-ui.js"> </script>
					<script language="JavaScript" type="text/javascript" src="', WEB_BASE_COMMON, 'scripts/bootstrap.js"></script>
					<script language="JavaScript" type="text/javascript" src="', WEB_BASE_COMMON, 'scripts/angular.js"></script>
					<script language="JavaScript" type="text/javascript" src="', WEB_BASE_COMMON, 'scripts/jquery.lightbox-0.5.js"></script>
                    <script language="JavaScript" type="text/javascript" src="', WEB_BASE_COMMON, 'scripts/jquery-ui-timepicker-addon.min.js"></script>
					<!--[if lt IE 8]>
					<script type="text/javascript">
					   	alert("This is an old browser. I am redirecting you for the sake of the internet!");
						window.location.href = "http://www.mozilla.org/en-US/firefox/new/";
					</script>
					<![endif]-->
					';
}
function display_footer($js_files = array())
{
	echo '	 </div>
    <hr>
    <div class="row">
        <div class="col-sm-12">
            <footer>Copyright &#169; Aaron Mondelblatt 2012 - ', date('Y'), '</p>
            </footer>
        </div>
    </div>
</div>
		';
		include('ga.php');
		foreach($js_files as $i => $val) {
			echo '<script language="JavaScript" type="text/javascript" src="', WEB_BASE_COMMON, 'scripts/',$val,'.js"></script>';
		}
	echo '	</body>
</html>';
}

function get_header_html()
{
	return get_nav_menu() . '
	<div class="container">
    <div class="jumbotron">
			';
}
function get_nav_menu()
{
	$islogin = is_loged_in();
return  '<nav id="myNavbar" class="navbar navbar-default navbar-inverse navbar-fixed-top" role="navigation">
			    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbarCollapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Weight Loss Challenge</a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="index.php">Home</a></li>' .
                ($islogin ?
				'<li><a href="weight_track.php">See My Weight Tracked</a></li>'
                	: '' ) . '
				 <!-- <li class="dropdown">
					<a data-toggle="dropdown" class="dropdown-toggle" href="#">Messages <b class="caret"></b></a>
					   <ul role="menu" class="dropdown-menu">
						<li><a href="guestbook.php">GuestBook</a></li>
                        <li><a href="#">Drafts</a></li>
                        <li><a href="#">Sent Items</a></li>
                        <li class="divider"></li>
                        <li><a href="#">Trash</a></li>
                    </ul>
                </li>g -->
            </ul>
			 <ul class="nav navbar-nav navbar-right"><li><a href="' .
			 ($islogin ? 'logout.php">Logout' : 'login.php">Login')
           .'</a></li></ul>
        </div>
    </div>
</nav>';
}


?>