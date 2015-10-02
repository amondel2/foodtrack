<?php

/**************************************************************************
 * pg_database.php -  One Line summary description
 * Copyright (C) 2008 by Aaron Mondelblatt All Right Reserved
 *
 *
 *
 *Revision History
 *
 *  Date        	Version     BY     			Purpose of Revision
 * ---------	    --------	--------       	--------
 * Mar 4, 2008		    1.01a 		aaron			Initial Draft
 *
 **************************************************************************/
require_once ('libs/classes/debug_error_class.php');
class pg_database_class extends Debug_error_handler
{
	private $pg_resource;
	private $db_name;
	private $db_user;
	private $db_password;

	/*Constructor
	 */
	function __construct()
	{
		parent:: __construct();
		$this->pg_resource = false;
		$this->db_host = FUNCTION_LIBRARY_POSTGRESS_HOST;
	}

	/*Open a connection to the database
	 * Pass in the database Name, Database Username, and Database Password
	 * this function will return true or false
	 */
	public function open_connection($DBName, $DBUser, $DBPass)
	{
		$this->db_name = trim($DBName);
		$this->db_user = trim($DBUser);
		$this->db_password = trim($DBPass);
		if ($this->pg_resource)
		{
			$this->db_close();
		}
		$this->pg_resource = pg_connect('host=' . $this->db_host . ' port=5432 dbname=' . $this->db_name . ' user=' . $this->db_user . ' password=' . $this->db_password);
		if ($this->pg_resource === false)
		{
			$this->set_last_error_message(pg_last_error());
			return false;
		}
		return true;
	}

	public function & get_pg_resource()
	{
		return $this->pg_resource;
	}

	public function set_db_user_name($db_user_name)
	{
		$this->db_user = trim($db_user_name);
	}

	public function set_db_name($db_name)
	{
		$this->db_name = trim($db_name);
	}

	public function set_db_password($db_name)
	{
		$this->db_password = trim($db_name);
	}

	/*Run a PG Statement with the correct database instance.  You must
	 * call _open_connection before calling this command
	 */
	public function db_query($statement, $param_array = null)
	{
		if ($this->pg_resource === false)
		{
			if ($this->open_connection($this->db_name, $this->db_user, $this->db_password) === false);
			{
				return false;
			}
		}
		if( false !== stripos('Insert',substr(trim($statement),0,strlen('Insert') + 2)) )
		{
			$result = pg_exec($this->pg_resource, $statement);
		}
		else
		{
			if (is_array($param_array))
			{
				$result = pg_query_params($this->pg_resource, $statement,$param_array);
			}
			else
			{
				$result = pg_query($this->pg_resource,$statement);
			}
			if ($result === false)
			{
				$this->set_last_error_message(pg_last_error($this->pg_resource));
			}
		}
		return $result;
	}

	/*
	 * If the database is open. Close it
	 */
	public function db_close()
	{
		if ($this->pg_resource !== false)
		{
			pg_close($this->pg_resource);
			$this->pg_resource = false;
		}
	}

	function __destruct()
	{
		$this->db_close();
	}
}

/* Format a string so it won't hurt our database.
 */
function make_database_safe($string, $max_length = null)
{
	/* Strip out any existing slashes.  We need to do this so we don't end up
	 * breaking an escape sequence in the next step.
	 */
	$string = stripslashes($string);

	/* Truncate the string if it's too long:
	 */
	if (is_int($max_length))
		$string = substr($string, 0, $max_length);

	/* Put slashes back in:
	 */
	return addslashes($string);
}
?>