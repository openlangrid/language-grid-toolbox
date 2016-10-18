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
//$categories = $categoryManager->getCategories();
require_once dirname(__FILE__).'/../class/permission/permission.php';
require_once dirname(__FILE__).'/../class/manager/category-manager.php';	//09.09.03 add
require_once dirname(__FILE__).'/../class/util/pager.php';
require_once dirname(__FILE__).'/../class/util/sortheader.php';

require_once dirname(__FILE__).'/../class/posted-notice/PostedNotice.class.php';
require_once dirname(__FILE__).'/../class/tag/Tag.class.php';
require_once dirname(__FILE__).'/../class/jumpbox/JumpBox.class.php';

$permission = new Permission();

$xoopsOption['template_main'] = $mydirname.'_main_category_list.html' ;

$js = array(
	'class/panel/templates.js',
	'class/panel/panel.js',
	'class/panel/light-popup-panel.js',
	'class/util/observable.js',
	'class/util/observer.js',
//	'class/posted-notice/PostedNotice.js',
	'class/preferences/preferences-panel.js',
	'class/posted-notice/posted-notice-panel.js',
	'class/tag/panel_plus.js',
	'class/tag/light-popup-panel_plus.js',
	'class/tag/tag-panel-wrapper.js',
	'class/tag/tag-panel.js',
	'class/tag/tag-sets-panel.js',
	'class/preferences/preferences.js',
	'class/jumpbox/JumpBox.js',
	'main/category-list.js'
);

//var_dump($xoopsModuleConfig);

$xoopsModuleHeader = $xoopsTpl->get_template_vars("xoops_module_header");
foreach ($js as $jsPath) {
	$xoopsModuleHeader .= '<script charset="UTF-8" type="text/javascript" src="'.XOOPS_URL.'/modules/'.$mydirname.'/js/'.$jsPath.'"></script>'."\n";
}
$xoopsModuleHeader.="<link href='".XOOPS_URL.'/modules/'.$mydirname."/css/langrid-setting-module.css' type='text/css' rel='stylesheet'>";
$xoopsModuleHeader.="<link href='".XOOPS_URL.'/modules/'.$mydirname."/css/imported-services.css' type='text/css' rel='stylesheet'>";
$xoopsModuleHeader.="<link href='".XOOPS_URL.'/modules/'.$mydirname."/css/tag-style.css' type='text/css' rel='stylesheet'>";

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

$postedNotice = new PostedNotice();
$tag = new Tag();
$jumpBox = new JumpBox();

$xoopsTpl->assign(array(
	'xoops_module_header' => $xoopsModuleHeader,
	'page' => $page,
	'permission' => $permission,
	'categories' => $categories,
	'pager' => $pager,
	'sortheader' => $sortheader,
	'postedNoticeConfig' => json_encode($postedNotice->loadPostedNoticeConfig()),
	'tagConfigResource' => json_encode($tag->loadConfigs()),
	'jumpBoxResource' => json_encode($jumpBox->loadAssignValues())
//	,
//	'sort' => $sort
));
?>