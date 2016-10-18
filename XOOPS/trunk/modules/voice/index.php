<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts.
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
preg_match('/^[a-zA-Z\-]+$/', @$_GET['page'], $matches);
@$filePath = XOOPS_ROOT_PATH.'/modules/infoMainte/php/ajax/'.$matches[0].'.php';
if (@$matches[0] && file(@$filePath)) {
	@require_once $filePath;
	die();
}
//----------------------------

$xoopsOption['template_main'] = 'voice_main.html';
include(XOOPS_ROOT_PATH.'/header.php');

// set original smarty variables.
// must decleare between include(header.php) to include(footer.php)

// for language selecter
$settings = new TranslatorSettings();
$languages = $settings->getSupportedLanguageTags();


$xoops_module_header = '';
$xoops_module_header .= $xoopsTpl->get_template_vars( "xoops_module_header" );


foreach($css as $c){
	$xoops_module_header .= "\t<link rel='stylesheet' type='text/css' href='".XOOPS_URL."/modules/voice/" .$c. "' />\n";
}

$xoops_module_header .= "\t".'<script type="text/javascript" src="'.XOOPS_URL.'/modules/infoMainte/js/InfoManagerClass.js"></script>'."\n";
$langpairs = getLanguagePairScript();
$xoops_module_header .= $langpairs;

foreach($javaScripts as $script){
	$xoops_module_header .= "\t".'<script type="text/javascript" src="'.XOOPS_URL.'/modules/voice/'.$script.'"></script>'."\n";
}
$rev = file_get_contents(XOOPS_ROOT_PATH.'/modules/voice/rev');
$date = stat(XOOPS_ROOT_PATH.'/modules/voice/rev');
//$revision = '<div align="right">Revision: '.$rev
//	.'. ('.date('r', $date['mtime']).')</div>';
$revision = '';

$xoopsTpl->assign(array(
	'languages' => $languages
	, 'xoops_module_header' => $xoops_module_header
	, 'revision' => $revision
	, 'howToUse' => XOOPS_URL.'/modules/voice/how-to-use/'._MI_VOICE_HOW_TO_USE_LINK
	)
);


// decleare is end

include(XOOPS_ROOT_PATH.'/footer.php');

?>
