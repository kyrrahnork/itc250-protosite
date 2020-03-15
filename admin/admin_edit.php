<?php
/**
 * $config->adminEdit.php is a single page web application that allows an admin to 
 * edit some of their personal data
 *
 * This page is an addition to the application started as the nmAdmin package
 * 
 * @package nmAdmin
 * @author Bill Newman <williamnewman@gmail.com>
 * @version 2.014 2012/06/09
 * @link http://www.newmanix.com/  
 * @license https://www.apache.org/licenses/LICENSE-2.0
 * @see $config->adminAdd.php 
 * @see admin_reset.php
 * @see admin_only_inc.php
 * @todo Add ability to change privilege level of admin by developer - add ability of SuperAdmin to change priv. level
 */

require '../inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials
$config->titleTag = 'Edit Administrator'; #Fills <title> tag. If left empty will fallback to $config->titleTag in config_inc.php
$config->metaRobots = 'no index, no follow';#never index admin pages  

//END CONFIG AREA ----------------------------------------------------------

$access = "admin"; #admins can edit themselves, developers can edit any - don't change this var or no one can edit their own data
include_once INCLUDE_PATH . 'admin_only_inc.php'; #session protected page - level is defined in $access var

# Read the value of 'action' whether it is passed via $_POST or $_GET with $_REQUEST
if(isset($_REQUEST['act'])){$myAction = (trim($_REQUEST['act']));}else{$myAction = "";}

switch ($myAction) 
{//check for type of process
	case "edit": # 2) show form to edit data
	 	editDisplay();
	 	break;
	case "update": # 3) execute SQL, redirect
		updateExecute();
		break; 
	default: # 1)Select Administrator
	 	selectAdmin();
}

function selectAdmin()
{//Select administrator
	global $config;
	
	if($_SESSION["Privilege"] == "admin")
	{#redirect if logged in only as admin
		myRedirect(THIS_PAGE . "?act=edit");
	}
	
	$config->loadhead='
	<script type="text/javascript" src="' . VIRTUAL_PATH . 'include/util.js"></script>
	<script type="text/javascript">
			function checkForm(thisForm)
			{//check form data for valid info
				//if(!checkRadio(thisForm.AdminID,"Please Select an Administrator.")){return false;}
				if(empty(thisForm.AdminID,"Please Select an Administrator.")){return false;}
				return true;//if all is passed, submit!
			}
	</script>
	';
	get_header();
	echo '<h3 align="center">Edit Administrator Data</h3>';
	if($_SESSION["Privilege"] == "developer" || $_SESSION["Privilege"] == "superadmin")
	{# must be greater than admin level to have  choice of selection
		echo '<p align="center">Select an Administrator, to edit their data:</p>';
	}
	echo '<form action="' . $config->adminEdit . '" method="post" onsubmit="return checkForm(this);">';
	$iConn = IDB::conn();
	$sql = "select AdminID,FirstName,LastName,Email,Privilege,LastLogin,NumLogins from " . PREFIX . "Admin";
	if($_SESSION["Privilege"] != "developer" && $_SESSION["Privilege"] != "superadmin")
	{# limit access to the individual, if not developer level
		$sql .= " where AdminID=" . $_SESSION["AdminID"];
	}
	$result = mysqli_query($iConn,$sql) or die(trigger_error(mysqli_error($iConn), E_USER_ERROR));
	if (mysqli_num_rows($result) > 0)//at least one record!
	{//show results
		echo '
		<form action="' . $config->adminEdit . '" method="post" onsubmit="return checkForm(this);">
		<table align="center" border="1" style="border-collapse:collapse" cellpadding="3" cellspacing="3">
		<tr><th>AdminID</th><th>Admin</th><th>Email</th><th>Privilege</th></tr>
		';
		while ($row = mysqli_fetch_array($result))
		{//dbOut() function is a 'wrapper' designed to strip slashes, etc. of data leaving db
		     echo '
		     <tr>
		     	<td>
		     		<input type="radio" name="AdminID" value="' . (int)$row['AdminID'] . '">' . 
		     		(int)$row['AdminID'] . '</td>
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
	$privileges = getENUM(PREFIX . 'Admin','Privilege'); #grab all possible 'Privileges' from ENUM

	$iConn = IDB::conn();
	$sql = sprintf("select FirstName,LastName,Email,Privilege from " . PREFIX . "Admin WHERE AdminID=%d",$myID);
	$result = @mysqli_query($iConn,$sql) or die(trigger_error(mysqli_error($iConn), E_USER_ERROR));
	if(mysqli_num_rows($result) > 0)//at least one record!
	{//show results
		while ($row = mysqli_fetch_array($result))
		{//dbOut() function is a 'wrapper' designed to strip slashes, etc. of data leaving db
		     $FirstName = dbOut($row['FirstName']);
		     $LastName = dbOut($row['LastName']);
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
	$config->loadhead = '
	<script type="text/javascript" src="<?php echo VIRTUAL_PATH; ?>include/util.js"></script>
	<script type="text/javascript">
			function checkForm(thisForm)
			{//check form data for valid info
				if(empty(thisForm.FirstName,"Please enter first name.")){return false;}
				if(empty(thisForm.LastName,"Please enter last name.")){return false;}
				if(!isEmail(thisForm.Email,"Please enter a valid Email Address")){return false;}
				return true;//if all is passed, submit!
			}
	</script>
	';
	get_header();
	echo '
	<h3 align="center">Edit Administrator</h3>
	<form action="' . $config->adminEdit . '" method="post" onsubmit="return checkForm(this);">
	<table align="center">
		<tr>
			<td align="right">First Name</td>
			<td>
				<input type="text" name="FirstName" value="' . $FirstName . '" />
				<font color="red"><b>*</b></font>
			</td>
		</tr>
		<tr>
			<td align="right">Last Name</td>
			<td>
				<input type="text" name="LastName" value="' . $LastName . '" />
				<font color="red"><b>*</b></font>
			</td>
		</tr>
		<tr>
			<td align="right">Email</td>
			<td>
				<input type="text" name="Email" value="' . $Email . '" />
				<font color="red"><b>*</b></font>
			</td>
		</tr>
	';
		if($_SESSION["Privilege"] == "developer" || $_SESSION["Privilege"] == "superadmin")
		{# uses createSelect() function to preload the select option
			echo '
			<tr>
				<td align="right">Privilege</td>
				<td>
				';
				# createSelect(element-type,element-name,values-array,db-array,labels-array,concatentator) - creates preloaded radio, select, checkbox set
				createSelect("select","Privilege",$privileges,$Privilege,$privileges,",");	#privileges is from ENUM	
				echo '
				</td>
			</tr>';
		}else{
			echo '<input type="hidden" name="Privilege" value="' . $_SESSION["Privilege"] . '" />';
		}	
	echo '
	   <input type="hidden" name="AdminID" value="' , $myID . '" />
	   <input type="hidden" name="act" value="update" />
	   <tr>
			<td align="center" colspan="2">
				<input type="submit" value="Update Admin" />
				<em>(<font color="red"><b>*</b> required field</font>)</em>
			</td>
		</tr>
	</table>    
	</form>
	<div align="center"><a href="' . $config->adminDashboard . '">Exit To Admin</a></div>
	';
	@mysqli_free_result($result); //free resources
	get_footer();
}

function updateExecute()
{
	global $config;
	$iConn = IDB::conn(); # MUST precede iformReq() function, which uses active connection to parse data
	$redirect = $config->adminEdit; # global var used for following iformReq redirection on failure
	$FirstName = iformReq('FirstName',$iConn);  # iformReq calls dbIn() internally, to check form data
	$LastName = iformReq('LastName',$iConn);
	$Email = strtolower(iformReq('Email',$iConn));
	$Privilege = iformReq('Privilege',$iConn);
	$AdminID = iformReq('AdminID',$iConn);
	
	#check for duplicate email
	$sql = sprintf("select AdminID from " . PREFIX . "Admin WHERE (Email='%s') and AdminID != %d",$Email,$AdminID);
	$result = mysqli_query($iConn,$sql) or die(trigger_error(mysqli_error($iConn), E_USER_ERROR));
	if(mysqli_num_rows($result) > 0)//at least one record!
	{# someone already has email!
		feedback("Email already exists - please choose a different email.");
		myRedirect($config->adminEdit); # duplicate email
	}

	#sprintf() function allows us to filter data by type while inserting DB values.  Illegal data is neutralized, ie: numerics become zero
	$sql = sprintf("UPDATE " . PREFIX . "Admin set FirstName='%s',LastName='%s',Email='%s',Privilege='%s' WHERE AdminID=%d",$FirstName,$LastName,$Email,$Privilege,(int)$AdminID);
 	
	mysqli_query($iConn,$sql) or die(trigger_error(mysqli_error($iConn), E_USER_ERROR));
	
	//feedback success or failure of insert
	if (mysqli_affected_rows($iConn) > 0){
	 $msg= "Admin Updated!";
	 feedback("Successfully Updated!","notice");
	 if($_SESSION["AdminID"] == $AdminID)
	 {#this is me!  update current session info:
		$_SESSION["Privilege"] = $Privilege;
	 	$_SESSION["FirstName"] = $FirstName;
	 }
	}else{
	 	feedback("Data NOT Updated! (or not changed from original values)");
	}
	
	get_header();
	echo '
		<div align="center"><h3>Edit Administrator</h3></div>
		<div align="center"><a href="' . $config->adminReset . '">Edit More</a></div>
		<div align="center"><a href="' . $config->adminDashboard . '">Exit To Admin</a></div>
		';	
	get_footer();
   
}

?>
