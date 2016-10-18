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
//start
require_once dirname(__FILE__).'/../class/permission/permission.php';
require_once dirname(__FILE__).'/../class/manager/post-manager.php';
require_once dirname(__FILE__).'/../class/manager/topic-manager.php';
require_once dirname(__FILE__).'/../class/manager/forum-manager.php';
require_once dirname(__FILE__).'/../class/manager/category-manager.php';
require_once dirname(__FILE__).'/../class/util/pager.php';
require_once dirname(__FILE__).'/../class/util/sortheader.php';

$categoryId = intval($_GET['categoryId']);

$categoryManager = new CategoryManager();
$category = $categoryManager->getCategory($categoryId);

$params = array(
	'categoryId' => $categoryId
);

$permission = new Permission($params);
//if (!$permission->categoryAccess()) {
//	die(_MD_D3FORUM_ERR_READCATEGORY);
//}

//-------- 09.09.07 add start -------
$forumManager = new ForumManager();
$ForumsCount = $forumManager->getForumsCountByCatId($categoryId);
if(isset($_GET['sortkey'])){
	$sortkey = intval($_GET['sortkey']);
}else{
	$sortkey = 0;
}
$sortheader = new SortHeader(5,$sortkey);

if(isset($_GET['page'])){
	$page = intval($_GET['page']);
	if(!$page){$page = 1;}
}else{
	$page = 1;
}
$view = FORUM_LIST_MAX;

$params = array(
	'currentPage' => $page,
	'perPage' => $view,
	'totalItems' => $ForumsCount
);
$pager = new Pager($params);

$forumManager->setCurPage($pager->getCurrentPage());
$forumManager->setParPage($pager->getPerPage());
$forumManager->setSortKey($sortkey);
$forums = $forumManager->getForumsByCatId($categoryId);
//-------- 09.09.07 add end -------

$xoopsOption['template_main'] = $mydirname.'_main_forum_list.html' ;
include XOOPS_ROOT_PATH.'/header.php' ;
$xoopsTpl->assign(
	array(
		'categoryId' => $categoryId,
		'category' => $category,
		'permission' => $permission,
		'forums' => $forums,
		'pager' => $pager,
		'sortheader' => $sortheader
	)
);
?>