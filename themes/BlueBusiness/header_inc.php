<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

   <head>

	<?php include INCLUDE_PATH . 'meta_inc.php'; ?>
	
	
      <link href="<?=THEME_PATH;?>bluebusiness.css" rel="stylesheet" type="text/css" />

   </head>

   <body>
        <div id="container">

            <div id="container-header">
                <div id="container-name"><?=$config->banner;?></div>
		<div id="container-slogan"><span>&quot;</span><?=$config->slogan;?><span>&quot;</span></div>
            </div>
            
	    <div id="container-eyecatcher">
                <div id="container-navigation">
	                <ul id="navigation">
	                    <?=makeLinks($config->nav1,'<li>','</li>');?>
	                	<!--
		                <li><a href="#">home</a></li>
		                <li><a href="#">products</a></li>
		                <li><a href="#">services</a></li>
		                <li><a href="#">about</a></li>
		                <li><a href="#">contact</a></li>
		                <li><a href="#">sitemap</a></li>
		                -->
	                </ul>
                </div>
            <img src="<?=THEME_PATH;?>images/business_eyecatcher.jpg" alt="Business picture" />
	    </div>

            <div id="container-content">

		<div id="content">
		<?=showFeedback();?>
                <!-- end header -->
