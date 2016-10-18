<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts with various translation support functions.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

include(dirname(__FILE__). '/include_header_resources.php');

$xoopsTpl -> assign('languageTags', TranslationPath::getSourceLangs(getLoginUserUid()));
$xoopsTpl -> assign('langMap', CommonUtil::getLanguageNameMap());
$xoopsTpl -> assign('loginId', getCurrentUserLoginId());

if(@$_GET['file_id']) {
	$file = File::findById($_GET['file_id']);
	if(!$file -> canRead()) {
		$xoopsTpl -> assign('parseError', true);
		
	} else {
	
		try {
			
			$xoopsTpl -> assign('document', WorkDocument::createFromFile($file));
			$xoopsTpl -> assign('parentId', $file -> getFolderId());
			
		} catch(Exception $e) {
			
			$xoopsTpl -> assign('parseError', true);
		}
	}
}
?>