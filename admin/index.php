<?php
/**
 * index.php is an ADMIN ONLY page for redirects! 
 *
 * DO NOT place this folder in the root of your application space!
 *
 * DO place this in the ADMIN folder! (whatever you name it!!)
 *
 * @package nmAdmin
 * @author Bill Newman <williamnewman@gmail.com>
 * @version 2.014 2012/06/09
 * @link http://www.newmanix.com/
 * @license https://www.apache.org/licenses/LICENSE-2.0
 * @see config_inc.php 
 * @todo none
 */
 
require '../inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials

$redirect_to_login = TRUE; #if true, will redirect to admin login page, else redirect to main site index

# END CONFIG AREA ---------------------------------------------------------- 

if($redirect_to_login)
{# redirect to current login page
	myRedirect($config->adminLogin);
}else{#redirect to main site index
	myRedirect(VIRTUAL_PATH . "index.php"); 
}
?>
