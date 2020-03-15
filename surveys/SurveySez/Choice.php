<?php
/**
 * Choice.php provides additional data access classes for the SurveySez project
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
 * @see Response.php
 */

namespace SurveySez;
 
/**
 * Choice Class stores data info for an individual Choice to an Answer
 * 
 * In the constructor an instance of the Response class creates multiple 
 * instances of the Choice class tacked to the Answer class to store 
 * response data.
 *
 * @see Answer
 * @see Response 
 * @todo none
 */
class Choice {
	public $AnswerID = 0; # ID of associated answer
	public $QuestionID = 0; # ID of associated question
	public $ChoiceID = 0; # ID of individual choice
	
	/**
	 * Constructor for Choice class. 
	 *
	 * @param integer $AnswerID ID number of associated answer 
	 * @param integer $QuestionID ID number of associated question
	 * @param integer $RQID ID number of choice from srv_response_question table
	 * @return void 
	 * @todo none
	 */ 
    function __construct($AnswerID,$QuestionID,$RQID)
	{# constructor sets stage by adding data to an instance of the object
		$this->AnswerID = (int)$AnswerID;
		$this->QuestionID = (int)$QuestionID;
		$this->ChoiceID = (int)$RQID;

	}#End Choice constructor
}#End Choice class