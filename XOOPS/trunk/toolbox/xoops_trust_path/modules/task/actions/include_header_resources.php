<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// manage translation tasks.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

// header
$xoops_module_header = <<< EOF
<script><!--
jQuery.noConflict();
jQuery(document).ready(function(){
});
//--></script>
EOF;
$xoops_module_header .= $xoopsTpl->get_template_vars("xoops_module_header");

// load css
$xoops_module_header .= '<link rel="stylesheet" type="text/css" media="all" href="'.XOOPS_MODULE_URL.'/'.$mydirname.'/css/common.css" />'."\n";
$xoops_module_header .= '<link rel="stylesheet" type="text/css" media="all" href="'.XOOPS_MODULE_URL.'/'.$mydirname.'/css/button.css" />'."\n";
$xoops_module_header .= '<link rel="stylesheet" type="text/css" media="all" href="'.XOOPS_MODULE_URL.'/'.$mydirname.'/css/task.css" />'."\n";
$xoops_module_header .= '<link rel="stylesheet" type="text/css" media="all" href="'.XOOPS_MODULE_URL.'/'.$mydirname.'/css/hover.css" />'."\n";

// load javascript
$xoops_module_header .= '<script type="text/javascript" src="'.XOOPS_MODULE_URL.'/'.$mydirname.'/js/common.js"></script>'."\n";
$xoops_module_header .= '<script type="text/javascript" src="'.XOOPS_MODULE_URL.'/'.$mydirname.'/js/validation.js"></script>'."\n";

$xoopsTpl->assign("xoops_module_header", $xoops_module_header);
