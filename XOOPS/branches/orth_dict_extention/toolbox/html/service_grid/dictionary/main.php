<?php

require_once(MYEXTPATH.'/langrid/include/Functions.php');
require_once(MYEXTPATH.'/service_grid/db/handler/UserDictionaryDbHandler.class.php');

global $wgRequest, $wgOut, $wgServer, $wgScriptPath, $wgTitle, $wgArticle, $wgLanguageCode;

$idUtil = new LanguageGridArticleIdUtil();
$dictId = $idUtil->getDictionaryIdByPageTitle($idUtil->getTitleDbKey());
$dbHandler = new UserDictionaryDbHandler();
$dictObj =& $dbHandler->getUserDictionary($dictId);
$currentLanguageArray = $dbHandler->getSupportedLanguages($dictId);

$myImagePath = LgUtil::getExtDictionaryRootPath().'/img';

/**
 *
 * Execute template engine
 *
 */
$template = new LgTemplate($wgOut, dirname(__FILE__).'/templates');

$lsm = new LgSettingManager('main');
$template->addJS($lsm->getJS());
$template->addCSS($lsm->getCSS());

$langList = array();
foreach ($currentLanguageArray as $code) {
	$langList[$code] = getLangridLanguageName($code);
}
uasort($langList,"mycmp");
function mycmp($a,$b){
	return strcasecmp($a,$b);
}
$template->assign('language_array', $langList);

/**
 *
 * Select initial language
 *
 */
$langCodes = array_keys($langList);
if (in_array($wgLanguageCode, $langCodes)) {
	$flangcd = $wgLanguageCode;
	$a = array_flip($langCodes);
	$i = $a[$wgLanguageCode];
	$slangcd = (isset($langCodes[$i+1])) ? $langCodes[$i+1] : '';
} else {
	$flangcd = $langCodes[0];
	$slangcd = $langCodes[1];
}

$languageSelectorOptions = array();

foreach ($langList as $key => $value) {
	$languageSelectorOptions[] = array(
		'tag' => $key,
		'name' => $value
	);
}

$languageSelectorOptions = json_encode($languageSelectorOptions);

$template->assign('f_selected_language_code', $flangcd);
$template->assign('s_selected_language_code', $slangcd);

$cancelurl = $wgTitle->getFullURL('action=edit&pagedict');
$selectlanguageurl = $wgTitle->getFullURL('action=edit&pagedict&selectlanguage');
$downloadurl = $wgTitle->getFullURL('action=edit&pagedict&download');
$uploadurl = $wgTitle->getFullURL('action=edit&pagedict&upload');
$template->assign('cancelurl', $cancelurl);
$template->assign('selectlanguageurl', $selectlanguageurl);
$template->assign('downloadurl', $downloadurl);
$template->assign('uploadurl', $uploadurl);

$template->assign('mode', 'Dictionary');
//$template->assign('pageId', $article->getId());
$template->assign('titledbkey', LanguageGridArticleIdUtil::getTitleDbKey());
$template->assign('pageTitle', $article->getTitle()->getText());
$template->assign('downloadUrl', $wgTitle->getFullURL('action=edit&download'));
$template->assign('uploadUrl', $wgTitle->getFullURL('action=ajax'));
$template->assign('xoops_imageurl', "$wgServer/$wgScriptPath/skins/common/langrid");

$template->assign('languageSelectorOptions', $languageSelectorOptions);

$template->assign('translationLanguagePairs', getLanguagePairs());
$template->assign('translationLanguages', getLanguages());

$template->assign('imagePath', $myImagePath);

$dictManager = new LgDictionaryManager();
$template->assign('importedDictionaries', json_encode($dictManager->getImportedDictionaries()));

$template->loadTemplate('dictionary_main.html');

function getLanguages() {
	return json_encode(GetLangridLanguageDefine());
}

function languageSort($a, $b) {
	$a = getLangridLanguageName($a);
	$b = getLangridLanguageName($b);

	return strcasecmp($a, $b);
}

function getLanguagePairs() {
	$client = new Wikimedia_LangridAccessClient();
	$pairs = $client->getSupportedTranslationLanguagePairs($wgTitle);

	$return = array();
	if (is_array($pairs['contents'])) {
		foreach ($pairs['contents'] as $pair) {
			$source = $pair[0];
			$target = $pair[1];
			$return[$source][] = $target;
		}
	}

	uksort($return, 'languageSort');
	foreach ($return as $key => $value) {
		usort($return[$key], 'languageSort');
	}

	return json_encode($return);
}
?>
