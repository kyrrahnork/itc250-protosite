<?php
/**
 * demo_contact.php is based on contact.php, but has been adapted to use the nmCommon include files. 
 * Use this file as a model for creating your contact pages.
 *
 * UPDATED 2/2/2013 - This version includes an important fix having to do with servers blocking sent mail 
 * with the user's email being placed into the "from" field via PHP's mail() function.
 *
 * Servers such as Dreamhost have a policy of blocking any emails with a "from" field that is not associated 
 * with the current domain.  This version alleviates this issue by creating a "from" field derived from the original 
 * domain name (no-reply@examplecom, for example) and uses the Reply-To header field to allow our client to click 
 * Reply To and be able to email back to the person who filled out the form.
 *
 * contact.php is a postback application designed to provide a 
 * contact form for users to email our clients.  contact.php references 
 * recaptchalib.php as an include file which provides all the web service plumbing 
 * to connect and serve up the CAPTCHA image and verify we have a human entering data.
 *
 * Only the form elements 'Email' and 'Name' are significant.  Any other form 
 * elements added, with any name or type (radio, checkbox, select, etc.) will be delivered via  
 * email with user entered data.  Form elements named with underscores like: "How_We_Heard" 
 * will be replaced with spaces to allow for a better formatted email:
 *
 * <code>
 * How We Heard: Internet
 * </code>
 * 
 * If checkboxes are used, place "[]" at the end of each checkbox name, or PHP will not deliver 
 * multiple items, only the last item checked:
 *
 * <code>
 * <input type="checkbox" name="Interested_In[]" value="New Website" /> New Website <br />
 * <input type="checkbox" name="Interested_In[]" value="Website Redesign" /> Website Redesign <br />
 * <input type="checkbox" name="Interested_In[]" value="Lollipops" /> Complimentary Lollipops <br />
 * </code>
 *
 * The CAPTCHA is handled by reCAPTCHA requiring an API key for each separate domain. 
 * Get your reCAPTCHA private/public keys from: http://recaptcha.net/api/getkey
 *
 * Place your target email in the $toAddress variable.  Place a default 'noreply' email address 
 * for your domain in the $fromAddress variable.
 *
 * After testing, change the variable $sendEmail to TRUE to send email.
 *
 * Tech Stuff: To retain data entered during an incorrect CAPTCHA, POST data is embedded in JS array via a 
 * PHP function sendPOSTtoJS().  On page load a JS function named loadElements() matches the 
 * embedded JS array to the form elements on the page, and reloads all user data into the
 * form elements. 
 *
 * @package nmCAPTCHA
 * @author Bill Newman <williamnewman@gmail.com>
 * @version 2.2 2013/02/02
 * @link http://www.newmanix.com/
 * @license https://www.apache.org/licenses/LICENSE-2.0
 * @see util.js
 * @see recaptchalib.php   
 * @todo none
 */
 
# '../' works for a sub-folder.  use './' for the root 
require '../inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials 

# For each customer/domain, get a key from http://www.google.com/recaptcha/whyrecaptcha (DON'T LET A CUSTOMER USE YOUR KEY) 
# zephir ONLY reCAPTCHA keys are below:
$publickey = "6Ld11wYAAAAAAMqyo-I6NvENfuD3VJOXRBLG_8cE";
$privatekey = "6Ld11wYAAAAAAEbf282RKWoikILiUBE7U1QJzfmO";
# For each customer/domain, get a key from http://recaptcha.net/api/getkey

#EDIT THE FOLLOWING:
$toAddress = "horsey01@example.com";  //place your/your client's email address here - EDISON/ZEPHIR WILL ONLY EMAIL seattlecentral.edu ADDRESSES!
$toName = "VALUED CLIENT"; //place your client's name here
$website = "DEMO FORM";  //place NAME of your client's website/form here, ie: ITC280 Contact, ITC280 Registration, etc.
$sendEmail = FALSE; //if true, will send an email, otherwise just show user data.

$config->titleTag = smartTitle(); #Fills <title> tag. If left empty will fallback to $config->titleTag in config_inc.php
$config->metaDescription = smartTitle() . ' - ' . $config->metaDescription;
  
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

# The config property named 'loadHead' places JS, CSS, etc. inside the <head> tag - only use double quotes, or escape them!
$config->loadhead = '
<script type="text/javascript" src="' . VIRTUAL_PATH . 'include/util.js"></script>
<!-- Edit Required Form Elements via JavaScript Here -->
<script type="text/javascript">
	//here we make sure the user has entered valid data	
	function checkForm(thisForm)
	{//check form data for valid info
		if(empty(thisForm.Name,"Please Enter Your Name")){return false;}
		if(!isEmail(thisForm.Email,"Please enter a valid Email Address")){return false;}
		return true;//if all is passed, submit!
	}
</script>

<!-- CSS class for required field elements.  Move to your CSS? (or not) -->
<style type="text/css">.required {font-style:italic;color:#FF0000;font-weight:bold;}</style>
'; #load page specific JS

#--------------END CONFIG AREA ------------------------#
$resp = ""; # the response from reCAPTCHA
$error = ""; # the error code from reCAPTCHA, if any
$skipFields = "recaptcha_challenge_field,recaptcha_response_field,Email"; #comma separated list of form elements NOT to store.
$fromDomain = $_SERVER["SERVER_NAME"];
$fromAddress = "noreply@" . $fromDomain; //form always submits from domain where form resides
include INCLUDE_PATH . 'recaptchalib_inc.php'; #required reCAPTCHA class code
include INCLUDE_PATH . 'contact_inc.php'; #complex unsightly code moved here
get_header(); #defaults to header_inc.php
?>
<form action="<?php echo THIS_PAGE; ?>" method="post" onsubmit="return checkForm(this);">
<?php
if (isset($_POST["recaptcha_response_field"]))
{# Check for reCAPTCHA response
    $resp = recaptcha_check_answer ($privatekey,$_SERVER["REMOTE_ADDR"],$_POST["recaptcha_challenge_field"],$_POST["recaptcha_response_field"]);
	if ($resp->is_valid)
	{#reCAPTCHA agrees data is valid
         handle_POST($skipFields,$sendEmail,$toName,$fromAddress,$toAddress,$website,$fromDomain);#process form elements, format and send email.
        
         #Here we can enter the data sent into a database in a later version of this file
?>
        <!-- format HTML here to be your 'thank you' message -->
		<div align="center"><h2>Your Comments Have Been Received!</h2></div>
        <div align="center">Thanks for the input!</div>
        <div align="center">We'll respond via email within 48 hours, if you requested information.</div>
            
<?php
    }else{#reCATPCHA response says data not valid - prepare for feedback
            $error = $resp->error;
            send_POSTtoJS($skipFields); #function for sending POST data to JS array to reload form elements
    }
}

#show form, either for first time, or if data not valid per reCAPTCHA    
if(!isset($_POST["recaptcha_response_field"])|| $error != "")
{#separate code block to deal with returning failed data, or no data sent yet	
	?>
	<!-- below change the HTML to accommodate your form elements - only 'Name' & 'Email' are significant -->
	<div align="center"><h3>Contact Us</h3></div>
	<div align="center">Your opinion is very important!</div>
	<div align="center"><span class="required">(*required)</span></div>
	<style type="text/css">

	</style>
		<p><span class="required">*</span>Name:<br />
		<input type="text" name="Name" required="true" size="45" title="Your Name is Required" /><br />
		<span class="required">*</span>Email:<br />
		<input type="email" name="Email" required="true" size="45" title="A Valid Email is Required" /><br />		
		Comments:<br />
		<textarea name="Comments" cols="35" rows="4"></textarea><br />
		<?php echo recaptcha_get_html($publickey, $error); ?>
		<input type="submit" value="submit" />
		</p>
    </form>
<?php
}
get_footer(); #defaults to footer_inc.php
?>