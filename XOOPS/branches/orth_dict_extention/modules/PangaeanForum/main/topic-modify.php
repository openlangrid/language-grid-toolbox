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
require_once dirname(__FILE__).'/../class/manager/topic-manager.php';
require_once dirname(__FILE__).'/../class/manager/forum-manager.php';
require_once dirname(__FILE__).'/../class/manager/category-manager.php';

require_once dirname(__FILE__).'/../class/history/bbs-edit-history-manager.php';
require_once dirname(__FILE__).'/../class/history/enum-bbs-item-type-code.php';
require_once dirname(__FILE__).'/../class/history/enum-process-type-code.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$topicManager = new TopicManager();

	$topicId = intval($_POST['topicId']);
	$params = array(
		'topicId' => $topicId
	);
	$permission = new Permission($params);
	if (!$permission->topicModify()){
		die(_MD_D3FORUM_ERR_MODTOPIC);
	}
	if (!$_POST['title'][$languageManager->getSelectedLanguage()]) {
		die(_MD_D3FORUM_ERR_NOMESSAGE);
	}

	$topic = $topicManager->getTopic($topicId);

	if ($_POST['title'][$languageManager->getSelectedLanguage()] == $topic->getTitle()) {
		redirect_header(XOOPS_URL.'/modules/'.$mydirname.'/?topicId='.$topicId);
		die();
	}

	$topicManager->modifyTopic($topicId, $_POST['title'][$languageManager->getSelectedLanguage()], '', '');
	redirect_header(XOOPS_URL.'/modules/'.$mydirname.'/?topicId='.$topicId);
	die();
} else {
	$topicManager = new TopicManager();
	$topicId = intval($_GET['topicId']);
	$topic = $topicManager->getTopic($topicId);

	if ($topic->getOriginalLanguage() == $languageManager->getSelectedLanguage()) {
		redirect_header(XOOPS_URL.'/modules/'.$mydirname.'/?page=topic-edit&topicId='.$topicId);
		die();
	}

	$originalTopic = $topicManager->getOriginalTopic($topicId);

	$forumManager = new ForumManager();
	$forum = $forumManager->getForum($topic->getForumId());

	$categoryManager = new CategoryManager();
	$category = $categoryManager->getCategory($forum->getCategoryId());

	$params = array(
		'categoryId' => $forum->getCategoryId(),
		'forumId' => $topic->getForumId(),
		'topicId' => $topicId
	);
	$permission = new Permission($params);
	if (!$permission->topicModify()){
		die(_MD_D3FORUM_ERR_MODTOPIC);
	}

	include XOOPS_ROOT_PATH.'/header.php';
	$xoopsOption['template_main'] = $mydirname.'_main_topic_modify_form.html';
	$bbsEditHistoryManager = new BBSEditHistoryManager();
	$modifyHistory = $bbsEditHistoryManager->getModificationHistory($topicId
							, EnumBBSItemTypeCode::$topicTitle
							, $languageManager->getSelectedLanguage());
	$xoopsTpl->assign(
		array(
			'topic' => $topic,
			'originalTopic' => $originalTopic,
			'forum' => $forum,
			'category' => $category,
			'modifyHistory' => $modifyHistory
		)
	);
	include XOOPS_ROOT_PATH.'/footer.php' ;
}
?>