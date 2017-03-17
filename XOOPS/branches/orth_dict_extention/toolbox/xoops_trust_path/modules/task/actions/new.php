<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// manage translation tasks.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

include dirname(__FILE__). '/include_header_resources.php';
require_once dirname(__FILE__) . '/../../collabtrans/class/translation_path.php';

$userId = $GLOBALS['userId'];

// header
$xoops_module_header  = $xoopsTpl->get_template_vars('xoops_module_header');
// load javascript
$xoops_module_header .= '<script type="text/javascript" src="'.XOOPS_MODULE_URL.'/'.$mydirname.'/js/yahoo-dom-event.js"></script>'.PHP_EOL;
$xoops_module_header .= '<script type="text/javascript" src="'.XOOPS_MODULE_URL.'/'.$mydirname.'/js/calendar-min.js"></script>'.PHP_EOL;
$xoops_module_header .= '<script type="text/javascript" src="'.XOOPS_MODULE_URL.'/'.$mydirname.'/js/glayer.js"></script>'.PHP_EOL;
$xoops_module_header .= '<script type="text/javascript" src="'.XOOPS_MODULE_URL.'/'.$mydirname.'/js/pager.js"></script>'.PHP_EOL;
$xoops_module_header .= '<script type="text/javascript" src="'.XOOPS_MODULE_URL.'/'.$mydirname.'/js/tablesort.js"></script>'.PHP_EOL;
// load css
$xoops_module_header .= '<link rel="stylesheet" type="text/css" href="'.XOOPS_MODULE_URL.'/'.$mydirname.'/css/calendar.css"/>'.PHP_EOL;
$xoops_module_header .= '<link rel="stylesheet" type="text/css" href="'.XOOPS_MODULE_URL.'/'.$mydirname.'/css/time_list.css"/>'.PHP_EOL;
$xoops_module_header .= '<link rel="stylesheet" type="text/css" href="'.XOOPS_MODULE_URL.'/'.$mydirname.'/css/glayer.css"/>'.PHP_EOL;
$xoops_module_header .= '<link rel="stylesheet" type="text/css" href="'.XOOPS_MODULE_URL.'/'.$mydirname.'/css/hover.css"/>'.PHP_EOL;

$timeList = CommonUtil::getTimeMapCache();

$xoopsTpl->assign(array(
	'xoops_module_header' => $xoops_module_header,
	'creator' => $this->serviceInfo->getName(),
	'languageTags' => TranslationPath::getSourceLangs($userId),
	'langMap' => CommonUtil::getLanguageNameMap(),
	'timeList' => $timeList
));
