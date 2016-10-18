<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides user management
// functions.
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

require_once dirname(__FILE__).'/../../../mainfile.php';

$userId = is_object( @$xoopsUser ) ? $xoopsUser->getVar('uid') : 0 ;
if (!$userId) {
	redirect_header(XOOPS_URL.'/');
}

$xoopsOption['template_main'] = 'user_search_main.html';
include(XOOPS_ROOT_PATH.'/header.php');

$xoops_module_header = <<< EOF
<script><!--
jQuery.noConflict();
//--></script>
EOF;
$xoops_module_header .= $xoopsTpl->get_template_vars( "xoops_module_header" );
$xoops_module_header .= "\t".'<script type="text/javascript" src="'.XOOPS_URL.'/modules/user/search/js/search.js"></script>'."\n";
$xoops_module_header .= "\t".'<script type="text/javascript" src="'.XOOPS_URL.'/modules/user/search/js/tablesort.js"></script>'."\n";

$xoops_module_header .= "\t".'<link type="text/css"rel="stylesheet"  href="'.XOOPS_URL.'/modules/user/search/css/search.css">'."\n";


require_once dirname(__FILE__)."/class/manager/UserSearchManager.class.php";

$userSearchManager = new UserSearchManager();
$userData = $userSearchManager->getUserProfile();
$title = $userSearchManager->getTitles();

$xoopsTpl->assign(array("title"=>$title, "userData"=>$userData));
$xoopsTpl->assign(array(
		'xoops_module_header' => $xoops_module_header
	)
);

// decleare is end
include(XOOPS_ROOT_PATH.'/footer.php');



?>
