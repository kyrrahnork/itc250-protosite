<?php
/**
 * custom_inc.php stores custom functions specific to your application
 * 
 * Keeping common_inc.php clear of your functions allows you to upgrade without conflict
 * 
 * @package nmCommon
 * @author Bill Newman <williamnewman@gmail.com>
 * @version 2.091 2011/06/17
 * @link http://www.newmanix.com/  
 * @license https://www.apache.org/licenses/LICENSE-2.0
 * @todo add safeEmail to common_inc.php
 */
 
/**
 * Place your custom functions below so you can upgrade common_inc.php without trashing 
 * your custom functions.
 *
 * An example function is commented out below as a documentation example  
 *
 * View common_inc.php for many more examples of documentation and starting 
 * points for building your own functions!
 */ 

/**
 * Checks data for alphanumeric characters using PHP regular expression.  
 *
 * Returns true if matches pattern.  Returns false if it doesn't.   
 * It's advised not to trust any user data that fails this test.
 *
 * @param string $str data as entered by user
 * @return boolean returns true if matches pattern.
 * @todo none
 */

//stdClassSnippets.php
//these are SNIPPETS, not a file to be placed into a running server!


//START SNIPPET #2-------------------------------

function buildCountries()
{//copy country data into an object
	$sql = "select Value,Country from test_Countries order by Country asc";
	$result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));
	while($row = mysqli_fetch_assoc($result))
	{# pull data from db array
		$values[] = dbOut($row['Value']);
		$countries[] = dbOut($row['Country']);
	}
	$returnObj = new stdClass;
	$returnObj->values = $values;
	$returnObj->countries = $countries;
	return $returnObj;
}


//END SNIPPET #2-----------------------------------



//START SNIPPET #5-------------------------------

/**
 * Creates and pre-loads radio, checkbox & options from passed delineated strings
 * v3 adds capability to associate id's & other attribute data to element
 *
 * data is now returned as a string intead of echoed 
 *
 * Pass arrays, or strings of data for value, label and database match to the function 
 * and identify if you wish to create a select option, or a set of 
 * radio buttons or checkboxes.
 *
 * Form elements will be 'pre-loaded' with database values ($dbStr) so a 
 * user can change their selection, or see their original choice. 
 * 
 * <code>
 * $valuStr = "1,2,3,4,5";
 * $dbStr = "1,2,5";  
 * $lblStr = "chocolate,bananas,nuts,caramel,butterscotch";
 * $attribs = 'id="blah"'; //attribs added to element
 * echo createSelect3("checkbox","Toppings",$valuStr,$dbStr,$lblStr,",");
 * </code>
 *
 * @param string $elType type of input element created, 'select', 'radio' or 'checkbox'
 * @param string $elName name of element
 * @param string/array $valuArray delimiter separated string of values to choose
 * @param string/array $dbArray delimiter separated string of DB items to match
 * @param string/array $lblArray delimiter separated string of labels to view
 * @param string $char delimiter, default is comma
 * @param string $attribs allows placement of attribute to element, ID, class, JS hook
 * @return void string is printed within function
 * @todo none
 */

function createSelect3($elType,$elName,$valuArray,$dbArray,$lblArray,$char=',',$attribs='', $alignment ='vertical')
{
if(!is_array($valuArray)){$valuArray = explode($char,$valuArray);}//if not array, blow it up!	
if(!is_array($dbArray)){$dbArray = explode($char,$dbArray);}  //db values
if(!is_array($lblArray)){$lblArray = explode($char,$lblArray);}  //labels identify
if($attribs!=''){$attribs = ' ' . $attribs;} //add space before attribs
	
$x = 0; $y = 0; $sel = "";//init stuff
   switch($elType)
   {
   case "radio":
   case "checkbox":
        for($x=0;$x<count($valuArray);$x++)
        {
             for($y=0;$y<count($dbArray);$y++)
             {
                   if($valuArray[$x]==$dbArray[$y])
                   {
                        $sel = " checked=\"checked\"";
                   }
             }//y for
              if($alignment == "horizontal")
			  {
				  print "<input type=\"" . $elType . "\" name=\"" . $elName . "\" value=\"" . $valuArray[$x] . "\"" . $sel . $attribs .">" . $lblArray[$x] . " &nbsp; &nbsp;&nbsp;\n"; 
			  }else{
				print "<input type=\"" . $elType . "\" name=\"" . $elName . "\" value=\"" . $valuArray[$x] . "\"" . $sel . $attribs .">" . $lblArray[$x] . "<br>\n";
			  }
		 $sel = "";
        }//x for
        break;
   case "select":
	print '<select name="' . $elName . '"' . $attribs .'>';
        for($x=0;$x<count($valuArray);$x++)
        {
             for($y=0;$y<count($dbArray);$y++)
             {
                   if($valuArray[$x]==$dbArray[$y])
                   {
                       $sel = " selected=\"selected\"";
                   }
             }//y for
              print "<option value=\"" . $valuArray[$x] . "\"" . $sel . ">" . $lblArray[$x] . "</option>\n";
	      $sel = "";
        }//x for
        print "</select>";
        break;
   }
}#end createSelect3()

//END SNIPPET #5-------------------------------


//START SNIPPET #6-------------------------------

/*
Alternate version of createSelect3, which returns a string.  
This version was required to embed into a function that created 
a string to return and would not work with echo built into createSelect
*/
function returnSelect($elType,$elName,$valuArray,$dbArray,$lblArray,$char=',',$attribs='', $alignment ='vertical')
{
if(!is_array($valuArray)){$valuArray = explode($char,$valuArray);}//if not array, blow it up!	
if(!is_array($dbArray)){$dbArray = explode($char,$dbArray);}  //db values
if(!is_array($lblArray)){$lblArray = explode($char,$lblArray);}  //labels identify
if($attribs!=''){$attribs = ' ' . $attribs;} //add space before attribs
$myReturn = ''; //init	
$x = 0; $y = 0; $sel = "";//init stuff
   switch($elType)
   {
   case "radio":
   case "checkbox":
        for($x=0;$x<count($valuArray);$x++)
        {
             for($y=0;$y<count($dbArray);$y++)
             {
                   if($valuArray[$x]==$dbArray[$y])
                   {
                        $sel = " checked=\"checked\"";
                   }
             }//y for
              if($alignment == "horizontal")
			  {
				  $myReturn .= "<input type=\"" . $elType . "\" name=\"" . $elName . "\" value=\"" . $valuArray[$x] . "\"" . $sel . $attribs .">" . $lblArray[$x] . " &nbsp; &nbsp;&nbsp;\n"; 
			  }else{
				$myReturn .= "<input type=\"" . $elType . "\" name=\"" . $elName . "\" value=\"" . $valuArray[$x] . "\"" . $sel . $attribs .">" . $lblArray[$x] . "<br>\n";
			  }
		 $sel = "";
        }//x for
        break;
   case "select":
	$myReturn .= '<select name="' . $elName . '"' . $attribs .'>';
        for($x=0;$x<count($valuArray);$x++)
        {
             for($y=0;$y<count($dbArray);$y++)
             {
                   if($valuArray[$x]==$dbArray[$y])
                   {
                       $sel = " selected=\"selected\"";
                   }
             }//y for
              $myReturn .= "<option value=\"" . $valuArray[$x] . "\"" . $sel . ">" . $lblArray[$x] . "</option>\n";
	      $sel = "";
        }//x for
        $myReturn .= "</select>";
        break;
   }
   return $myReturn;
}#end returnSelect()



//END SNIPPET #6-----------------------------------

