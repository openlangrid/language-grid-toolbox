<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts with various translation support functions.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

$xoopsTpl -> assign('sourceLang', CommonUtil::toLangugePair($_GET['sourceLang']));
$xoopsTpl -> assign('targetLang', CommonUtil::toLangugePair($_GET['targetLang']));
$xoopsTpl -> assign('sourceText', $_GET['keyword']);
?>
