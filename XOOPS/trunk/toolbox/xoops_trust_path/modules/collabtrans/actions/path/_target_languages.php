<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts with various translation support functions.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

if(validate()) {
	die();
}

$xoopsTpl -> assign('languageTags', TranslationPath::getTargetLangs(getLoginUserUid(), @$_GET['sourceLang']));
$xoopsTpl -> assign('langMap', CommonUtil::getLanguageNameMap());

?>
<?php 
function validate() {
	$result = false;
	
	// require paramter
	if(!@$_GET['sourceLang']) $result = true;
	
	return $result;
}
?>
