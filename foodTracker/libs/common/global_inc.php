<?php
/**************************************************************************
 * global_inc.php -  One Line summary description
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
ini_set('display_errors','on');
ini_set('display_startup_errors','on');
//ini_set('include_path',get_include_path() . ':/hsphere/local/home/A878499/weightloss.mondelblatt.com/' );
ini_set('include_path',get_include_path() . ';C:/wamp/www/foodtrack' );
ini_set('memory_limit','64M');
define('WEB_BASE_COMMON','http://'.$_SERVER['SERVER_NAME'] . '/foodtrack/');
//define('FUNCTION_LIBRARY_POSTGRES_DB_NAME', 'A878499_weightloss');
define('FUNCTION_LIBRARY_POSTGRES_DB_NAME', 'A995847_foodListdev');
define('FUNCTION_LIBRARY_POSTGRES_USER', 'A995847_fin');
define('FUNCTION_LIBRARY_POSTGRESS_PASSWORD', 'Splatt66');
define('FUNCTION_LIBRARY_POSTGRESS_HOST', 'pgsql1401.ixwebhosting.com');
session_start();
require_once('libs/functions/html_functions.php');
require_once('libs/functions/login_funcs.php');
?>