<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts with various translation support functions.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

$xoopsTpl -> assign(array(
	'histories' => WorkHistory::createFromParams($_POST['history'], array(
		'create_date' => @$_POST['create_date'],
		'creator' => @$_POST['creator']
	)),
	'sourceLanguage' => CommonUtil::toLanguageAsName($_POST['sourceLang']),
	'targetLanguage' => CommonUtil::toLanguageAsName($_POST['targetLang'])
));

?>