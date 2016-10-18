<?php
require_once 'test_util.php';
require_once XOOPS_TRUST_PATH.'/modules/'.$mytrustdirname.'/class/shop_question.php';

echo "****************************** [shop_question.php test start] ******************************";


echo '<p>--  Test1 findById() ----------------------------------</p>' . PHP_EOL;

	$shopQuestion = ShopQuestion::findById(4);

	// debug
	if (0) {
		echo "<pre>";
		var_dump($shopQuestion);
		echo "</pre>";
	}

echo '<p style="background-color:#F8FFF8;border:2px solid green;">Test1 findById() OK</p>' . PHP_EOL;




echo '<p>--  Test2 findAllByCategoryId() ----------------------------------</p>' . PHP_EOL;
	$shopQuestions = ShopQuestion::findAllByCategoryId(2);

	echo "- * - * - * - * - * - * - * - * -------------------------------------<br/>";
	if (1) {
		echo '<pre>';
		var_dump($shopQuestions);
		echo '</pre>';
	}

echo '<p style="background-color:#F8FFF8;border:2px solid green;">Test2 findAllByCategoryId() OK</p>' . PHP_EOL;



echo '<p>--  Test3 getAnswersByAvailable() ----------------------------------</p>' . PHP_EOL;

	$shopQuestion = ShopQuestion::findById(4);
	$availableAnswers = $shopQuestion -> getAnswersByAvailable();

	// debug
	if (0) {
		echo "<pre>";
		var_dump($availableAnswers);
		echo "</pre>";
	}

echo '<p style="background-color:#F8FFF8;border:2px solid green;">Test3 getAnswersByAvailable() OK</p>' . PHP_EOL;



echo '<p>--  Test4 getQuestion() ----------------------------------</p>' . PHP_EOL;

	$shopQuestion = ShopQuestion::findById(4);
	$Questions = $shopQuestion -> getQuestion();
	
	// debug
	if (0) {
		echo "<pre>";
		var_dump($Questions);
		echo "</pre>";
	}

echo '<p style="background-color:#F8FFF8;border:2px solid green;">Test4 getQuestion() OK</p>' . PHP_EOL;


echo '<p>--  Test5 getQuestionAvailable() ----------------------------------</p>' . PHP_EOL;

	$shopQuestion = ShopQuestion::findById(4);
	$qustionAvailable = $shopQuestion -> getQuestionAvailable();
	
	// debug
	if (0) {
		echo "<pre>";
		var_dump($qustionAvailable);
		echo "</pre>";
	}

echo '<p style="background-color:#F8FFF8;border:2px solid green;">Test5 getQuestionAvailable() OK</p>' . PHP_EOL;



echo '<p>--  Test6 getFirstAnswer() ----------------------------------</p>' . PHP_EOL;

	$shopQuestion = ShopQuestion::findById(4);
	$firstAnswer = $shopQuestion -> getFirstAnswer();
	
	// debug
	if (0) {
		echo "<pre>";
		var_dump($firstAnswer);
		echo "</pre>";
	}

echo '<p style="background-color:#F8FFF8;border:2px solid green;">Test6 getFirstAnswer() OK</p>' . PHP_EOL;



echo '<p>--  Test7 findWithOffset() ----------------------------------</p>' . PHP_EOL;

	//$shopQuestions = ShopQuestion::findWithOffset($categoryId, $offset, $limit);
	$shopQuestions = ShopQuestion::findWithOffset(2, 2, 1);
	
	// debug
	if (0) {
		echo "<pre>";
		var_dump($shopQuestions);
		echo "</pre>";
	}

echo '<p style="background-color:#F8FFF8;border:2px solid green;">Test7 findWithOffset() OK</p>' . PHP_EOL;



echo '<p>--  Test8 searchQuestion() ----------------------------------</p>' . PHP_EOL;

	/**
	 * 
	 * @param string search keyword
	 * @param string resourceName
	 * @param int categoryId
	 * @param string asec/desc
	 * @param string sort creationDate/
	 * @param int offset
	 * @param int limit
	 */
	$shopQuestions = ShopQuestion::searchQuestion('japanese-english', '本', array(1,2), 'asec', 'creationDate', 0, 5);

	// debug
	if (0) {
		echo "<pre>";
		var_dump($shopQuestions);
		echo "</pre>";
	}

echo '<p style="background-color:#F8FFF8;border:2px solid green;">Test8 searchQuestion() OK</p>' . PHP_EOL;


echo '<p>--  Test9 getAllCategories() ----------------------------------</p>' . PHP_EOL;

	// resourceName:japanese-english or estate-jp-ch pulled from user_dictionary table.
	$shopCaetgories = ShopQuestion::getAllCategories('estate-jp-ch');
	
	// debug
	if (0) {
		echo "<pre>";
		var_dump($shopCaetgories);
		echo "</pre>";
	}

echo '<p style="background-color:#F8FFF8;border:2px solid green;">Test9 findWithOffset() OK</p>' . PHP_EOL;


echo '<p>--  Test10 getAllCategories() ----------------------------------</p>' . PHP_EOL;

	// resourceName:japanese-english or estate-jp-ch pulled from user_dictionary table.
	$shopQuestions = ShopQuestion::findByResorceNameWithOffset('japanese-english', 0, 50);
	
	// debug
	if (0) {
		echo "<pre>";
		var_dump($shopQuestions);
		echo "</pre>";
	}

echo '<p style="background-color:#F8FFF8;border:2px solid green;">Test10 findByResorceNameWithOffset() OK</p>' . PHP_EOL;


echo "****************************** [shop_question.php test end] ******************************";
?>