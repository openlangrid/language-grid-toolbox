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
require_once dirname(__FILE__).'/../class/permission/permission.php';
require_once dirname(__FILE__).'/../class/manager/post-manager.php';
require_once dirname(__FILE__).'/../class/manager/topic-manager.php';
require_once dirname(__FILE__).'/../class/manager/forum-manager.php';
require_once dirname(__FILE__).'/../class/manager/category-manager.php';
require_once dirname(__FILE__).'/../class/util/pager.php';
require_once dirname(__FILE__).'/../class/database/dao/dao-factory.php';

require_once dirname(__FILE__).'/../class/tag/adapter/TagBBSClientAdapter.class.php';

$topicId = intval($_GET['topicId']);

$topicManager = new TopicManager();
$topic = $topicManager->getTopic($topicId);

$forumManager = new ForumManager();
$forum = $forumManager->getForum($topic->getForumId());

$categoryManager = new CategoryManager();
$category = $categoryManager->getCategory($forum->getCategoryId());

$postManager = new PostManager();
$postsCount = $postManager->getPostsCount($topicId);

$params = array(
	'topicId' => $topicId,
	'forumId' => $topic->getForumId(),
	'categoryId' => $forum->getCategoryId()
);
$permission = new Permission($params);
if (isset($_GET['page'])) {
	$page = intval($_GET['page']);
} else {
	$page = 0;
}
if ($page <= 0) {
	$page = 1;
}

$view = POST_LIST_MAX;

$posts = $postManager->getPostsByTopicId($topicId, $page, $view);
require_once(dirname(__FILE__)."/../class/attachedFile/AttachedFileManager.php");
$attachedFileManager=new AttachedFileManager();
$cntPosts=count($posts);

$tagClient = new TagBBSClientAdapter();

for($i=0;$i<$cntPosts;$i++){
	$ret=$attachedFileManager->GetFileRecord($posts[$i]->getId());
	if($ret){
		$cntRet=count($ret);
		for($j=0;$j<$cntRet;$j++){
			$posts[$i]->postedFiles[$j]=$ret[$j];
			$posts[$i]->postedFiles[$j]["file_size"]/=1000;
		}
	}
	$tags = $tagClient->getBindTags($posts[$i]->getId());
	if ($tags) {
		$posts[$i]->tags = $tags;
	}
}

$params = array(
	'currentPage' => $page,
	'perPage' => $view,
	'totalItems' => $postsCount
);
$pager = new Pager($params);
$xoopsOption['template_main'] = $mydirname.'_main_post_list.html' ;

$js = array(
	'class/panel/templates.js',
	'class/panel/panel.js',
	'class/panel/imported-services-panel.js',
	'class/panel/light-popup-panel.js',
	'class/attachedFile/FileList.js',
	'config/bbs-pull-message-config.php',
	'class/util/observable.js',
	'class/util/observer.js',
	'class/pull/bbs-post-page.js',
	'class/pull/bbs-pull-client.js',
	'main/post-list.js',
);

//var_dump($xoopsModuleConfig);

$xoopsModuleHeader = $xoopsTpl->get_template_vars("xoops_module_header");
foreach ($js as $jsPath) {
	$xoopsModuleHeader .= '<script charset="UTF-8" type="text/javascript" src="'.XOOPS_URL.'/modules/'.$mydirname.'/js/'.$jsPath.'"></script>'."\n";
}
$xoopsModuleHeader .= '<script type="text/javascript" src="'.XOOPS_URL.'/modules/infoMainte/js/InfoManagerClass.js"></script>'."\n";
$xoopsModuleHeader .= '<script type="text/javascript" src="'.XOOPS_URL.'/modules/'.$mydirname.'/js/InfoMainte.js"></script>'."\n";


//var_dump($posts);
$maxTimestamp = 0;
foreach ($posts as $post) {
	$maxTimestamp = max($post->getUpdateTime(), $maxTimestamp);
}

$daoFactory = DAOFactory::getInstance();
$user = Toolbox::getCurrentUser();
$dao = $daoFactory->createTopicAccessLogDAO($topic->getId(), $user->getId());
$dao->doLogging();

// user
$users = $dao->getOnlineUsers();

$onlineUsers = array();
foreach ($users as $user) {
	$onlineUsers[] = $user->toArray();
}

function cmp($a, $b)
{
	$a = ($a['name'] != '') ? $a['name'] : $a['fullName'];
	$b = ($b['name'] != '') ? $b['name'] : $b['fullName'];
    return strnatcmp($a, $b);
}

usort($onlineUsers, 'cmp');

$xoopsModuleHeader.="<link href='".XOOPS_URL.'/modules/'.$mydirname."/css/langrid-setting-module.css' type='text/css' rel='stylesheet'>";
$xoopsModuleHeader.="<link href='".XOOPS_URL.'/modules/'.$mydirname."/css/imported-services.css' type='text/css' rel='stylesheet'>";


include XOOPS_ROOT_PATH.'/header.php' ;
$xoopsTpl->assign(
	array(
		'xoops_module_header' => $xoopsModuleHeader,
		'permission' => $permission,
		'posts' => $posts,
		'topic' => $topic,
		'onlineUsers' => $onlineUsers,
		'timestamp' => $maxTimestamp,
		'forum' => $forum,
		'totalPosts' => $postsCount,
		'category' => $category,
		'page' => $page,
		'view' => $view,
		'limit' => $view,
		'offset' => intval(($page-1) * $view),
		'pager' => $pager,
		'userTableWidth' => count($onlineUsers) * 40,
		'aResults' => $postManager->getPostsCount($topicId)
	)
);
?>