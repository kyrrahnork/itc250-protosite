<?php
/**
 * index.php is a model for largely static PHP pages 
 *
 * @package nmCommon
 * @author Bill Newman <williamnewman@gmail.com>
 * @version 2.091 2011/06/17
 * @link http://www.newmanix.com/
 * @license https://www.apache.org/licenses/LICENSE-2.0
 * @see config_inc.php 
 * @todo none
 */
 
require 'inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials
$config->titleTag = THIS_PAGE; #Fills <title> tag. If left empty will fallback to $config->titleTag in config_inc.php

//below you can add a link to a unique page to the existing nav as follows
//$config->nav1 = array("aboutus.php"=>"About Us") + $config->nav1; 
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

# END CONFIG AREA ---------------------------------------------------------- 

get_header(); #defaults to theme header or header_inc.php
?>
<div class="jumbotron" style="margin-top:.5em;">
	<h1><?=$config->banner;?></h1>
	<p><em><?=$config->slogan;?></em></p>
	<a href="http://www.bootswatch.com/" target="_blank" class="btn btn-primary btn-lg">Learn more</a>
</div>
<p>Here are a few more links to demo files that can be used as starting points when building web applications:</p>
<p><a href="<?=VIRTUAL_PATH;?>demo/demo_shared.php" target="_blank">demo_shared</a>: A  singleton class based mysqli database (shared) connection application using the Customers table. Best starting point for a non-specific database enabled application </p>
<p><a href="<?=VIRTUAL_PATH;?>demo/demo_postback.php">postback</a>: A vanilla postback switch based application with no specific code other than submittal via POST. Use for building complex postback applications. Compare to <strong>demo_edit</strong> to see what this file can be used to create. </p>
<p><a href="<?=VIRTUAL_PATH;?>demo/demo_list_pager.php" target="_blank">demo_list_pager</a>: A &quot;muffin&quot; example that incorporates the Pager class into a  list/view application. Best starting point for a List/View app </p>
<p><a href="<?=VIRTUAL_PATH;?>demo/demo_add.php" target="_blank">demo_add</a>: A postback switch based application for adding new records to the Customers table.</p>
<p><a href="<?=VIRTUAL_PATH;?>demo/demo_edit.php" target="_blank">demo_edit</a>: A postback switch based application for editing existing records in the Customers table</p>
<p>&nbsp; </p>
<?php

//add a benchmarking note as follows:
//$config->benchNote = "Test From Index File!";

get_footer(); #defaults to theme header or footer_inc.php
?>
