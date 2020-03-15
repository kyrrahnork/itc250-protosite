<?php
/**
 * Answer.php provides a way to store answers to questions to Surveys for the SurveySez 
 * project
 *
 * The Answer class is not normally called directly, rather it's loaded via the constructor 
 * of the Survey class, which stores an array of Questions as one of it's properties.
 *
 * The individual questions are in turn loaded with an array of answers as a property of 
 * each question object.
 * 
 * Data access for several of the SurveySez pages are handled via Survey classes 
 * named Survey,Question & Answer, respectively.  These classes model the one to many 
 * relationships between their namesake database tables. 
 *
 * @package SurveySez
 * @author William Newman
 * @version 2.1 2015/05/28
 * @link http://newmanix.com/ 
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @see Survey.php
 * @see Question.php
 * @see Response.php
 * @see Choice.php
 */
 
 namespace SurveySez;
 
/**
* The Answer class is not normally called directly, rather it's loaded via the constructor 
 * of the Survey class, which stores an array of Questions as one of it's properties.
 *
 * The individual questions are in turn loaded with an array of answers as a property of 
 * each question object.
 *
 * @see Question
 * @see Survey 
 * @todo none
 */
 
class Answer
{
	 public $AnswerID = 0;
	 public $Text = "";
	 public $Description = "";
	
	/**
	 * Constructor for Answer class. 
	 *
	 * @param integer $AnswerID ID number of answer 
	 * @param string $Text The text of the answer
	 * @param string $Description Additional description info
	 * @return void 
	 * @todo none
	 */ 
    function __construct($AnswerID,$answer,$description)
	{#constructor sets stage by adding data to an instance of the object
		$this->AnswerID = (int)$AnswerID;
		$this->Text = $answer;
		$this->Description = $description;
	}#end Answer() constructor
}#end Answer class
