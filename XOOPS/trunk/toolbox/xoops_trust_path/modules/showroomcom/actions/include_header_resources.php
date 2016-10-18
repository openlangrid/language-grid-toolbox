<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows users to share
// contents on the multilingual BBS to have a discussion over the contents
// through the BBS.
// Copyright (C) 2010  Graduate School of Informatics, Kyoto University
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
//  ------------------------------------------------------------------------ //

$xoops_module_header = <<< EOF
<script><!--
jQuery.noConflict();
jQuery(document).ready(function(){
});
//--></script>
EOF;
$xoops_module_header .= $xoopsTpl->get_template_vars("xoops_module_header");

//$xoops_module_header .= '<script charset="UTF-8" type="text/javascript" src="'.XOOPS_URL.'/modules/'.$mydirname.'/js/'.$jsPath.'.js"></script>'."\n";
$xoops_module_header .= '<link rel="stylesheet" type="text/css" media="all" href="'.XOOPS_URL.'/modules/'.$mydirname.'/css/common.css" />'."\n";
$xoops_module_header .= '<link rel="stylesheet" type="text/css" media="all" href="'.XOOPS_URL.'/modules/'.$mydirname.'/css/button.css" />'."\n";
$xoops_module_header .= '<link rel="stylesheet" type="text/css" media="all" href="'.XOOPS_URL.'/modules/'.$mydirname.'/css/communication.css" />'."\n";
$xoops_module_header .= '<link rel="stylesheet" type="text/css" media="all" href="'.XOOPS_URL.'/modules/'.$mydirname.'/css/glayer.css" />'."\n";
$xoops_module_header .= '<script type="text/javascript" src="'.XOOPS_URL.'/modules/'.$mydirname.'/js/lib/cookiemanager.js"></script>'."\n";
$xoops_module_header .= '<script type="text/javascript" src="'.XOOPS_URL.'/modules/'.$mydirname.'/js/communication_common.js"></script>'."\n";
$xoops_module_header .= '<script type="text/javascript" src="'.XOOPS_URL.'/modules/'.$mydirname.'/js/tablesort.js"></script>'."\n";
$xoops_module_header .= '<script type="text/javascript" src="'.XOOPS_URL.'/modules/'.$mydirname.'/js/glayer.js"></script>'."\n";
$xoops_module_header .= '<script type="text/javascript" src="'.XOOPS_URL.'/modules/'.$mydirname.'/js/lib/scriptaculous.js?load=effects"></script>'."\n";
$xoops_module_header .= '<script type="text/javascript" src="'.XOOPS_URL.'/modules/'.$mydirname.'/js/jsdeferred.js"></script>'."\n";
$xoops_module_header .= '<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>';
$xoops_module_header .= '<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>';


$xoopsTpl->assign("xoops_module_header", $xoops_module_header);
?>