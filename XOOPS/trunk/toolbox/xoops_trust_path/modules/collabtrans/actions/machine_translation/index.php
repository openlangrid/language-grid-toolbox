<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts with various translation support functions.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //
$renderOption['type'] = 'noheader';

$xoopsTpl -> assign('languageTags', TranslationPath::getSourceLangs(getLoginUserUid()));
$xoopsTpl -> assign('langMap', CommonUtil::getLanguageNameMap());
$xoopsTpl -> assign('translations',	MachineTranslation::translateAll($_GET['sourceLang'], $_GET['targetLang'], ''));
?>