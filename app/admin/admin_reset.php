<?php
/**
 * admin_reset.php allows an administrator to reset (reselect) a password 
 *
 * Because passwords are encrypted via the MySQL encrpyption SHA() method, 
 * we can't recover them, so we instead create new ones.
 * 
 * @package nmAdmin
 * @author Bill Newman <williamnewman@gmail.com>
 * @version 2.014 2012/06/09
 * @link http://www.newmanix.com/  
 * @license https://www.apache.org/licenses/LICENSE-2.0
 */

require '../inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials
$config->titleTag = 'Reset Admin Password'; #Fills <title> tag. If left empty will fallback to $config->titleTag in config_inc.php
$config->metaRobots = 'no index, no follow';#never index admin pages  

//END CONFIG AREA ---------------------------------------------------------- 
 
$access = "admin"; #admin can reset own password, superadmin can reset others
include_once INCLUDE_PATH . 'admin_only_inc.php'; #session protected page - level is defined in $access var

# Read the value of 'action' whether it is passed via $_POST or $_GET with $_REQUEST
if(isset($_REQUEST['act'])){$myAction = (trim($_REQUEST['act']));}else{$myAction = "";}
switch ($myAction) 
{//check for type of process
	case "edit": //2) show password change form
	 	editDisplay();
	 	break;
	case "update": //3) change password, feedback to user
		updateExecute();
		break; 
	default: //1)Select Administrator
	 	selectAdmin();
}


function selectAdmin()
{//Select administrator
	global $config;
	
	if(isset($_SESSION["Privilege"]) && $_SESSION["Privilege"] == "admin")
	{#redirect if logged in only as admin
		myRedirect(THIS_PAGE . "?act=edit");
	}

	$config->loadhead='
	<script type="text/javascript" src="' . VIRTUAL_PATH . 'include/util.js"></script>
	<script type="text/javascript">
			function checkForm(thisForm)
			{//check form data for valid info
				if(empty(thisForm.AdminID,"Please Select an Administrator.")){return false;}
				return true;//if all is passed, submit!
			}
	</script>';
	get_header();
	echo '<h3 align="center">Reset Administrator Password</h3>';
	if($_SESSION["Privilege"] != "admin")
	{# must be greater than admin level to have  choice of selection
		echo '<p align="center">Select an Administrator, to reset their password:</p>';
	}
	echo '<form action="' . $config->adminReset . '" method="post" onsubmit="return checkForm(this);">';
	$iConn = IDB::conn();
	$sql = "select AdminID,FirstName,LastName,Email,Privilege,LastLogin,NumLogins from " . PREFIX . "Admin";
	if($_SESSION["Privilege"] == "admin")
	{# limit access to the individual, if admin level
		$sql .= " where AdminID=" . $_SESSION["AdminID"];
	}
	$result = @mysqli_query($iConn,$sql) or die(trigger_error(mysqli_error($iConn), E_USER_ERROR));
	if (mysqli_num_rows($result) > 0)//at least one record!
	{//show results
		echo '
		<form action="' . $config->adminReset . '" method="post" onsubmit="return checkForm(this);">
		<table align="center" border="1" style="border-collapse:collapse" cellpadding="3" cellspacing="3">
			<tr>
				<th>AdminID</th>
				<th>Admin</th>
				<th>Email</th>
				<th>Privilege</th>
			</tr>
		';
		while ($row = mysqli_fetch_array($result))
		{//dbOut() function is a 'wrapper' designed to strip slashes, etc. of data leaving db
		     echo '
			     <tr>
					<td>
						<input type="radio" name="AdminID" value="' . dbOut($row['AdminID']) . '">'
							. dbOut($row['AdminID']) . '
					</td>
					<td>' . dbOut($row['FirstName']) . ' ' . dbOut($row['LastName']) . '</td>
					<td>' . dbOut($row['Email']) . '</td>
			     	<td>' . dbOut($row['Privilege']) . '</td>
			     </tr>
		     ';
		}
		echo '
			<input type="hidden" name="act" value="edit" />
			<tr>
				<td align="center" colspan="4">
					<input type="submit" value="Choose Admin!" />
				</td>
			</tr>
		</table>
		</form>
		';	
	}else{//no records
      //put links on page to reset form, exit
      echo '<div align="center"><h3>Currently No Administrators in Database.</h3></div>';
	}
	 echo '<div align="center"><a href="' . $config->adminDashboard . '">Exit To Admin</a></div>';
	@mysqli_free_result($result); //free resources
	get_footer();
}

function editDisplay()
{
	global $config;
	if($_SESSION["Privilege"] == "admin")
	{#use session data if logged in as admin only
		$myID = (int)$_SESSION['AdminID'];
	}else{
		if(isset($_POST['AdminID']) && (int)$_POST['AdminID'] > 0)
		{
		 	$myID = (int)$_POST['AdminID']; #Convert to integer, will equate to zero if fails
		}else{
			feedback("AdminID not numeric","error");
			myRedirect($config->adminReset);
		}
	}
	$config->loadhead = '
	<script type="text/javascript" src="' . VIRTUAL_PATH . 'include/util.js"></script>
	<script type="text/javascript">
			function checkForm(thisForm)
			{//check form data for valid info
				if(!isAlphanumeric(thisForm.PWord1,"Only alphanumeric characters are allowed for passwords.")){thisForm.PWord2.value="";return false;}
				if(!correctLength(thisForm.PWord1,6,20,"Password does not meet the following requirements:")){thisForm.PWord2.value="";return false;}
				if(thisForm.PWord1.value != thisForm.PWord2.value)
				{//match password fields
	   			alert("Password fields do not match.");
	   			thisForm.PWord1.value = "";
	   			thisForm.PWord2.value = "";
	   			thisForm.PWord1.focus();
	   			return false;
	   		}
				return true;//if all is passed, submit!
			}
	</script>
	';
	get_header();
	$iConn = IDB::conn();
	$sql = sprintf("select AdminID,FirstName,LastName,Email,Privilege from " . PREFIX . "Admin WHERE AdminID=%d",$myID);
	$result = @mysqli_query($iConn,$sql) or die(trigger_error(mysqli_error($iConn), E_USER_ERROR));
	if(mysqli_num_rows($result) > 0)//at least one record!
	{//show results
		while ($row = mysqli_fetch_array($result))
		{//dbOut() function is a 'wrapper' designed to strip slashes, etc. of data leaving db
		     $Name = dbOut($row['FirstName']) . ' ' . dbOut($row['LastName']);
		     $Email = dbOut($row['Email']);
		     $Privilege = dbOut($row['Privilege']);
		}
	}else{//no records
      //put links on page to reset form, exit
      echo '
      	<div align="center"><h3>No such administrator.</h3></div>
      	<div align="center"><a href="' . $config->adminDashboard . '">Exit To Admin</a></div>
      	';
	}
	echo '
	<h3 align="center">Reset Administrator Password</h3>
	<p align="center">
		Admin: <font color="red"><b>' . $Name . '</b></font> 
		Email: <font color="red"><b>' . $Email . '</b></font>
		Privilege: <font color="red"><b>' . $Privilege . '</b></font> 
	</p> 
	<p align="center">Be sure to write down password!!</p>
	<form action="' . $config->adminReset . '" method="post" onsubmit="return checkForm(this);">
	<table align="center">
	   <tr>
		   	<td align="right">Password</td>
		   	<td>
		   		<input type="password" name="PWord1" />
		   		<font color="red"><b>*</b></font> <em>(6-20 alphanumeric chars)</em>
		   	</td>
	   </tr>
	   <tr>
	   		<td align="right">Re-enter Password</td>
	   		<td>
	   			<input type="password" name="PWord2" />
	   			<font color="red"><b>*</b></font>
	   		</td>
	   </tr>
	   <tr>
	   		<td align="center" colspan="2">
	   			<input type="hidden" name="AdminID" value="' .$myID . '" />
	   			<input type="hidden" name="act" value="update" />
	   			<input type="submit" value="Reset Password!" />
	   			<em>(<font color="red"><b>*</b> required field</font>)</em>
	   		</td>
	   	</tr>
	</table>    
	</form>
	<div align="center"><a href="' . $config->adminDashboard . '">Exit To Admin</a></div>
	';
	@mysqli_free_result($result); #free resources
	get_footer();
}

function updateExecute()
{
	global $config;
	if(isset($_POST['AdminID']) && (int)$_POST['AdminID'] > 0)
	{
	 	$myID = (int)$_POST['AdminID']; #Convert to integer, will equate to zero if fails
	}else{
		feedback("AdminID not numeric","warning");
		myRedirect($config->adminReset);
	}
	
	if(!onlyAlphaNum($_POST['PWord1']))
	{//data must be alphanumeric or punctuation only	
		feedback("Data entered for password must be alphanumeric only");
		myRedirect(THIS_PAGE);
	}
	$iConn = IDB::conn(); 
	$redirect = $config->adminReset; # global var used for following iformReq redirection on failure
	$AdminID = iformReq('AdminID',$iConn);  # calls dbIn internally, to check form data
	$AdminPW = iformReq('PWord1',$iConn);
	
	 # SHA() is the MySQL function that encrypts the password
	$sql = sprintf("UPDATE " . PREFIX . "Admin set AdminPW=SHA('%s') WHERE AdminID=%d",$AdminPW,$AdminID);
	
	@mysqli_query($iConn,$sql) or die(trigger_error(mysqli_error($iConn), E_USER_ERROR));
	
	 //feedback success or failure of insert
	 if (mysqli_affected_rows($iConn) > 0)
	 {
		 feedback("Password Successfully Reset!","notice");
 	 }else{
		 feedback("Password NOT Reset! (or not changed from original value)");
	 }
	get_header();
	echo '
	<div align="center"><h3>Reset Administrator Password</h3></div>
	<div align="center"><a href="' . $config->adminReset . '">Reset More</a></div>
	<div align="center"><a href="' . $config->adminDashboard . '">Exit To Admin</a></div>
	';
	get_footer();
}
?>
