<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides a multilingual
// BBS, where messages can be displayed in multiple languages.
// Copyright (C) 2009-2013  Department of Social Informatics, Kyoto University
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
require_once dirname(__FILE__).'/../class/manager/post-manager.php';
require_once dirname(__FILE__).'/../class/manager/topic-manager.php';
require_once dirname(__FILE__).'/../class/manager/forum-manager.php';
require_once dirname(__FILE__).'/../class/manager/category-manager.php';

require_once dirname(__FILE__).'/../class/history/bbs-edit-history-manager.php';
require_once dirname(__FILE__).'/../class/history/enum-bbs-item-type-code.php';
require_once dirname(__FILE__).'/../class/history/enum-process-type-code.php';

$languageManager = new LanguageManager();
$forumManager = new ForumManager();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$forumId = intval($_POST['forumId']);
	$forum = $forumManager->getForum($forumId);
	
	$categoryManager = new CategoryManager();
	$category = $categoryManager->getCategory($forum->getCategoryId());
	
	$params = array(
		'categoryId' => $categoryId,
		'forumId' => $forumId
	);
	$permission = new Permission($params);
	
	if (!$permission->forumModify()){
		die(_MD_D3FORUM_ERR_MODFORUM);
	}
	
	if ($_POST['description'][$languageManager->getSelectedLanguage()] == $forum->getDescription()
		&& $forum->getTitle() == $_POST['title'][$languageManager->getSelectedLanguage()]
	) {
		redirect_header(XOOPS_URL.'/modules/'.$mydirname.'/?forumId='.$forumId);
		die();
	}
	
	$forumManager->modifyForum(
		$forumId
		, $_POST['title'][$languageManager->getSelectedLanguage()]
		, $_POST['description'][$languageManager->getSelectedLanguage()],
		'',
		''
	);
	redirect_header(XOOPS_URL.'/modules/'.$mydirname.'/?forumId='.$forumId);
	die();
} else {
	$forumId = intval($_GET['forumId']);
	$forum = $forumManager->getForum($forumId);
	
	if ($forum->getOriginalLanguage() == $languageManager->getSelectedLanguage()) {
		redirect_header(XOOPS_URL.'/modules/'.$mydirname.'/?page=forum-edit&forumId='.$forumId);
		die();
	}
	
	$categoryManager = new CategoryManager();
	$category = $categoryManager->getCategory($forum->getCategoryId());
	
	$params = array(
		'categoryId' => $forum->getCategoryId(),
		'forumId' => $forumId
	);
	$permission = new Permission($params);
	
	if (!$permission->forumModify()){
		die(_MD_D3FORUM_ERR_MODFORUM);
	}
	
	include XOOPS_ROOT_PATH.'/header.php';
	$xoopsOption['template_main'] = $mydirname.'_main_forum_modify_form.html';
	
	$bbsEditHistoryManager = new BBSEditHistoryManager();
	$modifyHistory = array();
	$modifyHistory['title'] = $bbsEditHistoryManager->getModificationHistory($forumId
							, EnumBBSItemTypeCode::$forumTitle
							, $languageManager->getSelectedLanguage());
	$modifyHistory['description'] = $bbsEditHistoryManager->getModificationHistory($forumId
							, EnumBBSItemTypeCode::$forumDescription
							, $languageManager->getSelectedLanguage());
	
	$xoopsTpl->assign(
		array(
			'forum' => $forum,
			'category' => $category,
			'modifyHistory' => $modifyHistory
		)
	);
	//20130213 add
	include dirname(dirname(__FILE__)).'/main/user-access.php' ;
	//20130213 add
	include XOOPS_ROOT_PATH.'/footer.php' ;
}
?>