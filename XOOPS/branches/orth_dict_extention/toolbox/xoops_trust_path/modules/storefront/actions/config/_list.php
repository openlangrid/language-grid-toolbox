<?php
require_once dirname(__FILE__).'/../../class/shop_answer_content.php';
require_once dirname(__FILE__).'/../../class/shop_answer.php';
require_once dirname(__FILE__).'/../../class/shop_category_list.php';
require_once dirname(__FILE__).'/../../class/shop_category.php';
require_once dirname(__FILE__).'/../../class/shop_question_list.php';
require_once dirname(__FILE__).'/../../class/shop_question.php';
require_once dirname(__FILE__).'/../../class/manager/language-manager-no-cookie.php';
require_once dirname(__FILE__).'/../../class/ToolboxVO_QA_Available_QARecord.php';

// resourceName
$resourceName = @$_GET['resourceName'] ? $_GET['resourceName'] : @$_POST['resourceName'];

// for paging
$page = isset($_GET['page']) ? $_GET['page'] : 1;

// for searching by offset
$searchPage = isset($_GET['page']) ? $_GET['page'] : 1;
$perPage = isset($_GET['perPage']) ? $_GET['perPage'] : 5;

$perPages = array(
	'5' => 5,
	'10' => 10,
	'20' => 20,
	'50' => 50,
	'99999' => STF_LABEL_ALL
);

$mainLanguage = $_GET['mainLanguage'];
$subLanguage = $_GET['subLanguage'];

$langManager = new BilingualManagerWithoutCookie($mainLanguage, $subLanguage, $resourceName);

$langArray = CommonUtil::getLanguageListFromResource($resourceName);

if ($perPage <> "99999") {
	$shopQuestionList = ShopQuestionList::findByResourceNameWithOffset($resourceName, (($page-1) * $perPage), $perPage, $langManager);

} else {
	$shopQuestionList = ShopQuestionList::findAllByResourceName($resourceName, $langManager);
}

$allLangNames = CommonUtil::getLanguageNameMap();

$langList = array();
foreach ($langArray as $tag) {
	$langList[] = array($tag, @$allLangNames[$tag] ? $allLangNames[$tag] : $tag);
}

$pageInfo = sprintf(STF_LABEL_NUM_OF_FOUND,
	1 + (($page-1) * $perPage), ($page - 1) * $perPage + count($shopQuestionList->getQuestions()),
	$shopQuestionList->getSum()
);

$root_url = "config/?action=_list".
			"&mainLanguage=".$langManager->getSelectedLanguage().
			"&subLanguage=".$langManager->getSelectedSubLanguage().
			"&resourceName=$resourceName";
$xoopsTpl->assign(array(
	'pageInfo' => $pageInfo,
	'page' => $page,
	'perPage' => $perPage,
	'perPages' =>$perPages,
	'mainLanguage' => $langManager->getSelectedLanguage(),
	'subLanguage' => $langManager->getSelectedSubLanguage(),
	'resourceName' => $resourceName,
	'shopQuestionList' => $shopQuestionList,
	'langList' => $langList,
	'root_url' => $root_url
));
