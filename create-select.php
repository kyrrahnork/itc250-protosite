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

echo '<h1>Create Select</h1>';

echo "Please pick your favorite color: <br />";
$valuStr = "red,blue,yellow";
$dbStr = "blue"; 
$lblStr = "Red,Blue,Yellow";
createSelect("radio","color",$valuStr,$dbStr,$lblStr,",");
 
echo "<br />Please pick your favorite lifesaver flavors: <br />";
$valuStr = "cherry,pineapple,orange";
$dbStr = "pineapple,orange"; 
$lblStr = "Cherry,Pineapple,Orange";
createSelect("checkbox","flavor[]",$valuStr,$dbStr,$lblStr,",");
 
echo "<br />Please indicate your State: <br />";
$valuStr = ",CA,OR,WA"; //NOTE THE EMPTY FIRST ELEMENT HERE!
$dbStr = "WA"; 
$lblStr = "Pick A State,California,Oregon,Washington";
createSelect("select","state",$valuStr,$dbStr,$lblStr,",");

get_footer(); #defaults to theme header or footer_inc.php
?>
