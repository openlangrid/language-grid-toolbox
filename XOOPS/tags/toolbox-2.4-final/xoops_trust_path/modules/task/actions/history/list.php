<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// manage translation tasks.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

require_once dirname(__FILE__).'/../include_header_resources.php';
require_once dirname(__FILE__). '/_list.php';

// header
$xoops_module_header  = $xoopsTpl->get_template_vars('xoops_module_header');
// load javascript
$xoops_module_header .= '<script type="text/javascript" src="'.XOOPS_MODULE_URL.'/'.$mytrustdirname.'/js/pager.js"></script>'.PHP_EOL;

$xoopsTpl->assign(array(
	'xoops_module_header' => $xoops_module_header,
));
