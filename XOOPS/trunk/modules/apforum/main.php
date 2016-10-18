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
error_reporting(0);
//error_reporting(E_ALL);

define('CATEGORY_LIST_MAX',20);
define('FORUM_LIST_MAX',20);
define('TOPIC_LIST_MAX',20);
define('POST_LIST_MAX',20);

$userId = is_object(@$xoopsUser) ? $xoopsUser->getVar('uid') : 0 ;

if (!$userId) {
	redirect_header(XOOPS_URL.'/');
}

preg_match('/^[a-zA-Z\-]+$/', @$_GET['impage'], $matches);
@$filePath = XOOPS_ROOT_PATH.'/modules/infoMainte/php/ajax/'.$matches[0].'.php';
if (@$matches[0] && file_exists($filePath)) {
	@require_once $filePath;
	die();
}

require_once dirname(__FILE__).'/class/manager/language-manager.php';
$languageManager = new LanguageManager();
//require_once(XOOPS_TRUST_PATH.'/libs/altsys/class/D3LanguageManager.class.php');
//$langman =& D3LanguageManager::getInstance();
//$langman->read( 'main.php' , $mydirname , $mytrustdirname);

$db =& Database::getInstance();

require_once dirname(__FILE__).'/class/manager/title-manager.php';
require_once dirname(__FILE__).'/class/manager/category-manager.php';
require_once dirname(__FILE__).'/class/manager/forum-manager.php';

$mytrustdirname = basename( dirname( __FILE__ ) ) ;
$mytrustdirpath = dirname(__FILE__);

//09.09.03 add
$titleManager = new TitleManager;
$titles = $titleManager->getAllTitle();

$allLanguages = array();
foreach ($languageManager->getAllLanguages() as $languageTag) {
	$allLanguages[$languageTag] = $languageManager->getNameByTag($languageTag);
}

$toLanguages = array();
foreach ($languageManager->getToLanguages() as $languageTag) {
	$toLanguages[$languageTag] = $languageManager->getNameByTag($languageTag);
}

asort($allLanguages, SORT_STRING);
asort($toLanguages, SORT_STRING);

$xoopsTpl->assign(
	array(
		//'forums' => $forums,
		//'categories' => $categories,
		'titles' => $titles,
		'allLanguages' => $allLanguages,
		'allLanguageJson' => json_encode($allLanguages),
		'toLanguages' => $toLanguages,
		'selectedLanguageTag' => $languageManager->getSelectedLanguage(),
		'selectedLanguageName' => $languageManager->getNameByTag($languageManager->getSelectedLanguage()),
		'allLanguagePair' => $languageManager->getAllLanguagePair()
	)
);
//var_dump($allLanguagePair);

if (empty($_SERVER['QUERY_STRING'])) {
	$pagenquery = $_SERVER['PHP_SELF'].'?lang=';
} elseif (isset($_SERVER['QUERY_STRING'])) {
	$query = explode("&",$_SERVER['QUERY_STRING']);
	$langquery = $_SERVER['QUERY_STRING'];
	$langquery = '&'.$langquery;

	// If the last parameter of the QUERY_STRING is sel_lang, delete it so we don't have repeating sel_lang=...
        If (strpos($query[count($query) - 1], 'lang=')  === 0 ) {
            $langquery = str_replace('&' . $query[count($query) - 1], '', $langquery);
        }

	$pagenquery = $_SERVER['PHP_SELF'].'?'.$langquery.'&lang=';
	$pagenquery = str_replace('?&','?',$pagenquery);
}

$xoops_module_header = <<< EOF
<script><!--
jQuery.noConflict();
//--></script>
EOF;
$xoops_module_header .= $xoopsTpl->get_template_vars("xoops_module_header");
$xoops_module_header .= '<link rel="stylesheet" type="text/css" href="'.XOOPS_URL.'/modules/'.$mydirname.'/css/bbs-main.css" />'."\n";
$xoops_module_header .= '<script type="text/javascript" src="'.XOOPS_URL.'/modules/'.$mydirname.'/js/forum-functions.js"></script>'."\n";

$user_define_header = '<link rel="stylesheet" type="text/css" href="'.XOOPS_URL.'/modules/'.$mydirname.'/css/user_style.css" />'."\n";
$xoopsTpl->assign(
	array(
		'user_define_header' => $user_define_header,
		'pagenquery' => $pagenquery,
		'mydirname' => $mydirname ,
		'mod_url' => XOOPS_URL.'/modules/'.$mydirname ,
		'mod_imageurl' => XOOPS_URL.'/modules/'.$mydirname.'/'.$xoopsModuleConfig['images_dir'] ,
		'mod_config' => $xoopsModuleConfig ,
		'xoops_config' => $xoopsConfig ,
		'xoops_module_header' => $xoops_module_header,
		'howToUse' => XOOPS_URL.'/modules/'.$mydirname.'/how-to-use/'._MI_D3FORUM_HOW_TO_USE_LINK,
		'autoUpdateIntevalTime' => $xoopsModuleConfig['autoUpdateIntevalTime'],
		'manualUpdateIntevalTime' => $xoopsModuleConfig['manualUpdateIntevalTime'],
		'showSettingLink' => 'site',
		'searchMode' => (isset($_GET['search']) || isset($_GET['search_result'])?'yes':'0')
	)
);

$controller = preg_replace( '/[^a-zA-Z0-9_-]/' , '' , @$_GET['controller'] );
$page = preg_replace( '/[^a-zA-Z0-9_-]/' , '' , @$_GET['page'] ) ;
if( file_exists($mytrustdirpath.'/controller/'.$controller.'.php')) {
	include $mytrustdirpath.'/controller/'.$controller.'.php';
} else if( file_exists($mytrustdirpath.'/main/'.$page.'.php')) {
	include $mytrustdirpath.'/main/'.$page.'.php';
} else {
	include $mytrustdirpath.'/main/index.php';
}
?>