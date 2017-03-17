<?php
// ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts with various translation support functions.
// Copyright (C) 2010  CITY OF KYOTO
// ------------------------------------------------------------------------ //

$xoops_module_header = <<< EOF
<script><!--
jQuery.noConflict();
jQuery(document).ready(function(){
});
//--></script>
EOF;
$xoops_module_header .= $xoopsTpl->get_template_vars("xoops_module_header");

$xoops_module_header .= '<link rel="stylesheet" type="text/css" media="all" href="'.XOOPS_URL.'/modules/'.$mydirname.'/css/common.css" />'."\n";
$xoops_module_header .= '<link rel="stylesheet" type="text/css" media="all" href="'.XOOPS_URL.'/modules/'.$mydirname.'/css/button.css" />'."\n";
$xoops_module_header .= '<link rel="stylesheet" type="text/css" media="all" href="'.XOOPS_URL.'/modules/'.$mydirname.'/css/translate.css" />'."\n";
$xoops_module_header .= '<link rel="stylesheet" type="text/css" media="all" href="'.XOOPS_URL.'/modules/'.$mydirname.'/css/glayer.css" />'."\n";


//$xoops_module_header .= '<script type="text/javascript" src="'.XOOPS_URL.'/modules/'.$mydirname.'/js/lib/cookiemanager.js"></script>'."\n";
$xoops_module_header .= '<script type="text/javascript" src="'.XOOPS_URL.'/modules/'.$mydirname.'/js/effects.js"></script>'."\n";
$xoops_module_header .= '<script type="text/javascript" src="'.XOOPS_URL.'/modules/'.$mydirname.'/js/trans_common.js"></script>'."\n";
$xoops_module_header .= '<script type="text/javascript" src="'.XOOPS_URL.'/modules/'.$mydirname.'/js/glayer.js"></script>'."\n";
$xoops_module_header .= '<script type="text/javascript" src="'.XOOPS_URL.'/modules/'.$mydirname.'/js/jsdeferred.js"></script>'."\n";
$xoops_module_header .= '<script type="text/javascript" src="'.XOOPS_URL.'/modules/'.$mydirname.'/js/pager.js"></script>'."\n";
$xoops_module_header .= '<script type="text/javascript" src="'.XOOPS_URL.'/modules/'.$mydirname.'/js/tablesort.js"></script>'."\n";

$xoopsTpl->assign("xoops_module_header", $xoops_module_header);
?>