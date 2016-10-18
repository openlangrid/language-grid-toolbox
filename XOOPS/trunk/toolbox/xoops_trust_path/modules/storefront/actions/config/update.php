<?php
	require_once dirname(__FILE__).'/../../class/common_util.php';

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
	//$qaQuestion          = urldecode($_GET['qa_question']);
	//$qaQuestionAndAnswer = urldecode($_GET['qa_answer']);
	
	$qaQuestion          = $_POST['_qa_question'];
	$qaQuestionAndAnswer = $_POST['_qa_answer'];
	
	$resourceName = $_POST['resourceName'];
	
	// get request parameters and create ShopQuestionList from it
	$shopQuestions = array();
	$commonUtil = CommonUtil::createWithParams();
	
	// get parameters for question update
	if (isset($qaQuestion)) {
		$factory = new ShopQuestionFactory();
		foreach($qaQuestion as $qaQuestionId => $available) {

			$QArecord = new ToolboxVO_QA_QARecord();
			$QArecord -> id           = $qaQuestionId;							// qa_question_id
			$QArecord -> question     = array();
			$QArecord -> categoryIds  = array();
			
			// Answers
			if (isset($qaQuestionAndAnswer)) {
				$QArecord -> answers      = $commonUtil -> getShopAnswers($qaQuestionId, $qaQuestionAndAnswer);		
			} else {
				$QArecord -> answers      = array();
			}			
	
			// Questions
			$shopQuestion = $factory->createFromQARecord($QArecord);
			$shopQuestion -> setQuestionAvailable($available);
			array_push($shopQuestions, $shopQuestion);
		 }
	}
	
	// update Q&A available
	$shopQuestionList = ShopQuestionList::createFromQuestionList($shopQuestions);
	
	// set resouceName
	$result = $shopQuestionList -> update($resourceName);
	
	// TODO set page
	redirect_header(XOOPS_URL.'/modules/'.$GLOBALS['mytrustdirname'].'/config/?action=list&page=1&resourceName='.$resourceName);
?>