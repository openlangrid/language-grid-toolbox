<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts with various translation support functions.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //
include(dirname(__FILE__). '/include_header_resources.php');
$renderOption['template'] = $service -> moduleName . '_index.html';

$xoopsTpl -> assign('languageTags', TranslationPath::getSourceLangs(getLoginUserUid()));
$xoopsTpl -> assign('langMap', CommonUtil::getLanguageNameMap());
$xoopsTpl -> assign('loginId', getCurrentUserLoginId());


if(validate()) {
	
	try {
		$data = file_get_contents($_FILES['xml']['tmp_name']);
		if($data) {
			$xoopsTpl -> assign('document', WorkDocument::createFromXML($data));
		}
	} catch(Exception $e) {
		$xoopsTpl -> assign('parseError', true);
	}
}

function validate() {
	if(!@$_FILES['xml']) return false;
	
	return file_exists($_FILES['xml']['tmp_name']);
}
?>