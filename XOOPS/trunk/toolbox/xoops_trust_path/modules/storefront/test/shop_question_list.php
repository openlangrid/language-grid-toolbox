<?php
require_once 'test_util.php';
require_once XOOPS_ROOT_PATH.'/api/class/client/QAClient.class.php';
require_once XOOPS_TRUST_PATH.'/modules/'.$mytrustdirname.'/class/shop_question.php';
require_once XOOPS_TRUST_PATH.'/modules/'.$mytrustdirname.'/class/shop_question_list.php';

echo "****************************** [shop_question_list.php test start] ******************************";


echo '<p>--  Test1 update() ----------------------------------</p>' . PHP_EOL;

	/**
	 * Request parameters
	 * 
	 * [Questions format]
	 * qa_question[qaQuestionId]=available(display flag of Question)
	 * 
	 * sample:
	 * ?qa_question[0]=1&qa_question[1]=0&qa_question[3]=0&qa_question[9]=1
	 * 
	 * [Answers format]
	 * qa_answer[qaQuestionId][qaAnswerId]=available(display flag of Answer)
	 * 
	 * sample:
	 * ?qa_answer[0][0]=1&qa_answer[0][1]=1&qa_answer[1][0]=0&qa_answer[1][1]=1&qa_answer[1][2]=0
	 */
	$qaQuestion          = $_GET['qa_question'];
	$qaQuestionAndAnswer = $_GET['qa_answer'];
		
	// get request parameters and create ShopQuestionList from it
	$shopQuestions = array();
	
	// get parameters for question update
	if (isset($qaQuestion)) {
		foreach($qaQuestion as $qaQuestionId => $available) {

			$QArecord = new ToolboxVO_QA_QARecord();
			$QArecord -> id           = $qaQuestionId;							// qa_question_id
			$QArecord -> question     = array();
			$QArecord -> categoryIds  = array();
			
			// Answers
			if (isset($qaQuestionAndAnswer)) {
				$QArecord -> answers      = getShopAnswers($qaQuestionId, $qaQuestionAndAnswer);		
			} else {
				$QArecord -> answers      = array();
			}			
	
			// Questions
			$shopQuestion = ShopQuestion::createFromQARecord($QArecord);
			$shopQuestion -> setQuestionAvailable($available);
			array_push($shopQuestions, $shopQuestion);
		 }
	}
		
	// update Q&A available
	$shopQuestionList = ShopQuestionList::createFromQuestionList($shopQuestions);
	$result = $shopQuestionList -> update();
	
	echo "[update result]" . $result . "<br/>";

/**
 * Returns ShopAnswer instances.
 * @param int qaQuestionId
 * @param object Questions and Answers
 * @return array of ShopAnswer instance
 */
function getShopAnswers($qaQuestionId, $qaQuestionAndAnswer) {

	$shopAnswers = array();

	// get parameters for answer update
	foreach($qaQuestionAndAnswer as $_qaQuestionId => $answers) {
		
		if (strcmp($qaQuestionId, $_qaQuestionId) == 0 && is_array($answers)) {
			
			foreach ($answers as $qaAnswerId => $available) {
				
				$shopAnswer = ShopAnswer::createFromAnswer(array( 'id' => $qaAnswerId,	// qa_answer_id
																  'qa_question_id' => $qaQuestionId,	// qa_question_id
															  	  'qa_answer_available' => $available,
															  	  'shop_answer_id' => null));
				array_push($shopAnswers, $shopAnswer);
			}
		}
	 }
	 return $shopAnswers;
}


echo '<p style="background-color:#F8FFF8;border:2px solid green;">Test1 update() OK</p>' . PHP_EOL;


echo "****************************** [shop_question_list.php test end] ******************************";
?>