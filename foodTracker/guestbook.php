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
display_html_start();
echo '
	<script language="JavaScript" type="text/javascript" src="scripts/guestbook.js" ></script>
	</head><body>', get_header_html(), '
	<h3 id="main_page_content_title" class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix">Guestbook</h3>
	<div class="main_text" id="guestbook_main">
		<div id="guestbook_msg_container" class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom ui-accordion-content-active" ></div>
		<div id="guestbook_button_container" >
			<button type="button" id="sign_guest_btn" title="Sign Our Guestbook">Sign Our Guestbook</button>
		</div>
	</div>
<div id="guest_sign"> 
	<div>
		<div id="guest_new_entry_error"></div>
		<div id="guest_new_entry_form_data"> 
			<div><label for="guest_new_entry_name">Enter Name:</label> <input type="text" id="guest_new_entry_name" value="" /></div>
			<div>
				<label for="guest_new_entry_msg">Enter Message:</label> <textarea  id="guest_new_entry_msg" ></textarea>
			</div>
		</div>
	</div>
</div>
';
display_footer();
?>