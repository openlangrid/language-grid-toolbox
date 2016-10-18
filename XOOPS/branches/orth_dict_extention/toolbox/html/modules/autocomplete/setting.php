<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides accurate
// translation using the autocomplete feature based on parallel texts and
// translation template.
// Copyright (C) 2010  CITY OF KYOTO
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
/** $Id: setting.php 3550 2010-03-25 07:36:17Z yoshimura $ */

require_once(dirname(__FILE__).'/class/AutoCompleteSetting.php');
// Redirect to top page if user don't sign in.
$userId = is_object( @$xoopsUser ) ? $xoopsUser->getVar('uid') : 0 ;
if (!$userId) {
	redirect_header(XOOPS_URL.'/');
}

if (isset($_GET['ajax'])) {
	// Ajax
	$ajax = preg_replace( '/[^a-zA-Z0-9_-]/' , '' , $_GET['ajax'] );
	$file = dirname(__FILE__).'/ajax/'.$ajax.'.php';
	if(file_exists($file)) {
		include $file;
	}
	die();
}

$mydirname = basename(dirname(__FILE__));

$xoopsOption['template_main'] = 'autocomplete_setting_main.html';

$javaScripts = array(
	'autocomplete_setting_main.js'
);

// Header
$xoops_module_header = $xoopsTpl->get_template_vars( "xoops_module_header" );

// JavaScript
foreach ($javaScripts as $javaScript) {
	$xoops_module_header .= '<script charset="utf-8" type="text/javascript" src="'.XOOPS_URL.'/modules/'.$mydirname.'/js/'.$javaScript.'?'.time().'"></script>';
}

// CSS
$xoops_module_header .= '<link rel="stylesheet" type="text/css" href="'.XOOPS_URL.'/modules/'.$mydirname.'/css/autocomplete_setting_main.css?'.time().'" />';
$xoops_module_header .= '<link rel="stylesheet" type="text/css" href="'.XOOPS_URL.'/modules/'.$mydirname.'/css/user_style.css?'.time().'" />';

$module_img_url = XOOPS_URL.'/modules/'.$mydirname.'/images/';

$root = XCube_Root::getSingleton();
//$userIsAdmin = $root->mContext->mXoopsUser->isAdmin();
$userIsAdmin = true;

$xoopsTpl->assign(
	array(
		'xoops_module_header' => $xoops_module_header,
		'howToUse' => XOOPS_URL.'/modules/'.$mydirname.'/how-to-use/'._MI_AUTOCOMPLETE_SETTING_HOW_TO_USE_LINK,
		'module_img_url' => $module_img_url,
		'xoops_url' => XOOPS_URL,
		'userIsAdmin' => $userIsAdmin
	)
);

$c = new AutoCompleteSetting();
$resourceNames = $c->get();
$currentSettings = $c->load();

$datas = array();
foreach ($resourceNames as $name) {
	if (in_array($name, $currentSettings)) {
		$datas[] = array('name' => $name, 'checked' => 'checked="yes"');
	} else {
		$datas[] = array('name' => $name, 'checked' => '');
	}
}
$xoopsTpl->assign('datas', $datas);
//$xoopsTpl->assign(array('resourceNames'=>$resourceNames, 'currentSettings'=>$currentSettings));

?>