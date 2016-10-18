<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to create
// translation templates.
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
require_once dirname(__FILE__).'/../../mainfile.php';
require_once dirname(__FILE__).'/config.php';

$userId = is_object( @$xoopsUser ) ? $xoopsUser->getVar('uid') : 0 ;

if (!$userId) {
	redirect_header(XOOPS_URL.'/');
}

// require_once(dirname(__FILE__).'/main/user-tentative-template.php');

require dirname(__FILE__).'/include/javascripts.php';

function getMlLanguage() {
	$language = 'en';

	if (isset($_GET['ml_lang']) && isValidMlLanguage($_GET['ml_lang'])) {
		$language = $_GET['ml_lang'];
	} else if (isset($_COOKIE['ml_lang']) && isValidMlLanguage($_COOKIE['ml_lang'])) {
		$language = $_COOKIE['ml_lang'];
	}

	return $language;
}

function isValidMlLanguage($language) {
	return in_array($language, array('ja', 'en', 'ko', 'zh-CN'));
}

$language = getMlLanguage();

$opts = '';
//$languagesss = XoopsLists::getLangList();
$langnames = explode(',',CUBE_UTILS_ML_LANGDESCS);
$langs = explode(',',CUBE_UTILS_ML_LANGS);
$langIndex = 0;
for ($i=0; $i < count($langs); $i++) {
    $opts .= '<option value="'.$langs[$i].'"';
    if ($langs[$i] == $language) {$opts .= ' selected'; $langIndex = $i;}
    $opts .= '>'.$langnames[$i].'</option>';
}

$timeout = time() + (30 * 24 * 60 * 60); // 30days
setcookie('ml_lang', $language, $timeout, XOOPS_COOKIE_PATH);

// set original smarty variables.
// must decleare between include(header.php) to include(footer.php)
// for language selecter
$timestamp = mktime();

$xoops_module_header = $xoopsTpl->get_template_vars( "xoops_module_header" );
foreach ($javascripts as $javascript) {
	$xoops_module_header .= "\t".'<script charset="utf-8" type="text/javascript" src="'.XOOPS_URL.'/modules/template/js'.$javascript.'?'.time().'"></script>'."\n";
}

$xoops_module_header .= '<link rel="stylesheet" type="text/css" media="screen" href="'.XOOPS_URL.'/modules/template/css/style.css" />';
$user_define_header = '<link rel="stylesheet" type="text/css" media="screen" href="'.XOOPS_URL.'/modules/template/css/user_style.css" />';

$xoopsConfig = $GLOBALS['xoopsConfig'];

$xoopsTpl->assign(array(
	'xoops_imageurl' => XOOPS_THEME_URL.'/'.$xoopsConfig['theme_set'].'/',
    'howToUse' => _MI_TEMPLATE_HOW_TO_USE,
    'user_define_header' => $user_define_header,
	'xoops_module_header' => $xoops_module_header,
	'mode' => 'Dictionary',
	'ml_lang' => $language,
	'ml_lang_index' => $langIndex,
	'opts' => $opts
));

print $xoopsTpl->fetch('db:translation-template-user-edit.html');
?>
