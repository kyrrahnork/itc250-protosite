<?php
/**
 * Response.php provides additional data access classes for the SurveySez project
 * 
 * This file requires Survey.php to access the original Survey, Question & Answer classes
 * 
 * Data access for several of the SurveySez pages are handled via Survey classes 
 * named Survey,Question & Answer, respectively.  These classes model the one to many 
 * relationships between their namesake database tables. 
 *
 * Version 2 introduces two new classes, the Response and Choice classes, and moderate 
 * changes to the existing classes, Survey, Question & Answer.  The Response class will 
 * inherit from the Survey Class (using the PHP extends syntax) and will be an elaboration 
 * on a theme.  
 *
 * An instance of the Response class will attempt to identify a SurveyID from the srv_responses 
 * database table, and if it exists, will attempt to create all associated Survey, Question & Answer 
 * objects, nearly exactly as the Survey object.
 *
 * @package SurveySez
 * @author William Newman
 * @version 2.1 2015/05/28
 * @link http://newmanix.com/ 
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @see Survey.php
 * @see Question.php
 * @see Answer.php
 * @see Choice.php
 */

namespace SurveySez;
 
/**
 * Response Class retrieves response info for an individual Survey
 * 
 * The constructor of the Response class inherits all data from an instance of 
 * the Survey class.  As such it has access to all Question class and the Answer class 
 * info. 
 *
 * Properties of the Survey class like Title, Description and TotalQuestions provide 
 * summary information upon demand.
 * 
 * A response object (an instance of the Response class) can be created in this manner:
 *
 *<code>
 *$myResponse = new SurveySez\Response(1);
 *</code>
 *
 * In which one is the number of a valid Response in the database. 
 *
 * The showChoices() method of the Response object will access an array of choice 
 * objects and only show answers to questions that match
 *
 * @see Survey
 * @see Question
 * @see Answer
 * @see Choice  
 * @todo none
 */
class Response extends Survey
{
	public $ResponseID = 0; # unique ID number of current response
	public $DateTaken = ""; # Date Survey was taken
	public $SurveyID = 0;
	public $isValid = FALSE;
	public $aChoice = Array(); # stores an array of choice objects
	
	/**
	 * Constructor for Response class. 
	 *
	 * @param integer $id ID number of Response 
	 * @return void 
	 * @todo none
	 */ 
    function __construct($id)
	{
		$this->ResponseID = (int)$id;
		if($this->ResponseID == 0){return FALSE;} # invalid response id - abort
		$iConn = \IDB::conn(); # uses a singleton DB class to create a mysqli improved connection

		$sql = sprintf("select SurveyID, DateAdded from " . PREFIX . "responses where ResponseID =%d",$this->ResponseID);
		$result = mysqli_query($iConn,$sql) or die(trigger_error(mysqli_error($iConn), E_USER_ERROR));
		if (mysqli_num_rows($result) > 0)
		{# returned a response!
		  while ($row = mysqli_fetch_array($result))
		   {# load singular response object properties
			   $this->SurveyID = (int)$row['SurveyID'];
			   $this->DateTaken = dbOut($row['DateAdded']);
		   }
		}else{
			return FALSE; #no responses - abort	
		}
		mysqli_free_result($result);
		parent::__construct($this->SurveyID); # access parent class to build Question & Answers

		# attempt to load choice array of Answer objects
		if($this->TotalQuestions > 0)
		{# Questions must exist for this survey, if we are to proceed
			$sql = sprintf("select AnswerID, QuestionID, RQID from " . PREFIX . "responses_answers where ResponseID=%d order by QuestionID asc",$this->ResponseID);
			$result = mysqli_query($iConn,$sql) or die(trigger_error(mysqli_error($iConn), E_USER_ERROR));
			if (mysqli_num_rows($result) > 0)
			{# must be choices
			   while ($row = mysqli_fetch_array($result))
			   {# load data into array of choices
				   $this->aChoice[] = new Choice((int)$row['AnswerID'],(int)$row['QuestionID'],(int)$row['RQID']); 
			   }
			@mysqli_free_result($result);
			}
		}
	}# End Response Constructor
	
	/**
	 * Reveals choices in the internal Array of Choice Objects
	 *
	 * The choice array identifies chosen ID numbers from answers.  
	 * This function will echo only the chosen answers, not those unchosen. 
	 *
	 * @param none
	 * @return string prints data from Choice Array 
	 * @todo none
	 */ 
	function showChoices()
	{
		$myReturn = '';
		foreach($this->aQuestion as $question)
		{# loop through questions to reveal answers
			$myReturn .= "<b>" . $question->Number . ") ";
			$myReturn .= $question->Text . "</b> ";
			$myReturn .= '<em>(' . $question->Description . ')</em> ';
			foreach($question->aAnswer as $answer)
			{# loop through answers to see if chosen
				foreach($this->aChoice as $choice)
				{# loop through all choices to see if matches current answer
					if($answer->AnswerID == $choice->AnswerID)
					{# only show answers that are chosen
						$myReturn .= '<b>' . $answer->Text . "</b> ";
						break;	
					}
				}
			}
			$myReturn .= "<br />"; # break after each question/choices
		}
		return $myReturn;
	}#End showChoices() method
}#End Response class
