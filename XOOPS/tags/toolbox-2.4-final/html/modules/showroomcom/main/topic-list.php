<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to view
// contents of BBS without logging into Toolbox.
// Copyright (C) 2009  Department of Social Informatics, Kyoto University
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
require_once dirname(__FILE__).'/../class/manager/category-manager.php';
require_once dirname(__FILE__).'/../class/manager/forum-manager.php';
require_once dirname(__FILE__).'/../class/manager/topic-manager.php';
require_once dirname(__FILE__).'/../class/permission/permission.php';
require_once dirname(__FILE__).'/../class/util/pager.php';
require_once dirname(__FILE__).'/../class/util/sortheader.php';

$forumId = intval($_GET['forumId']);

$topicManager = new TopicManager();
//$topics = $topicManager->getTopicsByForumId($forumId);

$forumManager = new ForumManager();
$forum = $forumManager->getForum($forumId);

$categoryManager = new CategoryManager();
$category = $categoryManager->getCategory($forum->getCategoryId());

$params = array(
	'forumId' => $forumId,
	'categoryId' => $forum->getCategoryId()
);

$permission = new Permission($params);

// ------ 09.09.07 mod start ----------------
$topicManager = new TopicManager();
$TopicsCount = $topicManager->getTopicsCountByForumId($forumId);
if(isset($_GET['sortkey'])){
	$sortkey = intval($_GET['sortkey']);
}else{
	$sortkey = 0;
}
$sortheader = new SortHeader(3,$sortkey);

if(isset($_GET['page'])){
	$page = intval($_GET['page']);
	if(!$page){$page = 1;}
}else{
	$page = 1;
}
$view = TOPIC_LIST_MAX;

$params = array(
	'currentPage' => $page,
	'perPage' => $view,
	'totalItems' => $TopicsCount
);
$pager = new Pager($params);

$topicManager->setCurPage($pager->getCurrentPage());
$topicManager->setParPage($pager->getPerPage());
$topicManager->setSortKey($sortkey);
$topics = $topicManager->getTopicsByForumId($forumId);

// ------ 09.09.07 mod end ----------------


//if (!$permission->categoryAccess()) {
//	die(_MD_D3FORUM_ERR_READFORUM);
//};
//
//if (!$permission->forumAccess()) {
//	die(_MD_D3FORUM_ERR_READFORUM);
//}

$xoopsOption['template_main'] = $mydirname.'_main_topic_list.html';
include XOOPS_ROOT_PATH.'/header.php';
$xoopsTpl->assign(
	array(
		'topics' => $topics,
		'forum' => $forum,
		'category' => $category,
		'permission' => $permission,
		'pager' => $pager,
		'sortheader' => $sortheader
	)
);
?>