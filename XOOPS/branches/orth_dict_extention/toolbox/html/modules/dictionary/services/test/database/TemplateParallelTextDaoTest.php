<?php 
require_once(dirname(__FILE__).'/../../database/TemplateParallelTextDao.php');



echo "\nDao Test ---------------------------------------------\n\n";

searchTemplateTest('en', 'for', 'PARTIAL', array(36));
searchTemplateTest('en', 'for', 'PARTIAL', array());
searchTemplateTest('ja', '加入', 'PARTIAL', array());
getTemplatesByTemplateIdTest("en", array(827, 839, 632));
generateSentenceTest('en', 632, array(
	array("parameterId" => 2, "choiceId" => 54)),// or 55
	array(array("parameterId" => 0, "value" => "TEST TEXT VALUE"),
		array("parameterId" => 1, "value" => "10000")
	));
listTemplateCategoriesTest('en');

echo "\n[Complate] All Tests. --------------------------------\n";

function listTemplateCategoriesTest($language){
	try{
		$dao = new TemplateParallelTextDao("template import test 3");
		$start = microtime(true);
		$result = $dao->listTemplateCategories($language);
		$end = (microtime(true) - $start) * 1000;
		
		foreach($result as $row){
			if( ! is_a($row, "Category")){
				throw new Exception("Invalid result:object is not 'Category' class.");
			}
			if(is_null($row->categoryId) || is_null($row->categoryName)){
				throw new Exception("Null parameter value:object has not parameter value.");
			}
		}
		
		echo "[Success] " . __FUNCTION__ . "(" . $language . ")";
		echo "{count:" . count($result) . ", " . $end . "ms}.\n";
		logging(var_export($result, true));
	} catch(Exception $e) {
		logging($e->getMessage());
		$messages = preg_split("/:/", $e->getMessage());
		echo "[Failed: " . $messages[0] ."] " . __FUNCTION__  . "(){";
		echo $messages[1] . "}\n";
	}
}

function generateSentenceTest($language, $templateId, $boundChoiceParameters, $boundValueParameters){
	try{
		$dao = new TemplateParallelTextDao("template import test 3");
		$start = microtime(true);
		$result = $dao->generateSentence($language, $templateId, $boundChoiceParameters, $boundValueParameters);
		$end = (microtime(true) - $start) * 1000;
		
		if(is_null($result)){
			throw new Exception("Null parameter value:object has not parameter value.");
		}
		
		echo "[Success] " . __FUNCTION__ . "(" . $language . "," . $templateId . "," . $boundChoiceParameters . "," . $boundValueParameters . ")";
		echo "{count:" . count($result) . ", " . $end . "ms}.\n";
		echo "\tgenerated sentence: " . $result . "\n";
		logging(var_export($result, true));
	} catch(Exception $e) {
		logging($e->getMessage());
		$messages = preg_split("/:/", $e->getMessage());
		echo "[Failed: " . $messages[0] ."] " . __FUNCTION__  . "(){";
		echo $messages[1] . "}\n";
	}
}

function getTemplatesByTemplateIdTest($lang, $templateIds){
	try{
		$dao = new TemplateParallelTextDao("template import test 3");
		$start = microtime(true);
		$result = $dao->getTemplatesByTemplateId($lang, $templateIds);
		$end = (microtime(true) - $start) * 1000;
		echo "[Success] " . __FUNCTION__ . "(" . $lang . ", " . dumpArray($templateIds) . ")";
		echo "{count:" . count($result) . ", " . $end . "ms}\n";
		logging(var_export($result, true));
	} catch(Exception $e) {
		logging($e->getMessage());
		$messages = preg_split("/:/", $e->getMessage());
		echo "[Failed: " . $messages[0] ."] " . __FUNCTION__  . "(){";
		echo $messages[1] . "}\n";
	}
}

function searchTemplateTest($lang, $text, $method, $categories){
	try {
		$dao = new TemplateParallelTextDao("template import test 3");
		$start = microtime(true);
		$result = $dao->searchTemplates($lang, $text, $method, $categories);
		$end = (microtime(true) - $start) * 1000;
		echo "[Success] " . __FUNCTION__  . "(". $lang . "," . $text . "," . $method . "," . dumpArray($categories) . ")";
		echo "{count:" . count($result). ", " . $end . "ms}\n";
		logging(var_export($result, true));
	} catch(Exception $e) {
		logging($e->getMessage());
		$messages = preg_split("/:/", $e->getMessage());
		echo "[Failed: " . $messages[0] ."] " . __FUNCTION__  . "(){";
		echo $messages[1] . "}\n";
	}
}

function logging($message){
	$a = 'C:/xampp/htdocs/toolbox-ymc-update/xoops_trust_path/log/toolbox-debug.'.date('Ymd').'.log';
	list($micro, $Unixtime) = explode(" ", microtime());
	$sec = $micro + date("s", $Unixtime);
	$dt = date("Y-m-d g:i:", $Unixtime).$sec;
	error_log($dt.' - '.$message . PHP_EOL, 3, $a);
}

function dumpArray($array){
	$s = "array(";
	foreach($array as $a){
		$s = $s . $a . ",";
	}
	return preg_replace("/,$/", "", $s) . ")";
}
?>