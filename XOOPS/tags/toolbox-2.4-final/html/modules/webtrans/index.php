<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate a Web page.
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
error_reporting(0);
require_once('../../mainfile.php');
require_once('./php/TranslatorSettings.php');
require_once('./php/javascripts.php');
require_once('./php/css.php');
require_once('./php/LangPairJS.php');

$userId = is_object( @$xoopsUser ) ? $xoopsUser->getVar('uid') : 0 ;
if (!$userId) {
	redirect_header(XOOPS_URL.'/');
}

//---------------------------
preg_match('/^[a-zA-Z\-]+$/', @$_GET['mainte'], $matches);
@$filePath = XOOPS_ROOT_PATH.'/modules/infoMainte/php/ajax/'.$matches[0].'.php';
if (@$matches[0] && file(@$filePath)) {
	@require_once $filePath;
	die();
}
//----------------------------

preg_match('/^[a-zA-Z\-]+$/', @$_GET['page'], $matches);
@$filePath = dirname(__FILE__).'/php/ajax/'.$matches[0].'.php';
if (@$matches[0] && file(@$filePath)) {
	require_once $filePath;
	die();
}

$xoopsOption['template_main'] = 'web-translation.html';
include(XOOPS_ROOT_PATH.'/header.php');

// for language selecter
$settings = new TranslatorSettings();
$languages = $settings->getSupportedLanguageTags();

$xoops_module_header = '';
$xoops_module_header .= $xoopsTpl->get_template_vars( "xoops_module_header" );

$mydirname = basename(dirname(__FILE__));
$mypath = XOOPS_URL."/modules/".$mydirname."/";
foreach($css as $c){
	$xoops_module_header .= "<link rel='stylesheet' type='text/css' href='".$mypath."css/".$c."' />\n";
}
$xoops_module_header .= '<script type="text/javascript" src="'.XOOPS_URL.'/modules/infoMainte/js/InfoManagerClass.js"></script>'."\n";
$langpairs = getLanguagePairScript();
$xoops_module_header .= $langpairs;
foreach($javaScripts as $script){
	$xoops_module_header .= '<script charset="utf-8" type="text/javascript" src="'.$mypath.$script.'"></script>'."\n";
}

//$rev = file_get_contents(XOOPS_ROOT_PATH.'/modules/'.$mydirname.'/rev');
//$date = stat(XOOPS_ROOT_PATH.'/modules/".$mydirname."/rev');
//$revision = '<div align="right">Revision: '.$rev
//	.'. ('.date('r', $date['mtime']).')</div>';
$revision = '';
$MyUrl = XOOPS_URL.'/modules/'.$mydirname;

$xoopsTpl->assign(
	array(
		'languages' => $languages,
		'xoops_module_header' => $xoops_module_header,
		'revision' => $revision,
		'module_url' => $MyUrl,
		'howToUse' =>  $MyUrl.'/how-to-use/'._MI_WEBTRANS_HOW_TO_USE_LINK
	)
);

include(XOOPS_ROOT_PATH.'/footer.php');
?>