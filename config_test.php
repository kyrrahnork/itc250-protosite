<?php
/**
 * config_test.php identifies paths & error handling issues for setting up PHP applications
 *
 * This file is saved as index.php and placed in the root of the server space.  Once the 
 * configuration data that is displayed is used, rename index_new.php to index.php to replace it.
 * 
 * When placed in the APPLICATION ROOT (Where you want this application to reside) inside 
 * the WEB ROOT, this file will attempt to expose the PHYSICAL_PATH & VIRTUAL_PATH	data that 
 * can be used for the constants by those names in config_inc.php
 *
 * It would be dangerous to leave this file in the web root, so remember to remove it, after 
 * you are done determining your paths.
 *
 * Keep the file to check for configuration info on your next host
 *
 * Version 2.06 includes more instructions, changes colors, etc.
 *
 * @package nmCommon
 * @author Bill Newman <williamnewman@gmail.com>
 * @version 2.091 2011/06/17
 * @link http://www.newmanix.com/ 
 * @license https://www.apache.org/licenses/LICENSE-2.0
 * @see config_inc.php
 * @see index_new.php 
 * @todo none
 */
?>
<html>
<head>
	<style type="text/css">
	 .red {color:#FF0000;font-family:'Courier New',Courier,Verdana;font-weight:bold;font-size:120%;}
	 .blue {color:#0000FF;font-family:'Courier New',Courier,Verdana;font-weight:bold;font-size:120%;}
	 .green {color:#347235;font-family:'Courier New',Courier,Verdana;font-weight:bold;font-size:120%;}
	</style>
	<title>config_test.php</title>
	<meta name="robots" content="no index, no follow" />
</head>
<script type="text/javascript">
virtual = location.href.substring(0,location.href.lastIndexOf("/") + 1);
</script>
<body>
<p><b>config_test.php</b><strong>/index.php</strong></p>
<p>This file will attempt to expose the <strong>PHYSICAL_PATH</strong> & <strong>VIRTUAL_PATH</strong> to your <strong>APPLICATION ROOT</strong> that 
can be used to fill in the constants by those names required in <strong>config_inc.php</strong> (located initially in the <strong>inc_0700</strong> folder) </p>
<p>
  <?php
//identify physical & virtual paths:

$thisFile = basename($_SERVER['PHP_SELF']);
$fileLength = strlen($thisFile);

$physical = $_SERVER['SCRIPT_FILENAME'];
$physical = substr($physical,0,strlen($physical) - $fileLength);

$physical2 = __FILE__;
$physical2 = substr($physical2,0,strlen($physical2) - $fileLength);

if($physical == $physical2)
{#they match.  Yowza!
	echo 'Likely <b>PHYSICAL_PATH</b> (verified 2 ways, via SCRIPT_FILENAME and __PATH__): <br /><span class="green">' . $physical . '</span><br />';
	echo 'Use the above path (in green) as the <b>PHYSICAL_PATH</b> constant inside <b>config_inc.php</b>.<br />';
}else{#different. - try each!
	echo 'Likely <b>PHYSICAL_PATH</b> (via SCRIPT_FILENAME): <span class="green">' . $physical . '</span><br />';
	echo 'Alternative (possible) <b>PHYSICAL_PATH</b> (via __PATH__): <span class="green">' . $physical2 . '</span><br />';
	echo 'You may need to try <b>both</b> of the above paths above (in green) as the <b>PHYSICAL_PATH</b> constant inside <b>config_inc.php</b>';
}
echo '<br />';
?>
  <b>VIRTUAL_PATH</b>: <br />
  <span class="blue">
  <script type="text/javascript">document.write(virtual);</script>
  </span><br />
Use the above path (in blue) as the <b>VIRTUAL_PATH</b> constant inside <b>config_inc.php</b>.</p>
<p><strong>After Editing config_inc.php:</strong> Once you have edited <strong>config_inc.php</strong>, come back to this page and try the following link:</p>
<p><a href="index_new.php">index_new.php</a></p>
<p>If the page shows a <strong>complete</strong> page with a header and footer, likely your <strong>config_inc.php</strong> file was edited properly! If you see error messages, read the error message and prepare to re-edit your <strong>config_inc.php</strong> file.</p>
<p><strong>Once All is Working: </strong>Once the above link shows a complete error free page, rename the <strong>index_new.php</strong> file to<strong> index.php</strong>. That will replace <strong>this</strong> file as the index page for your application. A backup of this file is named <strong>config_test.php</strong>, and its in the application root as well (same folder). <strong>This file should be moved from the web space into a safe location, unreachable via the web. </strong></p>
<p><strong>Error Messages:</strong> This file also attempts to show you  whether or not errors are currently visible in this space. If errors are not currently visible, this page will offer advice on what to do next (below). </p>
<?php
if(ini_get('display_errors')!='' && ini_get('display_errors') == 1)
{#errors are visible!
	echo '<b>display_errors</b> is currently: <span class="green">ON</span><br />';
	echo 'You <b>are</b> currently able to see errors in this folder.  You should be able to develop as normal with feedback from PHP error reporting.<br />';
}else{#errors NOT visible!
	echo 'By default, <b>display_errors</b> is: <span class="red">OFF</span><br />';
	echo 'By default you should <b>not</b> be able to view PHP errors from this folder.<br />';
	#ini_set() usually doesn't work to turn display_errors on, and it returns a false positive - skip it!
	echo 'However, you may be able to place a custom <b>php.ini</b> file in the root of your application space, which could contain ';
	echo 'a single line of code:<br /><br />';
	echo '<b>display_errors = 1</b><br /><br />';
	echo 'Not all hosting companies allow a custom <b>php.ini</b> however. If that is true, you may need to use the <b>error log</b> ';
	echo 'to view errors.  It is worth researching the FAQ then contacting the hosting company to find out how they recommend you ';
	echo 'troubleshoot PHP on their servers.<br />';
}
echo '<br />';
if(ini_get('log_errors')!='' & ini_get('log_errors') == 1)
{#error logging is turned ON
	echo 'By default <b>error logging (log_errors)</b> is turned: <span class="green">ON</span><br />';
}else{#error logging turned OFF
	echo 'By default <b>error logging (log_errors)</b> is turned: <span class="red">OFF</span><br /><br />';
	ini_set('log_errors',1); #attempt to turn on error logging
	if(ini_get('log_errors')!='' && ini_get('log_errors') == 1)
	{#apparently able to turn on error logging
		echo '<br />Able to turn on <b>error logging</b> via <b>ini_set()</b>: <span class="green">TRUE</span><br />';
		echo 'You can turn on error logging on a page by page basis by using <b>ini_set("log_errors",1)</b>.<br />';
		echo 'You will likely also need to set the error log path (see below).<br /><br />';
	}else{
		echo '<br />Able to turn on <b>error logging</b> via <b>ini_set()</b>: <span class="red">FALSE</span><br />';
		echo "Since we don't seem to be able to turn error logging on, you may wish to check with the hosting company to see if error logging can be enabled.<br />";
	}
}
#here we'll have a separate check to see if the error_log location can be changed - 
if(ini_get('log_errors')!='' && ini_get('log_errors') == 1)
{#error logging is currently on, either by default or because we set it that way
	if(ini_get('error_log') == 0)
	{# no error log path set.
		echo 'Default Error logging path: <span class="red">NONE</span><br />';
		echo 'There is <b>no default</b> error_log path set to view PHP errors.<br />';
		$origPath = ini_get('error_log');#attempt to set 'test' error log path
		ini_set("error_log", $physical2 . "error_log/test_error_log.txt"); #attempt to set 'test' error log path
		if(ini_get('error_log') != 0  && ini_get('error_log') != $origPath)
		{#unable to set error log path
			echo '<br />Able to set an <b>error log path</b> via <b>ini_set()</b>: <span class="red">FALSE</span><br />';
			echo 'We attempted to setup an error_log path and failed via <b>ini_set()</b>.<br />';
			echo 'Check with the hosting company if you wish to enable error logging.<br />';
		}else{#error log path can be set manually
			echo '<br />Able to set an <b>error log path</b> via <b>ini_set()</b>: <span class="green">TRUE</span><br />';
			echo 'We <b>were successful</b> at setting an error log path manually.  This can be done by you by ';
			echo 'using <b>ini_set("error_log","path/to/error/log/file")</b> in which you identify an existing physical path to an error file.<br />';
			echo 'The file must be writeable and is best kept <b>outside</b> the web root, or at least set to <b>0700</b> to keep from being publicly viewable.';
			echo ' You may <b>not</b> need to do this if you use the <b>config_inc.php</b> file handles error logging.<br />';
		}
	}else{
		echo 'Default error logging path<b>' . ini_get('error_log') . '</b><br />';
		echo 'You may currently view PHP errors at the location above, by default.<br /><br />';
		$origPath = ini_get('error_log');#attempt to set 'test' error log path
		ini_set("error_log", $physical2 . "error_log/test_error_log.txt"); #attempt to set 'test' error log path
		if(ini_get('error_log') != 0  && ini_get('error_log') != $origPath)
		{#able to change error log path
			echo 'Able to change <b>error log path</b> via <b>ini_set()</b>: <span class="green">TRUE</span><br />';
			echo 'We <b>were successful</b> at changing the error log path manually.  This can be done by you by ';
			echo 'using <b>ini_set("error_log","path/to/error/log/file")</b> in which you identify an existing physical path to an error file.<br />';
			echo ' You may <b>not</b> need to do this as the <b>config_inc.php</b> file handles error logging.<br />';
		}else{#unable to change error log path
			echo 'Able to change the <b>error log path</b> via <b>ini_set()</b>: <span class="red">FALSE</span><br />';
			echo 'We attempted to change the error_log path and <b>failed</b> via <b>ini_set()</b>.<br />';
			echo 'Check with the hosting company if you wish to change the location of the error log.<br />';
		}
	}
}
echo '<br /><br />Changing <b>any</b> setting using <b>ini_set()</b> must be done on <b>every</b> page (hence our config file)<br />';
?>
<p><strong>Custom php.ini file: </strong>Sometimes the best (and most flexible) results of all come from being allowed to create your own custom <strong>php.ini</strong> file. However this is frequently not supported by shared hosting packages. Check to see if your hosting company supports a custom <strong>php.ini </strong>file. Some folks even compile their own version of PHP, which would then have it's own <strong>php.ini</strong> file. This is sometimes done (although not supported) at Dreamhost, and is what you would do if you had a dedicated server. </p>
</body>
</html>