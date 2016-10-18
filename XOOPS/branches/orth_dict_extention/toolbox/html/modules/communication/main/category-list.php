<?php
//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Toolbox. This provides a series of
// multilingual collaboration tools.
// Copyright (C) 2009  NICT Language Grid Project
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
//$categories = $categoryManager->getCategories();
require_once dirname(__FILE__).'/../class/permission/permission.php';
require_once dirname(__FILE__).'/../class/manager/category-manager.php';	//09.09.03 add
require_once dirname(__FILE__).'/../class/util/pager.php';
require_once dirname(__FILE__).'/../class/util/sortheader.php';

$permission = new Permission();

$xoopsOption['template_main'] = $mydirname.'_main_category_list.html' ;
include XOOPS_ROOT_PATH.'/header.php' ;
if (isset($sort)) {
	$sort = '';
}

//09.09.03 add
$categoryManager = new CategoryManager();
$CategoriesCount = $categoryManager->getCategoriesCount();

if(isset($_GET['sortkey'])){
	$sortkey = intval($_GET['sortkey']);
}else{
	$sortkey = 0;
}
$sortheader = new SortHeader(6,$sortkey);

if(isset($_GET['page'])){
	$page = intval($_GET['page']);
	if(!$page){$page = 1;}
}else{
	$page = 1;
}
$view = CATEGORY_LIST_MAX;


$params = array(
	'currentPage' => $page,
	'perPage' => $view,
	'totalItems' => $CategoriesCount
);
$pager = new Pager($params);

$categoryManager->setCurPage($pager->getCurrentPage());
$categoryManager->setParPage($pager->getPerPage());
$categoryManager->setSortKey($sortkey);
$categories = $categoryManager->getCategories();

$xoopsTpl->assign(array(
	'page' => $page,
	'permission' => $permission,
	'categories' => $categories,
	'pager' => $pager,
	'sortheader' => $sortheader
//	,
//	'sort' => $sort
));
?>