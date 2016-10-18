<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to create
// dictionaries and parallel texts.
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
error_reporting(0);
//ini_set('display_errors', 'On');
//error_reporting(E_ALL);
require_once dirname(__FILE__).'/../../mainfile.php';
//require_once XOOPS_ROOT_PATH.'/modules/toolbox/toolbox.php';
require_once dirname(__FILE__).'/php/lib/util/language_util.php';

preg_match('/^[a-zA-Z\-]+$/', @$_GET['page'], $matches);
@$filePath = dirname(__FILE__).'/php/ajax/'.$matches[0].'.php';
if (@$matches[0] && file(@$filePath)) {
	require_once $filePath;
	die();
}

$mydirname = basename(dirname(__FILE__));

if (isset($_GET['mode']) && $_GET['mode'] == 'parallel_text') {
	$mode = 'ParallelText';
	$getSupportedLanguage = $selectableLanguages = getSupportedLanguagePairs();
	$howToUse = XOOPS_URL.'/modules/'.$mydirname.'/how-to-use/'._MI_DICTIONARY_PARALLEL_TEXT_HOW_TO_USE_LINK;
} else if (isset($_GET['mode']) && $_GET['mode'] == 'paraphrase') {
	$mode = 'ParaphraseDictionary';
	$getSupportedLanguage = $selectableLanguages = getSupportedLanguagePairsWithSimple();
	$howToUse = XOOPS_URL.'/modules/'.$mydirname.'/how-to-use/'._MI_DICTIONARY_PARALLEL_TEXT_HOW_TO_USE_LINK;
} else if (isset($_GET['mode']) && $_GET['mode'] == 'normalize') {
	$mode = 'NormalizeDictionary';
	$getSupportedLanguage = getSupportedLanguagePairsWithNormalized();
	$selectableLanguages = getSupportedLanguagePairs();
	$howToUse = XOOPS_URL.'/modules/'.$mydirname.'/how-to-use/'._MI_DICTIONARY_HOW_TO_USE_LINK;
} else {
	$mode = 'Dictionary';
	$getSupportedLanguage = $selectableLanguages = getSupportedLanguagePairs();
	$howToUse = XOOPS_URL.'/modules/'.$mydirname.'/how-to-use/'._MI_DICTIONARY_HOW_TO_USE_LINK;
}


$xoopsTpl->assign(array(
	'mode' => $mode,
	'supportedLanguages' => $getSupportedLanguage,
	'selectableLanguages' => $selectableLanguages,
	'typeId' => getTypeId($mode)
));


$userId = is_object( @$xoopsUser ) ? $xoopsUser->getVar('uid') : 0 ;
if (!$userId) {
	redirect_header(XOOPS_URL.'/');
}


$jsFiles = array(
	'util/utilities.js',
	'util/language.js',
	'dictionary-creation-main.js',
	'component/word-table-controller.js',
	'component/dictionaries-view-controller.js',
	'component/words-view-controller.js',
	'component/dialog-view-controller.js',
	'component/search-view-controller.js',
	'component/communication.js',
	'component/paginate.js'
);

$xoops_module_header = <<< EOF
<script><!--
jQuery.noConflict();
//--></script>
EOF;
$xoops_module_header .= $xoopsTpl->get_template_vars( "xoops_module_header" );
foreach ($jsFiles as $jsFile) {
	$xoops_module_header .= "\t".'<script charset="utf-8" type="text/javascript" src="'.XOOPS_URL.'/modules/'.$mydirname.'/js/'.$jsFile.'"></script>'."\n";
}
$user_define_header = '<link rel="stylesheet" type="text/css" media="screen" href="'.XOOPS_URL.'/modules/'.$mydirname.'/css/user_style.css" />';

$xoopsTpl->assign(
	array(
		'user_define_header' => $user_define_header,
		'xoops_module_header' => $xoops_module_header,
		'howToUse' => $howToUse,
		'dictionary_type_name' => getDictionaryTypeNameByMode($mode)
	)
);

$xoopsOption['template_main'] = 'dictionary_main.html';

include(XOOPS_ROOT_PATH.'/header.php');
include(XOOPS_ROOT_PATH.'/footer.php');


function getTypeId($mode) {
	$typeLists = array(
		"Dictionary" => 0,
		"ParallelText" => 1,
		"ParaphraseDictionary" => 5,
		"NormalizeDictionary" => 6,
	);
	return $typeLists[$mode];
}

function getDictionaryTypeNameByMode($mode) {
	$typeNameLists = array(
		"Dictionary" => _MI_DICTIONARY_COMMUNITY_DICTIONARY,
		"ParallelText" => _MI_DICTIONARY_COMMUNITY_PARALLEL_TEXT,
		"ParaphraseDictionary" => _MI_DICTIONARY_COMMUNITY_PARAPHRASE_DICTIONARY,
		"NormalizeDictionary" => _MI_DICTIONARY_COMMUNITY_NORMALIZE_DICTIONARY
	);
	return $typeNameLists[$mode];
}
?>