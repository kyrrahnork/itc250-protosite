/**
 * util.js stores utility JavaScript functions
 *
 * This file stores utility functions for validating data before form submittal. 
 * On a public page, JS should NOT be the only means of validating data
 *
 * Version 1.1 adds addOnload() function for safe JS function initialization
 *
 * Version 1.2 adds empty() function for all radio/checkbox, select & text required fields
 * 
 * Version 1.3 adds loadElements() function to reload all radio/checkbox, select & text fields 
 * on postback via PHP POST array
 * 
 * @package ITC280
 * @author Bill Newman <williamnewman@gmail.com>
 * @version 1.3 2010/07/20
 * @link http://www.newmanix.com/itc280/ 
 * @license https://www.apache.org/licenses/LICENSE-2.0
 * @todo Create isNumeric(), isSafe() functions for form processing
 */
 
 /**
 * Checks a set of radio buttons, checkboxes, a select or text type form elements for required selection
 *
 * Requires some sort of data entry (any string data, any type) of  
 * input type=text, password or textarea objects.  
 *
 * <code>
 * if(!required(thisForm.myName,"Please Enter Name.")){return false;}
 * </code>
 *
 * While building select elements, The first option of the select must not be a valid option. 
 * View the code sample below to see how the first option is not valid option.
 *
 * <code>
 * <select name="State">
 *   <option value="">Please pick a state</option>
 *   <option value="CA">California</option>
 *   <option value="OR">Oregon</option>
 *   <option value="WA">Washington</option>
 * </select>
 * </code>
 * 
 * @param object $fObj radio button set, checkboxes, select or text type form object
 * @param string $msg feedback to user, based on data required of form element
 * @return true If true, continue to check other items.  If false, do not continue
 * @todo none
 */ 
function empty(fObj,msg)
{//will accept form elements and check for input

	//radio buttons & checkboxes are arrays - their type must be determined by a single element
	if (fObj.length != undefined)
	{//if length is defined, more than one element. Treat as an array
		testType = fObj[0].type;
		isArray = true;
	}else{//if undefined, only one element
	    testType = fObj.type;
	    isArray = false;
	}

	switch(testType)
	{//once the form object type is determined, we can treat elements separately 
		
	    //handle radio & checkbox elements
		case "radio":
		case "checkbox":
			if (isArray)
			{//if length is defined, more than one element. Treat as an array
			    for(x=0; x<fObj.length;x++)
				{
					if(fObj[x].checked){return false;}
				}
			}else{//if undefined, only one element
			    if (fObj.checked){return false;}
			}
			
			alert(msg);
			//focus only works cross browser on first element of array of named elements
			if(isArray){fObj[0].focus();}
			return true;
			break;

		//handle select elements		
		case "select":
			if(fObj.options[0].selected)
			{
				alert(msg);
				fObj.options[0].focus();
				return true;
			}else{
				return false;	
			}
			break;
		
		//handle text, textarea & password elements here
		default:
	       if(fObj.value == "")
		   {
			   alert(msg);
			   fObj.focus();
			   return true;
		   }else{
			   return false;
		   }
	}//end switch
	
}//end empty
 
/**
 * Uses a regular expression to require a valid email
 *
 * <code>
 * if(!isEmail(thisForm.Email,"Please enter a valid Email Address")){return false;}
 * </code>
 * 
 * @param object $fObj input type="text" requiring an email
 * @return true If true, continue to check other items.  If false, do not continue
 * @see isAlpha()
 * @todo none
 */
function isEmail(fObj,msg)
{//Uses regular expression for email check
	var rePattern = /^([a-zA-Z0-9]+([\.+_-][a-zA-Z0-9]+)*)@(([a-zA-Z0-9]+((\.|[-]{1,2})[a-zA-Z0-9]+)*)\.[a-zA-Z]{2,6})$/;
	if(rePattern.test(fObj.value))
	{
		return true;
	}else{
		alert(msg);
		fObj.value = "";
		fObj.focus();
		return false;
	}
}

/**
 * Uses a regular expression to require alphabetic data
 *
 * Requires alphabetic or numerical data for each character.  Will not 
 * accept a space, or any other special character. Good for passwords.
 *
 * <code>
 * if(!isAlpha(thisForm.Password,"Only alphabetic characters are allowed for passwords.")){return false;}
 * </code>
 * 
 * @param object $fObj input type="text" requiring alphabetic data
 * @return true If true, continue to check other items.  If false, do not continue
 * @see isAlphanumeric()
 * @see correctLength() 
 * @todo none
 */
function isAlpha(fObj,msg)
{//Uses regular expression for email check
	var rePattern = /^[a-zA-Z]+$/;
	if(rePattern.test(fObj.value))
	{
		return true;
	}else{
		alert(msg);
		fObj.value = "";
		fObj.focus();
		return false;
	}
}

/**
 * Uses a regular expression to require alphanumeric data
 *
 * Requires alphabetic or numerical data for each character.  Will not 
 * accept a space, or any other special character. Good for passwords.
 *
 * <code>
 * if(!isAlphanumeric(thisForm.Password,"Only alphanumeric characters are allowed for passwords.")){return false;}
 * </code>
 * 
 * @param object $fObj input type="text" requiring alphanumeric data
 * @return true If true, continue to check other items.  If false, do not continue
 * @see isAlpha()
 * @see correctLength() 
 * @todo none
 */
function isAlphanumeric(fObj,msg)
{//Uses regular expression for alphabetic check
	var rePattern = /^[a-zA-Z0-9]+$/;
	if(rePattern.test(fObj.value))
	{
		return true;
	}else{
		alert(msg);
		fObj.value = "";
		fObj.focus();
		return false;
	}
}

/**
 * Ensures minimum & maximum length for text
 *
 * Requires minimum and maximum data entry (any string data, any type) of  
 * input type=text, password or textarea objects.
 *
 * <code>
 * if(!correctLength(thisForm.Password,6,20,"Password does not meet the following requirements:")){return false;}
 * </code>
 * 
 * @param object $fObj input type="text" requiring alphanumeric data
 * @return true If true, continue to check other items.  If false, do not continue
 * @see isAlpha()
 * @see isAlphanumeric() 
 * @todo none
 */
function correctLength(fObj,min,max,msg)
{//Uses regular expression for email check
	var rePattern = /^[a-zA-Z]+$/;
	if((fObj.value.length >= min) && (fObj.value.length <= max ))
	{
		return true;
	}else{
		alert(msg + "\n Minimum characters: " + min + " Maximum characters: " + max);
		fObj.value = "";
		fObj.focus();
		return false;
	}
}

/**
 * Allows safe & convenient way to add multiple JS functions to window.onload
 *
 * Many JS scripts hijack the window.onload function, which is not additive.
 *
 * Therefore many scripts over-write each other, and only one script gets loaded.
 *
 * This solution presents a browser neutral version that will add scripts to 
 * window.onload, and also will not interfere with more primitive hijacks.
 * 
 * @author Marcello Calbucci
 * @link http://haacked.com/archive/2006/04/06/StopTheWindow.OnloadMadness.aspx
 *
 * <code>
 * addOnload(init);
 * </code>
 *
 * IMPORTANT - there are no quotes around the name of the example function (init) above!
 * 
 * @param string myfunc reference to name of initialization function to add to window.onload
 * @return void
 * @todo none
 */
function addOnload(myfunc)
{//addOnload function allows us to attach
	if(window.addEventListener){
		window.addEventListener('load', myfunc, false);
	}else if(window.attachEvent){
		window.attachEvent('onload', myfunc);
	}
}

/**
 * function called by addOnload() (safe window.onload function) that grabs the JS array imprinted 
 * on the page by PHP to represent the POST data.
 *
 * The JS POST array is matched to the current form elements and each element is reloaded by 
 * matching the names of the associative array to the current contents.
 *
 * Radio buttons, checkboxes, selects & text fields can all be loaded in this way, as the type 
 * of element is sniffed and the loading is matched to each element
 * window.onload, and also will not interfere with more primitive hijacks.
 *
 * <code>
 * addOnload(loadElements);  //no quotes around function name
 * </code>
 *
 * @param none  contents derived from JS form on page & forms available on page
 * @return void
 * @todo JS not currently working for IE for text elements, selects - selects were never finished, yet work in FF 
 * @todo loop through all forms on page, in case of login, etc.
 */
function loadElements()
{//function called onLoad to reload form elements passed to JS via PHP
   for (var f = 0; f<document.forms.length;f++)
   {//loop through array of forms - will try each one since onload allows no parameters
 		thisForm = document.forms[f];  //the current form
	 	for(x=0; x<thisForm.elements.length; x++)
		{//loop array of form elements
			fObj = thisForm.elements[x];  //the current element
			
			//accommodate 'brackets' on the element name which means an array in PHP
			var fCheck = "";
			var brackets = fObj.name.indexOf("[]"); 
			if(brackets != -1)
			{//found one!  remove brackets from form element name
				fCheck = fObj.name.substring(0,brackets);
			}
			
			//identify the element type, match the POST array & load data
			switch(fObj.type)
			{//handle radio & checkbox elements first 
				case "radio":
				case "checkbox":
					for(var i in POST)
					{//loop POST array transferred from PHP
						if(fObj.name == i || fCheck == i )
						{//radio or checkbox with same name, with or without "[]"
							if(fObj.value == POST[i]){fObj.checked = true;}//radios, checkboxes w/o "[]"
							for(var z=0;z<i.length;z++)
							{//radios, checkboxes with [], z is the internal array of choices from the checkbox, etc.
								if(fObj.value == POST[i][z]){fObj.checked = true;}
							}
						}
					}
					break;
				//handle text, textarea & password elements here
				//I was surprised this works for single item selects, too!
				default:
			   		for(var i in POST)
					{//loop POST array transferred from PHP
						if(fObj.name == i)
						{//radio or checkbox with same name, with or without []
							var postStr = POST[i];
							var postStr = postStr.replace(/<br \/>/g, "\n"); //global replacement of <br /> tag with c/r
							//fObj.value = POST[i];
							fObj.value = postStr;
						}
					}
			}//end switch
		}//end form elements loop
	}//end forms loop
}//end loadElements()
