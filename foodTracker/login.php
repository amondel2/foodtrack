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
		<h3>Login</h3>
	</div>
		<div class="row">
		<form class="form-inline"  role="form" >
			 <div class="form-group">
				 <label class="sr-only" for="inputUserName">User Name:</label>
            <input type="text" class="form-control" name="inputUserName" id="inputUserName" placeholder="User Name" required>
			</div>
			<div class="form-group">
            <label class="sr-only" for="password">Password:</label>
            <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
        </div>
		
		<button type="submit" class="btn btn-primary">Login</button>
	</form>
		</div>

		<div class="row">
		<a href="forget_user.php">Forget UserName</a>
		<a href="forget.php">Forget Password</a>
		</div><div class="row">
		<a href="signup.php">Create Account</a>
		
	
	';
display_footer(array('login'));
?>