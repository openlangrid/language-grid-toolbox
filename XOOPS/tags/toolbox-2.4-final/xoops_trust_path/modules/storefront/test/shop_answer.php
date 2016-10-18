<?php

require_once 'test_util.php';
require_once XOOPS_TRUST_PATH.'/modules/'.$mytrustdirname.'/class/shop_question.php';

echo "****************************** [shop_answer.php test start] ******************************";

echo '<p>--  Test1 getFirstContent() ----------------------------------</p>' . PHP_EOL;

	$shopQuestion = ShopQuestion::findById(4);
	$shopAnswers = $shopQuestion -> getAnswers();

	// debug
	if (0) {
		echo "<pre>";
		var_dump($shopAnswers);
		echo "</pre>";
	}
	
	foreach ($shopAnswers as $shopAnswer) {
		$shopAnswerContent = $shopAnswer -> getFirstContent();
		
		// debug
		if (0) {
			echo "-------------------------------------------<br/>";
			echo "<pre>";
			var_dump($shopAnswerContent);
			echo "</pre>";
		}
	}
	


echo '<p style="background-color:#F8FFF8;border:2px solid green;">Test1 getFirstContent() OK</p>' . PHP_EOL;


echo '<p>--  Test2 getSecondContent() ----------------------------------</p>' . PHP_EOL;

	$shopQuestion = ShopQuestion::findById(4);
	$shopAnswers = $shopQuestion -> getAnswers();

	// debug
	if (0) {
		echo "<pre>";
		var_dump($shopAnswers);
		echo "</pre>";
	}
	
	foreach ($shopAnswers as $shopAnswer) {
		
		echo "-------------------------------------------<br/>";
		echo "[SHOP ANSWER ID] " . $shopAnswer -> getShopAnswerId() . "<br/>";
		echo "[QA QUESTION ID] " . $shopAnswer -> getQaAnswerId() . "<br/>";
		$shopAnswerContent = $shopAnswer -> getSecondContent();
		
		// debug
		if (0) {
			echo "::::::::::::::::::::::::::::::::::::::::::::::::::::::::<br/>";
			echo "<pre>";
			var_dump($shopAnswerContent);
			echo "</pre>";
			echo "::::::::::::::::::::::::::::::::::::::::::::::::::::::::<br/>";
		}
	}
	


echo '<p style="background-color:#F8FFF8;border:2px solid green;">Test2 getSecondContent() OK</p>' . PHP_EOL;


echo '<p>--  Test2 hasContent() ----------------------------------</p>' . PHP_EOL;

	$shopQuestion = ShopQuestion::findById(4);
	$shopAnswers = $shopQuestion -> getAnswers();

	// debug
	if (0) {
		echo "<pre>";
		var_dump($shopAnswers);
		echo "</pre>";
	}
	
	foreach ($shopAnswers as $shopAnswer) {
		
		echo "-------------------------------------------<br/>";
		echo "[SHOP ANSWER ID] " . $shopAnswer -> getShopAnswerId() . "<br/>";
		echo "[QA ANSWER ID] " . $shopAnswer -> getQaAnswerId() . "<br/>";
		$boolean = $shopAnswer -> hasContent();
		
		echo "[hasContent:boolean]" . $boolean . "<br/>";
	}
	


echo '<p style="background-color:#F8FFF8;border:2px solid green;">Test2 getSecondContent() OK</p>' . PHP_EOL;


echo "****************************** [shop_answer.php test end] ******************************";
?>