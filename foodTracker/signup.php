<?php
require_once('libs/common/global_inc.php');
if(is_loged_in()) {
	header('Location: ' . WEB_BASE_COMMON . 'index.php');
	die();
}
display_html_start();
echo '
</head><body>',
get_header_html()
,'
		<h3>Create user</h3>
	</div>
		<div class="row">
		<form class="form-vertical"  role="form">
			 <div class="form-group">
				 <label  class="sr-only" for="inputUserName">User Name:</label>
           		 <input type="text" class="form-control" id="inputUserName"  name="inputUserName" placeholder="User Name" min="5" max="100" required>
			</div>
			<div class="form-group">
            <label class="sr-only" for="inputPassword">Password:</label>
            <input type="password" class="form-control" id="inputPassword"  name="inputPassword" placeholder="Password" min="5" max="100" required>
        	</div>
			<div class="form-group">
            <label class="sr-only" for="inputConPassword">Confirm Password:</label>
            <input type="password" class="form-control" id="inputConPassword"  name="inputConPassword" placeholder="Password" min="5" max="100" required>
        	</div>
			<div class="form-group">
            <label class="sr-only" for="fname">First Name:</label>
            <input type="text" class="form-control" id="fname"  name="fname" placeholder="First Name" min="3" max="100" required>
        	</div>
			<div class="form-group">
            <label class="sr-only" for="lname">Last Name:</label>
            <input type="text" class="form-control" id="lname"  name="lname" placeholder="Last Name" min="3" max="100" required>
        	</div>
			<div class="form-group">
            <label class="sr-only" for="email">Email Address:</label>
            <input type="email" class="form-control" id="email" name="email"  placeholder="E-Mail" required>
        	</div>
			<div class="form-group">
            <label class="sr-only" for="secquestion">Security Question:</label>
            <input type="text" class="form-control" id="secquestion" name="secquestion" placeholder="Security Question" min="10" max="500" required>
        	</div>
			<div class="form-group">
            <label class="sr-only" for="secans">Security Answer:</label>
            <input type="text" class="form-control" id="secans" name="secans" placeholder="Security Answer" min="3" max="100" required>
        	</div>


		<button type="submit" class="btn btn-primary">Create User</button>
	</form>
';
display_footer(array('signup'));
?>