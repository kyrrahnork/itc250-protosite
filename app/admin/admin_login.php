<?php
/**
 * admin_login.php is entry point (form) page to administrative area
 *
 * Works with admin_validate.php to process administrator login requests.
 * Forwards user to admin_dashboard.php, upon successful login.
 *
 * 6/4/12 - added session destroy after being passed from logout due to session var 
 * needed for feedback() 
 *
 * @package nmAdmin
 * @author Bill Newman <williamnewman@gmail.com>
 * @author David Wall <dwall@goodroadnetwork.com> 
 * @author Zach Johnson <zjohnson@goodroadnetwork.com> 
 * @version 2.1 2015/04/12
 * @link http://www.goodroadnetwork.com/ 
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @see admin_validate.php
 * @see admin_dashboard.php
 * @see admin_logout.php
 * @see admin_only_inc.php     
 * @todo none
 */
 
require '../inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials
$config->pageID = 'Admin Login';
$config->titleTag = 'Admin Login'; #Fills <title> tag. If left empty will fallback to $config->titleTag in config_inc.php
$config->metaRobots = 'no index, no follow';#never index admin pages  

//END CONFIG AREA ----------------------------------------------------------
if(startSession() && isset($_SESSION['admin-red']) && $_SESSION['admin-red'] != 'admin_logout.php')
{//store redirect to get directly back to originating page
	$admin_red = $_SESSION['admin-red'];
}else{//don't redirect to logout page!
	$admin_red = '';
}#required for redirect back to previous page
get_header(); #defaults to theme header or header_inc.php
?> 
 <div class="row">
	<div class="col-sm-6">
	<form role="form" action="<?=$config->adminValidate?>" method="post">
		<div class="form-group">
			<label for="em">Email</label>
			<input type="email" class="form-control" id="em" name="em" autofocus required>
		</div>
		<div class="form-group">
			<label for="pw">Password</label>
			<input type="password" class="form-control" id="pw" name="pw" required>
		</div>
		<div class="form-group">
			<button type="submit" class="btn btn-primary">Login</button>
			<a href="forgot-admin-password.php" class="pull-right">Forgot Password?</a>
			<input type="hidden" name="red" value="' . $admin_red . '" />
		</div>
	</form>
	</div><!-- / 6 -->
    <div class="col-sm-6"></div><!-- / 6 -->
</div><!-- / row -->
<?
get_footer(); #defaults to theme footer or footer_inc.php

if(isset($_SESSION['admin-red']) && $_SESSION['admin-red'] == 'admin_logout.php')
{#since admin_logout.php uses the session var to pass feedback, kill the session here!
	$_SESSION = array();
	session_destroy();	
}
?>
