<?php
require_once dirname(__FILE__).'/../../class/shop_question_list.php';

$renderOption['type'] = 'noheader';

$caId = @$_GET['caId'];
$page = @$_GET{'page'};
$keyWord = @$_GET['keyWord'];

$sort = @$_GET['sort'];
if (!$sort) {
	$sort = 'asec';
}

$resourceName = urldecode(@$_GET['resourceName']);
$pageInfo;

if (isset($page)) {
	$offset = -1;// (ShopQuestionList::DEFAULT_LIMIT * ($page - 1)) ;
	$pageInfo = ShopQuestionList::seachCategoriesWithKeyWord($resourceName,$keyWord,$caId,$sort,'creationDate',$offset);
} else {
    $offset = -1;
	$pageInfo = ShopQuestionList::seachCategoriesWithKeyWord($resourceName,$keyWord,$caId,$sort,'creationDate',$offset);
}


$myPager = array();
$myPage = array();
foreach ($pageInfo->getQuestions() as $question) {
    if (count($myPage) == 7) {
        $myPager[] = $myPage;
        $myPage = array();
    }
    $myPage[] = $question;
}
if (count($myPage)) {
    $myPager[] = $myPage;
}

if (!isset($page)){
	$page = 1;
}

$info = $pageInfo->getQuestions();

$xoopsTpl -> assign(array(
	'caId' => $caId,
	'content' =>  $info,
	'hitCount' => $pageInfo->getSum(),
	'page' =>  $page,
    'myPager' => $myPager,
	'keyWord' => htmlspecialchars($keyWord),
	'resourceName' => urlencode(htmlspecialchars($resourceName)),
	'sort' => $sort
));
