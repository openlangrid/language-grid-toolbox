<?php
include(dirname(__FILE__). '/include_header_resources.php');

require_once(dirname(__FILE__). '/_list.php');

// header
$xoops_module_header  = $xoopsTpl->get_template_vars('xoops_module_header');
// load javascript
$xoops_module_header .= '<script type="text/javascript" src="'.XOOPS_MODULE_URL.'/'.$mytrustdirname.'/js/pager.js"></script>'.PHP_EOL;

$xoopsTpl->assign("xoops_module_header", $xoops_module_header);
