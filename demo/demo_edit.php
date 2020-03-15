<?php
/**
 * demo_edit.php is a single page web application that allows us to select a customer
 * and edit their data
 *
 * This page is based on demo_postback.php as well as first_crud.php, which is part of the 
 * nmPreload package
 *
 * Any number of additional steps or processes can be added by adding keywords to the switch 
 * statement and identifying a hidden form field in the previous step's form:
 *
 *<code>
 * <input type="hidden" name="act" value="next" />
 *</code>
 * 
 * The above code shows the parameter "act" being loaded with the value "next" which would be the 
 * unique identifier for the next step of a multi-step process
 *
 * @package nmCommon
 * @author Bill Newman <williamnewman@gmail.com>
 * @version 1.12 2012/02/27
 * @link http://www.newmanix.com/
 * @license https://www.apache.org/licenses/LICENSE-2.0
 * @todo add more complicated checkbox & radio button examples
 */

# '../' works for a sub-folder.  use './' for the root  
require '../inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials
 
/*
$config->metaDescription = 'Web Database ITC281 class website.'; #Fills <meta> tags.
$config->metaKeywords = 'SCCC,Seattle Central,ITC281,database,mysql,php';
$config->metaRobots = 'no index, no follow';
$config->loadhead = ''; #load page specific JS
$config->banner = ''; #goes inside header
$config->copyright = ''; #goes inside footer
$config->sidebar1 = ''; #goes inside left side of page
$config->sidebar2 = ''; #goes inside right side of page
$config->nav1["page.php"] = "New Page!"; #add a new page to end of nav1 (viewable this page only)!!
$config->nav1 = array("page.php"=>"New Page!") + $config->nav1; #add a new page to beginning of nav1 (viewable this page only)!!
*/

//END CONFIG AREA ----------------------------------------------------------

# Read the value of 'action' whether it is passed via $_POST or $_GET with $_REQUEST
if(isset($_REQUEST['act'])){$myAction = (trim($_REQUEST['act']));}else{$myAction = "";}

switch ($myAction) 
{//check 'act' for type of process
	case "edit": //2) show first name change form
	 	editDisplay();
	 	break;
	case "update": //3) Change customer's first name
		updateExecute();
		break; 
	default: //1)Select Customer from list
	 	selectFirst();
}

function selectFirst()
{//Select Customer
	global $config;
	$config->loadhead .= '<script type="text/javascript" src="' . VIRTUAL_PATH . 'include/util.js"></script>
	<script type="text/javascript">
			function checkForm(thisForm)
			{//check form data for valid info
				if(empty(thisForm.CustomerID,"Please Select a Customer.")){return false;}
				return true;//if all is passed, submit!
			}
	</script>
	';
	get_header();
	echo '<h3 align="center">' . smartTitle() . '</h3>';

	$sql = "select CustomerID,FirstName,LastName,Email from test_Customers";
	$result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));
	if (mysqli_num_rows($result) > 0)//at least one record!
	{//show results
		echo '<form action="' . THIS_PAGE . '" method="post" onsubmit="return checkForm(this);">';  //TWO COPIES OF THIS LINE IN ORIG!!
		echo '<table align="center" border="1" style="border-collapse:collapse" cellpadding="3" cellspacing="3">';
		echo '<tr>
				<th>CustomerID</th>
				<th>First Name</th>
				<th>Last Name</th>
				<th>Email</th>
			</tr>
			';
		while ($row = mysqli_fetch_assoc($result))
		{//dbOut() function is a 'wrapper' designed to strip slashes, etc. of data leaving db
			echo '<tr>
					<td>
				 	<input type="radio" name="CustomerID" value="' . (int)$row['CustomerID'] . '">'
				     . (int)$row['CustomerID'] . '</td>
				    <td>' . dbOut($row['FirstName']) . '</td>
				    <td>' . dbOut($row['LastName']) . '</td>
				    <td>' . dbOut($row['Email']) . '</td>
				</tr>
				';
		}
		echo '<input type="hidden" name="act" value="edit" />';
		echo '<tr>
				<td align="center" colspan="4">
					<input type="submit" value="Choose Customer!"></em>
				</td>
			  </tr>
			  </table>
			  </form>
			  ';
	}else{//no records
      echo '<div align="center"><h3>Currently No Customers in Database.</h3></div>';
	}
	@mysqli_free_result($result); //free resources
	get_footer();
}

function editDisplay()
{# shows details from a single customer, and preloads their first name in a form.
	global $config;
	if(!is_numeric($_POST['CustomerID']))
	{//data must be alphanumeric only	
		feedback("id passed was not a number. (error code #" . createErrorCode(THIS_PAGE,__LINE__) . ")","error");
		myRedirect(THIS_PAGE);
	}


	$myID = (int)$_POST['CustomerID'];  //forcibly convert to integer

	$sql = sprintf("select CustomerID,FirstName,LastName,Email from test_Customers WHERE CustomerID=%d",$myID);
	$result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));
	if(mysqli_num_rows($result) > 0)//at least one record!
	{//show results
		while ($row = mysqli_fetch_array($result))
		{//dbOut() function is a 'wrapper' designed to strip slashes, etc. of data leaving db
		     $Name = dbOut($row['FirstName']) . ' ' . dbOut($row['LastName']);
		     $First = dbOut($row['FirstName']);
		     $Last = dbOut($row['LastName']);
		     $Email = dbOut($row['Email']);
		}
	}else{//no records
      //feedback issue to user/developer
      feedback("No such customer. (error code #" . createErrorCode(THIS_PAGE,__LINE__) . ")","error");
	  myRedirect(THIS_PAGE);
	}
	
	$config->loadhead .= '
	<script type="text/javascript" src="' . VIRTUAL_PATH . 'include/util.js"></script>
	<script type="text/javascript">
		function checkForm(thisForm)
		{//check form data for valid info
			if(empty(thisForm.FirstName,"Please Enter Customer\'s First Name")){return false;}
			if(empty(thisForm.LastName,"Please Enter Customer\'s Last Name")){return false;}
			if(!isEmail(thisForm.Email,"Please Enter a Valid Email")){return false;}
			return true;//if all is passed, submit!
		}
	</script>';
	
	get_header();
	echo '<h3 align="center">' . smartTitle() . '</h3>
	<h4 align="center">Update Customer\'s Name</h4>
	<p align="center">Customer: <font color="red"><b>' . $Name . '</b>
	 Email: <font color="red"><b>' . $Email . '</b></font> 
	<form action="' . THIS_PAGE . '" method="post" onsubmit="return checkForm(this);">
	<table align="center">
	   <tr><td align="right">First Name</td>
		   	<td>
		   		<input type="text" name="FirstName" value="' .  $First . '">
		   		<font color="red"><b>*</b></font> <em>(alphanumerics & punctuation)</em>
		   	</td>
	   </tr>
	   <tr><td align="right">Last Name</td>
		   	<td>
		   		<input type="text" name="LastName" value="' .  $Last . '">
		   		<font color="red"><b>*</b></font> <em>(alphanumerics & punctuation)</em>
		   	</td>
	   </tr>
	   <tr><td align="right">Email</td>
		   	<td>
		   		<input type="text" name="Email" value="' .  $Email . '">
		   		<font color="red"><b>*</b></font> <em>(valid email only)</em>
		   	</td>
	   </tr>
	   <input type="hidden" name="CustomerID" value="' . $myID . '" />
	   <input type="hidden" name="act" value="update" />
	   <tr>
	   		<td align="center" colspan="2">
	   			<input type="submit" value="Update Info!"><em>(<font color="red"><b>*</b> required field</font>)</em>
	   		</td>
	   </tr>
	</table>    
	</form>
	<div align="center"><a href="' . THIS_PAGE . '">Exit Without Update</a></div>
	';
	@mysqli_free_result($result); //free resources
	get_footer();
	
}

function updateExecute()
{
	if(!is_numeric($_POST['CustomerID']))
	{//data must be alphanumeric only	
		feedback("id passed was not a number. (error code #" . createErrorCode(THIS_PAGE,__LINE__) . ")","error");
		myRedirect(THIS_PAGE);
	}
	
	
	

	$iConn = IDB::conn();//must have DB as variable to pass to mysqli_real_escape() via iformReq()
	
	
	$redirect = THIS_PAGE; //global var used for following formReq redirection on failure

	$CustomerID = iformReq('CustomerID',$iConn); //calls mysqli_real_escape() internally, to check form data
	$FirstName = strip_tags(iformReq('FirstName',$iConn));
	$LastName = strip_tags(iformReq('LastName',$iConn));
	$Email = strip_tags(iformReq('Email',$iConn));
	
	//next check for specific issues with data
	if(!ctype_graph($_POST['FirstName'])|| !ctype_graph($_POST['LastName']))
	{//data must be alphanumeric or punctuation only	
		feedback("First and Last Name must contain letters, numbers or punctuation","warning");
		myRedirect(THIS_PAGE);
	}
	
	
	if(!onlyEmail($_POST['Email']))
	{//data must be alphanumeric or punctuation only	
		feedback("Data entered for email is not valid","warning");
		myRedirect(THIS_PAGE);
	}

    //build string for SQL insert with replacement vars, %s for string, %d for digits 
    $sql = "UPDATE test_Customers set  
    FirstName='%s',
    LastName='%s',
    Email='%s'
     WHERE CustomerID=%d"
     ; 
     
     
     
     
    # sprintf() allows us to filter (parameterize) form data 
	$sql = sprintf($sql,$FirstName,$LastName,$Email,(int)$CustomerID);

	@mysqli_query($iConn,$sql) or die(trigger_error(mysqli_error($iConn), E_USER_ERROR));
	#feedback success or failure of update
	if (mysqli_affected_rows($iConn) > 0)
	{//success!  provide feedback, chance to change another!
	 feedback("Data Updated Successfully!","success");
	 
	}else{//Problem!  Provide feedback!
	 feedback("Data NOT changed!","warning");
	}
	myRedirect(THIS_PAGE);
}

