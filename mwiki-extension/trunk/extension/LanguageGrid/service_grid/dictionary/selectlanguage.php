<?php

global $wgRequest, $wgOut, $wgServer, $wgScriptPath, $wgTitle, $wgArticle;

$idUtil =& new LanguageGridArticleIdUtil();
$dictId = $idUtil->getDictionaryIdByPageTitle($idUtil->getTitleDbKey());
$dbHandler =& new UserDictionaryDbHandler();
$dictObj =& $dbHandler->getUserDictionary($dictId);
$currentLanguageArray = $dbHandler->getSupportedLanguages($dictId);

$mydirname = basename(dirname(__FILE__));

$wikiRootPath = $wgServer . $wgScriptPath;
$extRootPath = $wikiRootPath.'/extensions/LanguageGrid';
$myJsPath = $extRootPath.'/service_grid/dictionary/js';
$myCssPath = $extRootPath.'/service_grid/dictionary/css';

$jsFiles = array(
	$extRootPath.'/common/js_lib/prototype-1.6.0.3.js',
	$myJsPath.'/language-select.js'
);

$loadCss = array(
	$myCssPath.'/style.css',
	$myCssPath.'/language_select.css'
);

$_js_ = "<script charset=\"utf-8\" type=\"text/javascript\" src=\"%s\"></script>";
$_css_ = "<link rel=\"stylesheet\" type=\"text/css\" href=\"%s\" />";
foreach ($loadCss as $css) {
	$wgOut->addScript(sprintf($_css_, $css));
}
foreach ($jsFiles as $js) {
	$wgOut->addScript(sprintf($_js_, $js));
}

/**
 * 
 * Execute Smarty template engine
 * 
 */
$smarty =& new LgTemplate($wgOut, dirname(__FILE__).'/templates');
if ($dictObj == null) {
	$wgOut->setPageTitle(wfMsg('lg:Create_Page_Dictionary'));
	$smarty->assign('savebuttonlabel', wfMsg('lg:Create'));
} else {
	$wgOut->setPageTitle(wfMsg('lg:Add/Delete_Languages'));
	$smarty->assign('savebuttonlabel', wfMsg('lg:Save'));
}


/**
 * 
 * Load the language list
 * 
 */
require(dirname(__FILE__).'/../../langrid/include/Languages.php');
$smarty->assign('language_array', GetLangridLanguageDefine());
$smarty->assign('current_language_array', $currentLanguageArray);
$smarty->assign('titledbkey', LanguageGridArticleIdUtil::getTitleDbKey());
$cancelurl = $wgTitle->getFullURL('action=edit&pagedict');
$smarty->assign('cancelurl', $cancelurl);

/**
 * 
 * Destination of Submission when Save button is pressed
 * 
 */
$submiturl = $wgTitle->getFullURL('action=edit&pagedict&languageselected');
$smarty->assign('submiturl', $submiturl);

$smarty->loadTemplate('dictionary_languageselect.html');
?>
