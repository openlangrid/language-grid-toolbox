<?php 

function getSupportedLanguagePairs() {
	$file = XOOPS_ROOT_PATH.'/modules/langrid/include/Languages.php';
	if (file_exists($file)) {
		require($file);
		$langs = $LANGRID_LANGUAGE_ARRAY;
	} else {
		$langs = array(
			'ar' , 'bg' , 'ca' , 'cs' ,	'da' , 'de' , 'el' ,'en' ,
			'es' ,'et' ,'fi' ,'fr' ,'gl' ,'hi' ,'hr' ,'hu' ,'id' ,
			'it' ,'iw' ,'ja' ,'ko' ,'lt' ,'lv' ,'mt' ,'nl' ,'no' ,
			'pl' ,'pt' ,'ro' ,'ru' ,'sk' ,'sl' ,'sq' ,'sr' ,'sv' ,
			'th' ,'tl' ,'tr' ,'uk' ,'vi' ,'zh'
		);
	}
	
	$langs = array_unique($langs);
	asort($langs);
	return $langs;
}

function getSupportedLanguages() {
	return array_keys(getSupportedLanguagePairs());
}

// 言い換え辞書用に全てのサポート言語に言語コード-simpleを追加したサポート言語リストを返す
function getSupportedLanguagesWithSimple() {
	return array_keys(getSupportedLanguagePairsWithSimple());
}

function getSupportedLanguagePairsWithSimple() {
	$langs = getSupportedLanguagePairs();
	$langs = appendLanguagesForParaphraseDictionary($langs);
	ksort($langs);
	return $langs;
}

function appendLanguagesForParaphraseDictionary($langPairs) {
	$addLangs = array();
	foreach($langPairs as $lang => $langName) {
		$addLangs[$lang.'-simple'] = "Alternative {$langName}";
	}
	return array_merge($langPairs, $addLangs);
}

// 表記統合辞書用に全てのサポート言語に言語コード-normalizedを追加したサポート言語リストを返す
function getSupportedLanguagesWithNormalized() {
	return array_keys(getSupportedLanguagePairsWithNormalized());
}

function getSupportedLanguagePairsWithNormalized() {
	$langs = getSupportedLanguagePairs();
	$langs = appendLanguagesForNormalizeDictionary($langs);
	ksort($langs);
	return $langs;
}

function appendLanguagesForNormalizeDictionary($langPairs) {
	$addLangs = array();
	foreach($langPairs as $lang => $langName) {
		$addLangs[$lang.'-normalized'] = "Normalized {$langName}";
	}
	return array_merge($langPairs, $addLangs);
}

// 引数の言語が全てサポート言語であるか
function isAllSupportedLanguage($languages, $typeId = 0) {
	$supportLangs = getSupportedLanguages();
	if($typeId == 5) {
		$supportLangs = getSupportedLanguagesWithSimple();
	} else if($typeId == 6) {
		$supportLangs = getSupportedLanguagesWithNormalized();
	}

	foreach ($languages as $language) {
		if (!in_array($language, $supportLangs)) {
			return false;
		}
	}
	return true;
}

function isSupportedLanguage($language, $typeId = 0) {
	if($typeId == 5) {
		return in_array($language, getSupportedLanguagesWithSimple());
	} else if($typeId == 6) {
		return in_array($language, getSupportedLanguagesWithNormalized());
	} else {
		return in_array($language, SupportedLanguages());		
	}
}
?>
