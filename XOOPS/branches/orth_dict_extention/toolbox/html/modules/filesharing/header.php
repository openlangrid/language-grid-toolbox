<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to share
// files with other users.
// Copyright (C) 2009  NICT Language Grid Project
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
include '../../mainfile.php' ;
require_once XOOPS_ROOT_PATH.'/modules/toolbox/toolbox.php';

$mydirname = basename( dirname( __FILE__ ) ) ;
include XOOPS_ROOT_PATH."/modules/".$mydirname."/include/read_configs.php" ;
include XOOPS_ROOT_PATH."/modules/".$mydirname."/include/get_perms.php" ;
include_once XOOPS_ROOT_PATH."/modules/".$mydirname."/include/functions.php" ;
include_once XOOPS_ROOT_PATH."/modules/".$mydirname."/include/draw_functions.php" ;
include_once XOOPS_ROOT_PATH."/modules/".$mydirname."/include/gtickets.php" ;

$xoops_module_header = <<< EOF
<script><!--
jQuery.noConflict();
//--></script>
EOF;
$xoops_module_header .= '<link rel="stylesheet" type="text/css" href="'.XOOPS_URL.'/modules/'.$mydirname.'/css/main.css" />'."\n";
$xoops_module_header .= '<link rel="stylesheet" type="text/css" href="'.XOOPS_URL.'/modules/'.$mydirname.'/css/user_style.css" />'."\n";
$xoops_module_header .= '<script type="text/javascript" src="'.XOOPS_URL.'/modules/'.$mydirname.'/js/jquery.blockUI.js"></script>'."\n";
$xoops_module_header .= '<script type="text/javascript" src="'.XOOPS_URL.'/modules/'.$mydirname.'/js/jquery.MultiFile.js"></script>'."\n";
$xoops_module_header .= '<script type="text/javascript" src="'.XOOPS_URL.'/modules/'.$mydirname.'/js/jquery.form.js"></script>'."\n";
$xoops_module_header .= '<script type="text/javascript" src="'.XOOPS_URL.'/modules/'.$mydirname.'/js/jquery.MetaData.js"></script>'."\n";
$xoops_module_header .= '<script type="text/javascript" src="'.XOOPS_URL.'/modules/'.$mydirname.'/js/documentation.js"></script>'."\n";
$xoops_module_header .= '<script type="text/javascript" src="'.XOOPS_URL.'/modules/'.$mydirname.'/js/functions.js"></script>'."\n";

$xoopsTpl->assign( 'xoops_module_header' , $xoops_module_header ) ;
$xoopsTpl->assign( 'mydirname' , $mydirname ) ;

$howToUse = XOOPS_URL.'/modules/'.$mydirname.'/how-to-use/'._MD_ALBM_HOW_TO_USE_LINK;
$xoopsTpl->assign( 'howToUse' , $howToUse ) ;

include 'include/assign_globals.php' ;
$xoopsTpl->assign( $filesharing_assign_globals ) ;
?>