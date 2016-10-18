<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides access methods
// to the Language Grid.
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
//error_reporting(E_ALL);
require('../../mainfile.php');
include(XOOPS_ROOT_PATH.'/header.php');

$mydirname = basename(dirname(__FILE__));

$page = preg_replace( '/[^a-zA-Z0-9_-]/' , '' , @$_GET['page'] );
if( file_exists(dirname(__FILE__).'/main/'.$page.'.php')) {
	include dirname(__FILE__).'/main/'.$page.'.php';
} else {
	include dirname(__FILE__).'/main/index.php';
}


$nocache = '?'.time();
$nocache = '';

$xoops_module_header = '';
$xoops_module_header .= $xoopsTpl->get_template_vars( "xoops_module_header" );
$xoops_module_header .= '<script charset="utf-8" type="text/javascript" src="'.XOOPS_URL.'/modules/'.$mydirname.'/js/util/utilities.js'.$nocache.'"></script>'."\n";
$xoops_module_header .= '<script charset="utf-8" type="text/javascript" src="'.XOOPS_URL.'/modules/'.$mydirname.'/js/util/language.js'.$nocache.'"></script>'."\n";

$xoops_module_header .= '<script charset="utf-8" type="text/javascript" src="'.XOOPS_URL.'/modules/'.$mydirname.'/js/default-morphological-analyzer.js'.$nocache.'"></script>'."\n";
$xoops_module_header .= '<script charset="utf-8" type="text/javascript" src="'.XOOPS_URL.'/modules/'.$mydirname.'/js/langrid.js'.$nocache.'"></script>'."\n";
$xoops_module_header .= '<script charset="utf-8" type="text/javascript" src="'.XOOPS_URL.'/modules/'.$mydirname.'/js/langrid-setting.js'.$nocache.'"></script>'."\n";
$xoops_module_header .= '<script charset="utf-8" type="text/javascript" src="'.XOOPS_URL.'/modules/'.$mydirname.'/js/langrid-translation-path-panel.js'.$nocache.'"></script>'."\n";
$xoops_module_header .= '<script charset="utf-8" type="text/javascript" src="'.XOOPS_URL.'/modules/'.$mydirname.'/js/langrid-translation-path-panel-view.js'.$nocache.'"></script>'."\n";
$xoops_module_header .= '<script charset="utf-8" type="text/javascript" src="'.XOOPS_URL.'/modules/'.$mydirname.'/js/langrid-service-panel.js'.$nocache.'"></script>'."\n";
$xoops_module_header .= '<script charset="utf-8" type="text/javascript" src="'.XOOPS_URL.'/modules/'.$mydirname.'/js/langrid-service-panel-view.js'.$nocache.'"></script>'."\n";
$xoops_module_header .= '<script charset="utf-8" type="text/javascript" src="'.XOOPS_URL.'/modules/'.$mydirname.'/js/langrid-dictionary-popup-panel.js'.$nocache.'"></script>'."\n";

//$xoops_module_header .= '<script charset="utf-8" type="text/javascript" src="'.XOOPS_URL.'/modules/'.$mydirname.'/js/min-pack.js"></script>';

$xoops_module_header .= '<link rel="stylesheet" type="text/css" href="'.XOOPS_URL.'/modules/'.$mydirname.'/css/langrid-setting-module.css'.$nocache.'" />'."\n";
$xoops_module_header .= '<link rel="stylesheet" type="text/css" href="'.XOOPS_URL.'/modules/'.$mydirname.'/css/user_style.css'.$nocache.'" />'."\n";

switch($_GET['mode']){
	case 'bbs':
//		if($xoopsUserIsAdmin == true){
			$view_mode = "bbs";
			$xoops_module_header .= '<script charset="utf-8" type="text/javascript" src="'.XOOPS_URL.'/modules/'.$mydirname.'/js/langrid-bbs-setting.js'.$nocache.'"></script>';
//		}else{
//			$view_mode = "bbs_view";
//			$xoops_module_header .= '<script charset="utf-8" type="text/javascript" src="'.XOOPS_URL.'/modules/'.$mydirname.'/js/langrid-bbs-setting.js'.$nocache.'"></script>';
//			$xoops_module_header .= '<script charset="utf-8" type="text/javascript" src="'.XOOPS_URL.'/modules/'.$mydirname.'/js/langrid-bbs-setting-view.js'.$nocache.'"></script>'."\n";
//		}
		$howToUse = XOOPS_URL.'/modules/'.$mydirname.'/how-to-use/'._MI_LANGRID_BBS_HOW_TO_USE_LINK;
		$xoops_module_header .= '<script charset="utf-8" type="text/javascript" src="'.XOOPS_URL.'/modules/'.$mydirname.'/js/langrid-bbs-path-panel.js'.$nocache.'"></script>'."\n";
		break;
	case 'text':
		$view_mode = "text";
		$howToUse = XOOPS_URL.'/modules/'.$mydirname.'/how-to-use/'._MI_LANGRID_TEXT_HOW_TO_USE_LINK;
		break;
	case 'web':
		$view_mode = "web";
		$howToUse = XOOPS_URL.'/modules/'.$mydirname.'/how-to-use/'._MI_LANGRID_WEB_HOW_TO_USE_LINK;
		$xoops_module_header .= '<script charset="utf-8" type="text/javascript" src="'.XOOPS_URL.'/modules/'.$mydirname.'/js/langrid-web-setting.js'.$nocache.'"></script>'."\n";
		$xoops_module_header .= '<script charset="utf-8" type="text/javascript" src="'.XOOPS_URL.'/modules/'.$mydirname.'/js/langrid-web-path-panel.js'.$nocache.'"></script>'."\n";
		break;
	case 'communication':
		$view_mode = "communication";
		$howToUse = XOOPS_URL.'/modules/'.$mydirname.'/how-to-use/'._MI_LANGRID_COM_HOW_TO_USE_LINK;
		$xoops_module_header .= '<script charset="utf-8" type="text/javascript" src="'.XOOPS_URL.'/modules/'.$mydirname.'/js/langrid-com-setting.js'.$nocache.'"></script>'."\n";
		$xoops_module_header .= '<script charset="utf-8" type="text/javascript" src="'.XOOPS_URL.'/modules/'.$mydirname.'/js/langrid-com-setting-view.js'.$nocache.'"></script>'."\n";
		$xoops_module_header .= '<script charset="utf-8" type="text/javascript" src="'.XOOPS_URL.'/modules/'.$mydirname.'/js/langrid-com-path-panel.js'.$nocache.'"></script>'."\n";
		break;
	case 'collabtrans':
		$view_mode = $_GET['mode'];
		$howToUse = XOOPS_URL.'/modules/'.$mydirname.'/how-to-use/'._MI_LANGRID_TRANS_HOW_TO_USE_LINK;
		$xoops_module_header .= '<script charset="utf-8" type="text/javascript" src="'.XOOPS_URL.'/modules/'.$mydirname.'/js/langrid-setting-for-trans.js'.$nocache.'"></script>'."\n";
		$xoops_module_header .= '<script charset="utf-8" type="text/javascript" src="'.XOOPS_URL.'/modules/'.$mydirname.'/js/langrid-translation-path-panel-for-trans.js'.$nocache.'"></script>'."\n";
		break;
}

$module_img_url = XOOPS_URL.'/modules/'.$mydirname.'/images/';
$xoopsTpl->assign(
	array(
		'xoops_module_header' => $xoops_module_header,
		'howToUse' => $howToUse,
		'module_img_url' => $module_img_url,
		'xoops_url' => XOOPS_URL,
		'max_dict_count' => 5,
		'tab_page' => $view_mode
	)
);

require_once(XOOPS_ROOT_PATH."/footer.php");
// end.
?>