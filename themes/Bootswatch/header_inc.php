<?php
//theme specific functions
include 'bootswatch_functions.php';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
	<?php include INCLUDE_PATH . 'meta_inc.php'; ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!--Bootstrap themes use style settings to change look and feel -->
    <link rel="stylesheet" href="<?=THEME_PATH;?>css/<?=$config->style;?>" media="screen">
    <link rel="stylesheet" href="<?=THEME_PATH;?>css/bootswatch.min.css">
	<link rel="stylesheet" href="<?=THEME_PATH;?>css/bootswatch-overrides.css">
    <!--[if lt IE 9]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
  </head>
  <body>
     <div class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <a href="<?=VIRTUAL_PATH?>" class="navbar-brand"><?=$config->banner;?></a>
		    <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>
        <div class="navbar-collapse collapse" id="navbar-main">
          <ul class="nav navbar-nav">
			<?php 
				echo bootswatchLinks($config->nav1,'<li>','</li>','<li class="active">'); #link arrays are created in config_inc.php file - see bootswatch_functions.php
			?>
          </ul>
			<?php
				echo bootswatchAdmin();  //add right aligned Admin link - see bootswatch_functions.php
			?>
        </div>
      </div>
    </div>
    <div class="container">
				<?php
				echo bootswatchFeedback();  //feedback on form operations - see bootswatch_functions.php
			?>
