<?php
/**
 * admin_validate.php validation page for access to administrative area
 *
 * Processes form data from $config->adminLogin.php to process administrator login requests.
 * Forwards user to admin_dashboard.php, upon successful login. 
 * 
 * @package nmAdmin
 * @author Bill Newman <williamnewman@gmail.com>
 * @version 2.02 2015/07/06
 * @link http://www.newmanix.com/
 * @license https://www.apache.org/licenses/LICENSE-2.0
 * @see $config->adminLogin.php
 * @see admin_dashboard.php
 * @todo none
 */

require '../inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials  

if (isset($_POST['em']) && isset($_POST['pw'])) 
{//if POST is set, prepare to process form data
	$params = array('em','pw','red');#required fields for login	- true disallows other fields
	if(!required_params($params,true))
	{//abort - required fields not sent
		feedback("Data not properly submitted. (error code #" . createErrorCode(THIS_PAGE,__LINE__) . ")","error");
		myRedirect($config->adminLogin);
		die();
	}
	if(!ctype_graph($_POST['pw']))
	{//data must be alphanumeric or punctuation only	
		feedback("Illegal characters were entered. (error code #" . createErrorCode(THIS_PAGE,__LINE__) . ")","error");
		myRedirect($config->adminLogin);
	}
	if(!onlyEmail($_POST['em']))
	{//login must be a legal email address only	
		feedback("Illegal characters were entered. (error code #" . createErrorCode(THIS_PAGE,__LINE__) . ")","error");
		myRedirect($config->adminLogin);
	}
	// Remove all illegal characters
	$Email = trim($_POST['em']);
	$Email = filter_var($Email, FILTER_SANITIZE_STRING);
	$Password = trim($_POST['pw']);
	$Password = filter_var($Password, FILTER_SANITIZE_EMAIL);
	/*
	$Email = trim($_POST['em']);
	$Email = filter_var($Email, FILTER_SANITIZE_STRING);
	
	*/
	
	//dumpDie($Email,$Password);
	
	//dumpDie($Password);


	
	
	$db = pdo(); # pdo() creates and returns a PDO object
	$sql = "select AdminID,FirstName,Privilege,NumLogins from " . PREFIX . "Admin WHERE Email=? AND AdminPW=SHA(?)";
	$stmt = $db->prepare($sql);
	$stmt->bindValue(1, $Email, PDO::PARAM_STR);
	$stmt->bindValue(2, $Password, PDO::PARAM_STR);
    try {$stmt->execute();} catch(PDOException $ex) {trigger_error($ex->getMessage(), E_USER_ERROR);}
    $result = $stmt->fetchAll();
	if(count($result) > 0)
	{#there are records - present data
		startSession(); #wrapper for session_start()
		foreach($result as $row)
		{# pull data from associative array
			$AdminID = (int)$row["AdminID"];  # use (int) cast to for conversion to integer
			$_SESSION["AdminID"] = $AdminID; # create session variables to identify admin
			$_SESSION["FirstName"] = dbOut($row["FirstName"]);  #use dbOut() to clean strings, replace escaped quotes
			$_SESSION["Privilege"] = dbOut($row["Privilege"]);
			$NumLogins = (int)$row["NumLogins"];
			$NumLogins+=1;  # increment number of logins, then prepare to update record!
		}
		//update number of logins, last login
		$sql = "UPDATE " . PREFIX . "Admin set NumLogins=?, LastLogin=NOW()  WHERE AdminID=?";
		$stmt = $db->prepare($sql);
		$stmt->bindValue(1, $NumLogins, PDO::PARAM_INT);
		$stmt->bindValue(2, $AdminID, PDO::PARAM_INT);
		try {$stmt->execute();} catch(PDOException $ex) {trigger_error($ex->getMessage(), E_USER_ERROR);}
		feedback("Login Successful!", "notice");
		if(isset($_SESSION['red']) && $_SESSION['red'] != "")
		{#check to see if we'll be redirecting to a requesting page
			$red = $_SESSION['red']; #redirect back to original page
			$_SESSION['red'] == ''; #clear session var
			myRedirect($red);
		}else{
			myRedirect($config->adminDashboard);# successful login! Redirect to admin page
		} 
	}else{# failed login, redirect
	    feedback("Login and/or Password are incorrect.","warning");
		myRedirect($config->adminLogin);
	}
	unset($result,$db);//clear resources
}else{
	feedback("Required data not sent. (error code #" . createErrorCode(THIS_PAGE,__LINE__) . ")","error");
	myRedirect($config->adminLogin);	
}		
