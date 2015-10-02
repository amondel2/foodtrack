<?php
/**************************************************************************
 * debug_error_class.php -  A Class that is ment to be extended on other class
 * that handles generic error handling and the output any debug messages
 * Copyright (C) 2007 by Aaron Mondelblatt All Right Reserved
 *
 *
 *
 *Revision History
 *
 *  Date        	Version     BY     			Purpose of Revision
 * ---------	    --------	--------       	--------
 * Sep 9, 2007		    1.01a 		aaron			Initial Draft
 *
 **************************************************************************/
class Debug_error_handler
{
	protected $show_debug;
	protected $last_error_message;

	protected function __construct()
	{
		$this->last_error_message = null;
		$this->show_debug = false;
	}

	/*Ouptut a Debug Message if debug mood is set to true.  You have
	 * the options to return the output instead of sending echoing it out
	 */
	protected function output_debug_message($input_item, $return = false)
	{
		if ($this->show_debug !== true)
			return true;
		/*If we have an array or a class we want to output the items
		 * with print_r since it looks nicer.
		 */
		if (is_array($input_item) || is_object($input_item))
		{
			$message = print_r($input_item, true);
		}
		else
		{
			$message = $input_item;
		}
		if (!$return)
		{
			echo $message . '<br />';
		}
		else
		{
			return $message;
		}
	}
	/*Set the debug value to true or false
	 */
	protected function set_debug($bool_values)
	{
		$this->set_debug = $bool_values;
	}
	/*Set the last Error Message
	 */
	protected function set_last_error_message($error_msg)
	{
		$this->last_error_message = $error_msg;
	}
	/*Get the Last Error Message
	 */
	protected function get_last_error_message()
	{
		return $this->last_error_message;
	}
}
?>
