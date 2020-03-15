<?php
/**
 * admin.php session protected 'dashboard' page of links to administrator tool pages
 *
 * Use this file as a landing page after successfully logging in as an administrator.  
 * Be sure this page is not publicly accessible by referencing admin_only_inc.php
 * 
 * @package nmAdmin
 * @author Bill Newman <williamnewman@gmail.com>
 * @version 2.14 2012/10/01
 * @link http://www.newmanix.com/
 * @license https://www.apache.org/licenses/LICENSE-2.0
 * @see $config->adminLogin.php
 * @see $config->adminValidate.php
 * @see $config->adminLogout.php
 * @see admin_only_inc.php 
 * @todo none
 */

require '../inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials
$config->titleTag = 'Admin Dashboard'; #Fills <title> tag. If left empty will fallback to $config->titleTag in config_inc.php
$config->metaRobots = 'no index, no follow';#never index admin pages  
$server = 'localhost'; #CHANGE TO YOUR MYSQL HOST!!
$username='horsey01'; #CHANGE TO YOUR MYSQL USERNAME!!
//END CONFIG AREA ----------------------------------------------------------
$access = "admin"; #admin or higher level can view this page
include_once INCLUDE_PATH . 'admin_only_inc.php'; #session protected page - level is defined in $access var

$feedback = ""; #initialize feedback
if(isset($_GET['msg']))
{
	switch($_GET['msg'])
	{
	    case 1:
	       $feedback = "Your administrative permissions don't allow access to that page.";
	       break;
		default:
	       $feedback = "";
	}
}

if($feedback != ""){$feedback = '<div align="center"><h4><font color="red">' . $feedback . '</font></h4></div>';} #Fill out feedback HTML

get_header(); #defaults to theme header or header_inc.php
?>
<div align="center"><h3>Site Admin Page</h3></div>
<?php echo $feedback; #feedback, if any, provided here ?>
<table border="1" style="border-collapse:collapse" align="center" width="98%" cellpadding="3" cellspacing="3">
	<tr><th>Page</th><th>Purpose</th></tr>
	<?php if($_SESSION['Privilege']=="developer"){ ?>
		<tr>
			<td width="250" align="center"><a href="<?=ADMIN_PATH;?>adminer.php?server=<?=DB_HOST;?>&username=<?=DB_USER;?>&db=<?=DB_NAME;?>" target="_blank">Adminer</a></td>
			<td>
				<b>Developer Only.</b> Adminer is a low overhead MySQL administrative tool.  Use it to create, backup and alter 
				MySQL database tables. (Requires MySQL credentials for login)</p>
			</td>
		</tr>
		<tr>
			<td width="250" align="center"><a href="<?=ADMIN_PATH;?>admin_log_list.php">View Log Files</a></td>
			<td><b>Developer Only.</b> View & Delete error, benchmarking or other log files</td>
		</tr>
		</tr>
			<tr>
			<td width="250" align="center"><a href="<?=ADMIN_PATH;?>admin_info.php" target="_blank">View php_Info()</a></td>
			<td><b>Developer Only.</b> View phpInfo() command for file pathing, environment info.</td>
		</tr>
			<tr>
			<td width="250" align="center"><a href="<?=ADMIN_PATH;?>admin_session_clean.php" target="_blank">Clean (wipe out) all old sessions.</a></td>
			<td><b>Developer Only.</b></td>
		</tr>		
				
	<?php
	}
	if($_SESSION['Privilege']=="superadmin" || $_SESSION['Privilege']=="developer"){ ?>
		<tr>
			<td width="250" align="center"><a href="<?php echo $config->adminAdd; ?>">Add Administrator</a></td>
			<td><b>SuperAdmin Only.</b> Create site administrators, of any level.</td>
		</tr>
	<?php
	}
	?>
	<tr>
			<td width="250" align="center"><a href="<?php echo $config->adminReset; ?>">Reset Administrator Password</a></td>
			<td>Reset Admin passwords here.  SuperAdmins can reset the passwords of others.</td>
	</tr>
	<tr>
			<td width="250" align="center"><a href="<?php echo $config->adminEdit; ?>">Edit Administrator Data</a></td>
			<td>Edit Admin data such as first, last & email here.  SuperAdmins can edit the Privilege levels of others.</td>
	</tr>
	<tr>
		<td align="center" colspan="2"><a href="<?php echo $config->adminLogout; ?>" title="Don't forget to Logout!">Logout</a></td>
	</tr>
</table>
<?php
get_footer(); #defaults to theme footer or footer_inc.php
?>
