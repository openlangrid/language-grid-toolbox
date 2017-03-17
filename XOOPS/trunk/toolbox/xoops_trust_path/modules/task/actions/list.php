<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// manage translation tasks.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

include(dirname(__FILE__). '/include_header_resources.php');
require_once dirname(__FILE__) . '/../../collabtrans/class/translation_path.php';

require_once (dirname(__FILE__). '/_list.php');

// header
$xoops_module_header  = $xoopsTpl->get_template_vars('xoops_module_header');
// load javascript
$xoops_module_header .= '<script type="text/javascript" src="'.XOOPS_MODULE_URL.'/'.$mytrustdirname.'/js/pager.js"></script>'.PHP_EOL;
$xoops_module_header .= '<script type="text/javascript" src="'.XOOPS_MODULE_URL.'/'.$mytrustdirname.'/js/glayer.js"></script>'.PHP_EOL;
// load css
$xoops_module_header .= '<link rel="stylesheet" type="text/css" href="'.XOOPS_MODULE_URL.'/'.$mytrustdirname.'/css/glayer.css"/>'.PHP_EOL;

// language list
// $languageList = CommonUtil::getLanguageNameMap();
$languageTags = TranslationPath::getSourceLangs(getLoginUserUid());

// work list
$workList = array(
	_MD_TASK_SMOOTHING,
	_MD_TASK_CHECK
);

$xoopsTpl->assign(array(
	'xoops_module_header' => $xoops_module_header,
	'searchMethods' => CommonUtil::getSearchMethods(),
	'languageTags' => $languageTags,
	'langMap' => CommonUtil::getLanguageNameMap(),
	'workList' => $workList,
	'symbolList' => CommonUtil::getSymbolMapCache()
));
