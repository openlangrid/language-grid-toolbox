<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts with various translation support functions.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //
$xoopsTpl -> assign(array(
	'dictionaries' => GlossaryItem::findAll($_POST),
	'sourceLanguage' => CommonUtil::toLanguageAsName($_POST['sourceLang']),
	'targetLanguage' => CommonUtil::toLanguageAsName($_POST['targetLang'])
));
?>