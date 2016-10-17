<?php
//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Extension. This provides MediaWiki
// extensions with access to the Language Grid.
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
global $wgRequest, $wgOut, $wgServer, $wgScriptPath, $wgTitle, $wgArticle;

require_once(dirname(__FILE__).'/../LanguageGrid.smarty.php');
require_once(dirname(__FILE__).'/class/PathSettingWrapperClass.php');
//require_once(dirname(__FILE__).'/class/UserDictionaryClass.php');
require_once(dirname(__FILE__).'/class/DefaultDictionariesClass.php');
require_once(MYEXTPATH.'/service_grid/db/handler/LangridServicesDbHandler.class.php');
require(dirname(__FILE__).'/include/Functions.php');

$mydirname = basename(dirname(__FILE__));

$wikiRootPath = $wgServer . $wgScriptPath;
$extRootPath = $wikiRootPath.'/extensions/LanguageGrid';
$myJsPath = $extRootPath.'/langrid/js';
$myCssPath = $extRootPath.'/langrid/css';

$jsFiles = array(
	$extRootPath.'/common/js_lib/prototype-1.6.0.3.js',
	$myJsPath.'/util/form.js',
	$myJsPath.'/util/utilities.js',
	$myJsPath.'/util/language.js',
	$myJsPath.'/langrid.js',
	$myJsPath.'/langrid-setting.js',
	$myJsPath.'/langrid-service-panel.js',
	$myJsPath.'/langrid-translation-path-panel.js',
	$myJsPath.'/default-morphological-analyzer.js',
	$myJsPath.'/langrid-dictionary-popup-panel.js',
	$myJsPath.'/langrid-translation-options-popup-panel.js',
	$myJsPath.'/langrid-page-setting.js',
	$myJsPath.'/langrid-page-path-panel.js'
);

$loadCss = array(
	$myCssPath.'/style.css',
	$myCssPath.'/module.css',
	$myCssPath.'/langrid-setting-module.css'
);

$_js_ = "<script charset=\"utf-8\" type=\"text/javascript\" src=\"%s\"></script>";
$_css_ = "<link rel=\"stylesheet\" type=\"text/css\" href=\"%s\" />";
foreach ($loadCss as $css) {
	$wgOut->addScript(sprintf($_css_, $css));
}
foreach ($jsFiles as $js) {
	$wgOut->addScript(sprintf($_js_, $js));
}

$smarty =& new LanguageGridSmartyBySetting();
$langridClass =& new LangridServicesDbHandler();

$supportLangs = $langridClass->getTranslatorAllSupportLanguagePairs();
$smarty->assign('supportLangs', $supportLangs);
$translationServices = $langridClass->getTranslators();
foreach ($translationServices as &$item) {
	$item['langPare'] = languagePair($item['supported_languages_paths']);
}
$smarty->assign('translationServices', $translationServices);
$analyzerServices = $langridClass->getAnalyses();
$smarty->assign('analyzerServices', $analyzerServices);
$dictionaryServices = $langridClass->getDictionarys();
foreach ($dictionaryServices as &$item) {
	$item['langPare'] = languagePair($item['supported_languages_paths']);
	$title = Title::makeTitle(NS_LG_RESOURCES, $item['service_name']);
	$item['resource_url'] = $title->getLocalUrl();
}
$smarty->assign('dictionaryServices', $dictionaryServices);
$smarty->assign(
	array(
//		'articleId' => $wgArticle->getId(),
		'titledbkey' => LanguageGridArticleIdUtil::getTitleDbKey(),
		'max_dict_count' => 5,
		'tab_page' => 'page'
	)
);
$html = $smarty->fetch('langridmain.html');
$wgOut->addHTML($html);

function httpAutoLink($text){
	return ereg_replace("(https?|ftp)(://[[:alnum:]\+\$\;\?\.%,!#~*/:@&=_-]+)",
				 "<a href=\"\\1\\2\" target=\"_blank\">\\1\\2</a>" , $text);
}

//function mylog($text) {
//	list($micro, $Unixtime) = explode(" ", microtime());
//	$sec = $micro + date("s", $Unixtime);
//	$mil = date("Y/m/d g:i:", $Unixtime).$sec;
//
//	$msg = $mil." ".$text.PHP_EOL;
//	$fno = fopen(dirname(__FILE__).'/access.log', 'a');
//	fwrite($fno, $msg);
//	fclose($fno);
//}
?>
