<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides a multilingual
// BBS, where messages can be displayed in multiple languages.
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
require_once dirname(__FILE__).'/../class/permission/permission.php';
require_once dirname(__FILE__).'/../class/manager/category-manager.php';
require_once dirname(__FILE__).'/../class/manager/forum-manager.php';
require_once dirname(__FILE__).'/../class/manager/topic-manager.php';
$permission = new Permission();
if (!$permission->isAdmin()) {
	die(_MD_D3FORUM_ERR_DELETE_TOPIC);
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$topicManager = new TopicManager();
	$topicId = $_POST['topicId'];
	$topic = $topicManager->getTopic($_POST['topicId']);
	$topicManager->deleteTopic($topicId);
	$forumId = intval($topic->getForumId());
	redirect_header(XOOPS_URL.'/modules/'.$mydirname.'/?forumId='.$forumId);
	die();
} else {
	$topicId = intval($_GET['topicId']);
	$topicManager = new TopicManager();
	$topic = $topicManager->getTopic($topicId);
	$forumManager = new ForumManager();
	$forum = $forumManager->getForum($topic->getForumId());
	$categoryManager = new CategoryManager();
	$category = $categoryManager->getCategory($forum->getCategoryId());
	$xoopsTpl->assign( array(
		'topic' => $topic,
		'forum' => $forum,
		'category' => $category
	));
	include XOOPS_ROOT_PATH.'/header.php';
	$xoopsOption['template_main'] = $mydirname.'_main_topic_delete_form.html';
	include XOOPS_ROOT_PATH.'/footer.php' ;
}
?>