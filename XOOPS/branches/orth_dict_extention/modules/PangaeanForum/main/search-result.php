<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides a multilingual
// BBS, where messages can be displayed in multiple languages.
// Copyright (C) 2009-2010  Department of Social Informatics, Kyoto University
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
//  ------------------------------------------------------------------------ //
//error_reporting(E_ALL);
error_reporting(0);
require_once dirname(__FILE__).'/../class/permission/permission.php';
require_once dirname(__FILE__).'/../class/manager/search-manager.php';
require_once dirname(__FILE__).'/../class/manager/post-manager.php';
require_once dirname(__FILE__).'/../class/manager/topic-manager.php';
require_once dirname(__FILE__).'/../class/manager/forum-manager.php';
require_once dirname(__FILE__).'/../class/manager/category-manager.php';
require_once dirname(__FILE__).'/../class/util/pager.php';
require_once dirname(__FILE__).'/../class/database/dao/dao-factory.php';
require_once dirname(__FILE__).'/../class/tag/adapter/TagBBSClientAdapter.class.php';
//require_once( XOOPS_ROOT_PATH . '/api/class/client/BBSClient.class.php' );

//$bbsClient = new BBSClient();
//--------------------------------------------------
//if(isset($_GET["word"])){
//	$searchWord = trim($_GET["word"]);
//}else{
//	include("./index.php");
//	exit();
//}
$searchWord = (isset($_GET["word"]) ? trim($_GET["word"]) : '');

/*
if(isset($_GET["slang"])){
	$searchLang = $_GET["slang"];
}else{
	$searchLang = $_POST["searchLang"];
}
*/
if(isset($_GET["categoryId"])){
	$categoryId = intval($_GET['categoryId']);
}else{
	$categoryId = intval(@$_POST['categoryId']);
}
if(isset($_GET["forumId"])){
	$forumId = intval($_GET['forumId']);
}else{
	$forumId = intval(@$_POST['forumId']);
}
if(isset($_GET["topicId"])){
	$topicId = intval($_GET['topicId']);
}else{
	$topicId = intval(@$_POST['topicId']);
}

$startDate = isset($_GET['startDate'])?$_GET['startDate']:@$_POST['startDate'];
if (isset($startDate)) {
	$s = strtotime($startDate);
	if ($s === -1) {
		$startDate = null;
		$s = null;
	}
}
$endDate = isset($_GET['endDate'])?$_GET['endDate']:@$_POST['endDate'];
if (isset($endDate)) {
	$e = strtotime($endDate);
	if ($e === -1) {
		$endDate = null;
		$e = null;
	} else {
		$e += 60*60*24;
	}
}
$ptime = array('start' => $s, 'end' => $e);

//--------------------------------------------------
$categoryManager = new CategoryManager();
$forumManager = new ForumManager();
$topicManager = new TopicManager();
$postManager = new PostManager();
$searchManager = new searchManager();

if($topicId > 0){
	$topic = $topicManager->getTopic($topicId);
	$forumId = $topic->getForumId();
}else{
	$topic = null;
	$topicId = null;
}
if($forumId > 0){
	$forum = $forumManager->getForum($forumId);
	$categoryId = $forum->getCategoryId();
}else{
	$forum = null;
	$forumId = null;
}
if($categoryId > 0){
	$category = $categoryManager->getCategory($categoryId);
}else{
	$category = null;
	$categoryId = null;
}

$tags = (isset($_GET['tag']) ? $_GET['tag'] : array());

//--------------------------------------------------

/*
$params = array(
	'topicId' => $topicId,
	'forumId' => $forumId,
	'categoryId' => $categoryId
);
$permission = new Permission($params);
*/

//--------------------------------------------------
if(isset($_GET['page'])){
	$page = intval($_GET['page']);
}else{
	$page = 0;
}
if($page <= 0){$page = 1;}
$view = POST_LIST_MAX;
//--------------------------------------------------
//$res = $bbsClient->searchMessages($searchWord,"PARTIAL","BODY");

//get_worktime("start");
if (isset($_GET['search_result'])) {
	$postsCount = $searchManager->getSearchCount($searchWord, $page, $view,$topicId,$forumId,$categoryId,$tags,$ptime);
	$posts = $searchManager->searchPosts($searchWord, $page, $view,$topicId,$forumId,$categoryId,$tags,$ptime);
	$noMatchMsg = _MD_D3FORUM_MSG_NOMATCH;
} else {
	$postsCount = 0;
	$posts = array();
	$noMatchMsg = '';
}
//get_worktime("end");

require_once(dirname(__FILE__)."/../class/attachedFile/AttachedFileManager.php");
$attachedFileManager=new AttachedFileManager();
$tagClient = new TagBBSClientAdapter();

foreach($posts as $K => $V){
	$ret=$attachedFileManager->GetFileRecord($V->getId());
	if($ret){
		foreach($ret as $K2 => $V2){
			$posts[$K]->postedFiles[$K2] = $V2;
			$posts[$K]->postedFiles[$K2]["file_size"] /= 1000;
		}
	}
	$tags = $tagClient->getBindTags($posts[$K]->getId());
	if ($tags) {
		$posts[$K]->tags = $tags;
	}
}

$params = array(
	'currentPage' => $page,
	'perPage' => $view,
	'totalItems' => $postsCount
);
$pager = new Pager($params);
$xoopsOption['template_main'] = $mydirname.'_main_search_list.html' ;

$js = array(
	'class/panel/templates.js',
	'class/panel/panel.js',
	'class/panel/imported-services-panel.js',
	'class/panel/light-popup-panel.js',
	'class/attachedFile/FileList.js',
	'class/jumpbox/NaviBox.js',
	'class/tag/tag.js',
	'class/search/search.js',
	'class/util/observable.js',
	'class/util/observer.js',
	'main/searched-post-list.js',
	'lib/protocalendar.js'
);

//var_dump($xoopsModuleConfig);

$xoopsModuleHeader = $xoopsTpl->get_template_vars("xoops_module_header");
foreach ($js as $jsPath) {
	$xoopsModuleHeader .= '<script charset="UTF-8" type="text/javascript" src="'.XOOPS_URL.'/modules/'.$mydirname.'/js/'.$jsPath.'"></script>'."\n";
}
$xoopsModuleHeader .= '<script type="text/javascript" src="'.XOOPS_URL.'/modules/infoMainte/js/InfoManagerClass.js"></script>'."\n";
$xoopsModuleHeader .= '<script type="text/javascript" src="'.XOOPS_URL.'/modules/'.$mydirname.'/js/InfoMainte.js"></script>'."\n";
$xoopsModuleHeader .= "<link href='".XOOPS_URL.'/modules/'.$mydirname."/css/langrid-setting-module.css' type='text/css' rel='stylesheet'>";
$xoopsModuleHeader .= "<link href='".XOOPS_URL.'/modules/'.$mydirname."/css/imported-services.css' type='text/css' rel='stylesheet'>";
$xoopsModuleHeader .= "<link href='".XOOPS_URL.'/modules/'.$mydirname."/css/protocalendar.css' type='text/css' rel='stylesheet'>";

include XOOPS_ROOT_PATH.'/header.php' ;
$xoopsTpl->assign(
	array(
		'categoryId' => $categoryId,
		'forumId' => $forumId,
		'topicId' => $topicId,
		'xoops_module_header' => $xoopsModuleHeader,
		'permission' => $permission,
		'posts' => $posts,
		'word' => $searchWord,
		'topic' => $topic,
		'forum' => $forum,
		'category' => $category,
		'totalPosts' => $postsCount,
		'page' => $page,
		'view' => $view,
		'limit' => $view,
		'offset' => intval(($page-1) * $view),
		'pager' => $pager,
		'aResults' => $postsCount,
		'noMatchMsg' => $noMatchMsg,
		'startDate' => $startDate,
		'endDate' => $endDate,
	)
);

require_once(dirname(__FILE__).'/search.php');

/*
function get_worktime($title){
	global $start_time;

	if(!isset($start_time)){
		$start_time = time()+round((float)microtime(),4);
	}

	$NowTime = time()+round((float)microtime(),4);

	echo "[".$title."]=".round($NowTime - $start_time,4)."<br>";
}
*/

?>
