<?php
/**
 * demo_list_upload.php along with demo_view_upload.php demonstrates
 * records paging with a list/view application
 *
 * This version includes thumbnail support, added in the nmUpload package, 
 * and Paging, added in the nmPager package
 * 
 * @package nmUpload
 * @author Bill Newman <williamnewman@gmail.com>
 * @version 2.031 2012/03/11
 * @link http://www.newmanix.com/
 * @license https://www.apache.org/licenses/LICENSE-2.0
 * @see demo_view_upload.php
 * @see upload_form.php
 * @see upload_execute.php
 * @todo none
 */ 

 # '../' works for a sub-folder.  use './' for the root
require '../inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials  
 
# SQL statement
$sql = "select MuffinName, MuffinID, Price from test_Muffins";

#Fills <title> tag. If left empty will default to $config->TitleTag in config_inc.php  
$config->TitleTag = 'Muffins made with love & PHP in Seattle';

#Fills <meta> tags.  Currently we're adding to the existing meta tags in config_inc.php
$config->metaDescription = 'Seattle Central\'s ITC280 Class Muffins are made with pure PHP! ' . $config->metaDescription;
$config->metaKeywords = 'Muffins,PHP,Fun,Bran,Regular,Regular Expressions,'. $config->metaKeywords;
$config->metaRobots = ''; #use default in config_inc.php - set to 'no index,no follow' during development

/*
$config->loadhead = ''; #load page specific JS
*/

#reference images for pager
$prev = '<img src="' . VIRTUAL_PATH . 'images/arrow_prev.gif" border="0" />';
$next = '<img src="' . VIRTUAL_PATH . 'images/arrow_next.gif" border="0" />';

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
<h3 align="center"><?=smartTitle();?></h3>

<p>This page, along with <b>demo_view_upload.php</b>, demonstrate a List/View web application.</p>
<p>This version incorporates image uploads, added in the nmUpload package.</p>
<p>When logged in as an administrator, you can now select an individual item, view it, and upload an image into place via a 
'peek-a-boo' link on the view page.</p>
<p>This page is the entry point of the application, meaning this page gets a link on your web site.</p>
<p>Use <b>demo_list_upload.php</b> and <b>demo_view_upload.php</b> as a starting point for building your own List/View web application!</p>

<?php
$myPager = new Pager(2,'',$prev,$next,''); # Create instance of new 'pager' class
$sql = $myPager->loadSQL($sql);  #load SQL, add offset
   
# connection comes first in mysqli (improved) function
$result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));

if(mysqli_num_rows($result) > 0)
{#records exist - process
    if($myPager->showTotal()==1){$itemz = "muffin";}else{$itemz = "muffins";}  //deal with plural
    echo '<div align="center">We have ' . $myPager->showTotal() . ' ' . $itemz . '!</div>';
	while($row = mysqli_fetch_assoc($result))
	{# process each row
    	echo '<div align="center">';
		echo '<img src="' . VIRTUAL_PATH . 'upload/m' . dbOut($row['MuffinID']) . '_thumb.jpg" />';
		echo '<a href="' . VIRTUAL_PATH . 'demo/demo_view_upload.php?id=' . dbOut($row['MuffinID']) . '">' . dbOut($row['MuffinName']) . '</a>';
		echo ' <i>only</i> <font color="red">$' . dbOut($row['Price'])  . '</font></div>';     
	} 
	echo $myPager->showNAV(); # show paging nav, only if enough records	
}else{#no records
    echo "<div align=center>What! No muffins?  There must be a mistake!!</div>";	
}
@mysqli_free_result($result);

get_footer(); #defaults to theme footer or footer_inc.php
?>
