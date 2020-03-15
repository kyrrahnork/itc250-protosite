<?php 
/**
 * SmallPark_header_inc.php provides structural HTML for the 'top' of our site pages
 *
 * Used with SmallPark_footer_inc.php to create 'SmallPark' open source theme
 *
 * @package nmCommon
 * @author Bill Newman <williamnewman@gmail.com>
 * @version 2.091 2011/06/17
 * @link http://www.newmanix.com/
 * @license https://www.apache.org/licenses/LICENSE-2.0
 * @see get_header() 
 * @see meta_inc.php
 * @see SmallPark_footer_inc.php  
 * @todo none
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php include INCLUDE_PATH . 'meta_inc.php'; ?>
	<link href="<?=THEME_PATH; ?>include/SmallPark_style.css" rel="stylesheet" type="text/css" />
	<style type="text/css">
	 .somethingcouldgohere {}
	</style>
</head> 
<body>
<div id="container">
	<div id="header">
	  <h1><?=$config->banner;?></h1>
	  <p>Small Park Theme</p>
	</div>
	<div id="navigation">
		 
		<ul id="navlist">
			<?=$config->sidebar1; ?>	
			<?php 
				echo makeLinks($config->nav1,'<li>','</li>'); #link arrays are created in config_inc.php file
			?>	
			<li
				<p><strong>A tiny service announcement.</strong><br />
				Put tidbits of information or pictures in this small yet useful area. This area is part of this theme!
				</p>
			</li>
		</ul>
	</div>
	<div id="sidebar">
		<?=$config->sidebar2; ?>
	</div>
	<div id="content">
		<br />
		<?=showFeedback();?>
	<!-- End of header. -->
