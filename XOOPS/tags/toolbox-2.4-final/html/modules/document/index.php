<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts.
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
//error_reporting(0);

error_reporting(E_ALL);
ini_set('display_errors', 'On');

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
preg_match('/^[a-zA-Z\-]+$/', @$_GET['page'], $matches);
@$filePath = XOOPS_ROOT_PATH.'/modules/infoMainte/php/ajax/'.$matches[0].'.php';
if (@$matches[0] && file(@$filePath)) {
	@require_once $filePath;
	die();
}
//----------------------------

$xoopsOption['template_main'] = 'document_main.html';
include(XOOPS_ROOT_PATH.'/header.php');

// set original smarty variables.
// must decleare between include(header.php) to include(footer.php)

// for language selecter
$settings = new TranslatorSettings();
$languages = $settings->getSupportedLanguageTags();


$xoops_module_header = '';
$xoops_module_header .= $xoopsTpl->get_template_vars( "xoops_module_header" );


foreach($css as $c){
	$xoops_module_header .= "\t<link rel='stylesheet' type='text/css' href='".XOOPS_URL."/modules/document/" .$c. "' />\n";
}

$xoops_module_header .= "\t".'<script type="text/javascript" src="'.XOOPS_URL.'/modules/infoMainte/js/InfoManagerClass.js"></script>'."\n";
$langpairs = getLanguagePairScript();
$xoops_module_header .= $langpairs;

foreach($javaScripts as $script){
	$xoops_module_header .= "\t".'<script type="text/javascript" src="'.XOOPS_URL.'/modules/document/'.$script.'"></script>'."\n";
}

// -
require_once XOOPS_ROOT_PATH.'/modules/filesharing/dialog/include_js.php';

foreach ($dialogjavascripts as $j) {
	$xoops_module_header .= '<script src="'.XOOPS_URL.'/modules/filesharing/dialog'.$j.'"></script>'."\n";
}
$xoops_module_header .= '<link rel="stylesheet" type="text/css" media="screen" href="'.XOOPS_URL.'/modules/filesharing/dialog/css/filesharingdialog.css" />'."\n";
$xoops_module_header .= '<link rel="stylesheet" type="text/css" media="screen" href="'.XOOPS_URL.'/modules/filesharing/dialog/css/glayer.css" />'."\n";
// -


$rev = file_get_contents(XOOPS_ROOT_PATH.'/modules/document/rev');
$date = stat(XOOPS_ROOT_PATH.'/modules/document/rev');
//$revision = '<div align="right">Revision: '.$rev
//	.'. ('.date('r', $date['mtime']).')</div>';
$revision = '';

require_once dirname(__FILE__).'/../langrid_config/class/manager/VoiceSettingManager.class.php';
$voiceSetting = VoiceSettingManager::getUserSetting();

$xoopsTpl->assign(array(
	'languages' => $languages
	, 'xoops_module_header' => $xoops_module_header
	, 'revision' => $revision
	, 'howToUse' => XOOPS_URL.'/modules/document/how-to-use/'._MI_DOCUMENT_HOW_TO_USE_LINK
	, 'showSettingLink' => 'user'
	, 'loadingImage' => XOOPS_URL.'/modules/document/img/loading.gif'
	, 'voiceSetting' => json_encode($voiceSetting)
	)
);


// decleare is end

include(XOOPS_ROOT_PATH.'/footer.php');

?>
