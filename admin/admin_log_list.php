<?php
/**
 * admin_log_list.php works with admin_log_view.php to 
 * view & delete log files 
 *
 * @package nmAdmin
 * @author Bill Newman <williamnewman@gmail.com>
 * @version 2.014 2012/06/09
 * @link http://www.newmanix.com/
 * @license https://www.apache.org/licenses/LICENSE-2.0
 * @see admin_log_view.php 
 * @todo Add intrusion detection and/or activity log files
 */
 
require '../inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials
$config->titleTag = 'Log Files'; #Fills <title> tag. If left empty will fallback to $config->titleTag in config_inc.php
$config->metaRobots = 'no index, no follow';#never index admin pages  

//END CONFIG AREA ----------------------------------------------------------
get_header(); #defaults to theme header or header_inc.php
echo '
<h3 align="center">Admin Logs</h3>
<p>Below are (potentially) a list of log files, for example error, benchmarking, activity or intrusion detection 
log files.  View the file name for a hint as to which type of file you are viewing. 
<p>The files also have dates embedded into their file names to see when they occurred. 
If you see no log file for a particular date, no logged items were detected, or the log file 
was deleted previously.</p>
<p>Once the files are deleted they can\'t be recovered.</p>
';
if(isset($_GET['msg']))
{//feedback is provided - perhaps data was entered improperly
	switch($_GET['msg'])
	{
		case 1:
			$feedback = "log file deleted";
			break;
		default:
			$feedback = "";	
	}	
}else{//no feedback
	$feedback = "";	
}
if($feedback != ""){echo '<div align="center"><h3><font color="red">' . $feedback . '</font></h3></div>';} #Fill out feedback HTML	
$dir = opendir(LOG_PATH);#open log directory
$foundFile = FALSE;
echo '<ul>';

while ($read = readdir($dir))
{#read each file that is not a pointer to other folders
	if ($read!='.' && $read!='..')
	{#create a link to view each file
		echo '<li><a href="' . 	ADMIN_PATH  . 'admin_log_view.php?f=' . $read . '">' . $read . '</a></li>';
		$foundFile = TRUE;
	}
}
echo '</ul>';

if(!$foundFile){
	echo '<div align="center"><h3><font color="red">No log files found</font></h3></div>';		
}

closedir($dir);#close log folder 

get_footer(); #defaults to theme footer or footer_inc.php
?>
