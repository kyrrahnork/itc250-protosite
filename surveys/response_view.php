<?php
/**
 * response_test.php shows an entire response, after it has been created
 * 
 * Will attempt to create a response 'object' to store user entered response data.
 *
 * This is a test page to prove the concept of storage of Response data, with  
 * internal Question, Answer and Choice object data
 * 
 * @package SurveySez
 * @author William Newman
 * @version 2.1 2015/05/28
 * @link http://newmanix.com/ 
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @see Response.php 
 * @see Choice.php
 */

require '../inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials
spl_autoload_register('MyAutoLoader::NamespaceLoader');//required to load SurveySez namespace objects

if(isset($_GET['id']) && (int)$_GET['id'] > 0){#proper data must be on querystring
	 $myID = (int)$_GET['id']; #Convert to integer, will equate to zero if fails
}else{
	myRedirect(VIRTUAL_PATH . "surveys/index.php");
} 
$myResponse = new SurveySez\Response($myID);
if($myResponse->isValid)
{
	$PageTitle = $myResponse->Title . " Survey Response!";
}else{
	$PageTitle = THIS_PAGE; #use constant 
}
$config->titleTag = $PageTitle;
#END CONFIG AREA ---------------------------------------------------------- 

get_header(); # defaults to header_inc.php
?>
<h2><?=THIS_PAGE;?></h2>
<p>This is test page for the response object.  (SurveySez version 2)</p>
<p>It features the new Response & Choice Objects</p>
<p>It is a model intended to be changed to response_view.php, with the ID of a response to be passed in via the QueryString</p>
<?php

if($myResponse->isValid)
{# check to see if we have a valid SurveyID
	echo "Survey Title: <b>" . $myResponse->Title . "</b><br />";  # show data on page
	echo "Date Taken: " . $myResponse->DateTaken . "<br />";
	echo "Survey Description: " . $myResponse->Description . "<br />";
	echo "Number of Questions: " .$myResponse->TotalQuestions . "<br /><br />";
	echo $myResponse->showChoices() . "<br />";	# showChoices method shows all questions, and selected answers (choices) only!
	unset($myResponse);  # destroy object & release resources
}else{
	echo "Sorry, no such response!";	
}

get_footer(); #defaults to footer_inc.php

?>