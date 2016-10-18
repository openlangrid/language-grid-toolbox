<?php
require_once dirname(__FILE__).'/../../class/shop_answer_content.php';
require_once dirname(__FILE__).'/../../class/shop_answer.php';
require_once dirname(__FILE__).'/../../class/shop_category_list.php';
require_once dirname(__FILE__).'/../../class/shop_category.php';
require_once dirname(__FILE__).'/../../class/shop_question_list.php';
require_once dirname(__FILE__).'/../../class/shop_question.php';
require_once dirname(__FILE__).'/../../class/common_util.php';
require_once dirname(__FILE__).'/../../class/ToolboxVO_QA_Available_QARecord.php';

// resourceName
$resourceName = @$_GET['resourceName'] ? $_GET['resourceName'] : @$_POST['resourceName'];

if (!isset($resourceName)){
	exit( "Not to display this page. <br> you need resourceName parameter." );
}

// include sublist.
require_once dirname(__FILE__).'/_list.php';

$xoopsTpl->assign(array(
	'dictionaries' => GlossaryList::findSelectedDefaultGlossaryDictionaries($resourceName),
	'shopCategoryList' => ShopCategoryList::findByResourceName($resourceName, $langManager)
));
