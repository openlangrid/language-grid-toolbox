<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts with various translation support functions.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //
$renderOption['type'] = 'noheader';

$xoopsTpl -> assign(
	'translations', 
	MachineTranslation::translateAll(
		$_POST['sourceLang'], $_POST['targetLang'],	$_POST['sourceText']
	)
);
?>