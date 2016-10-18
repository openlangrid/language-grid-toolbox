<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to create
// translation templates.
// Copyright (C) 2010  Department of Social Informatics, Kyoto University
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
/**
 * @author kitajima
 */
error_reporting(0);
require_once dirname(__FILE__).'/../../mainfile.php';
require_once dirname(__FILE__).'/config.php';

$userId = is_object( @$xoopsUser ) ? $xoopsUser->getVar('uid') : 0 ;
if (!$userId) {
	redirect_header(XOOPS_URL.'/');
}
$userName = $xoopsUser->getVar('uname');

if (isset($_GET['page'])) {
	preg_match('/^[a-zA-Z\-]+$/', $_GET['page'], $matches);

	$filePath = '';
	if (isset($matches[0])) {
		$filePath = dirname(__FILE__).'/main/'.$matches[0].'.php';
	}
	if (file($filePath)) {
		require_once $filePath;
		die();
	}
}

require dirname(__FILE__).'/include/javascripts.php';

$xoops_module_header = $xoopsTpl->get_template_vars( "xoops_module_header" );
foreach ($javascripts as $javascript) {
	$xoops_module_header .= "\t".'<script charset="utf-8" type="text/javascript" src="'.XOOPS_URL.'/modules/template/js'.$javascript.'?'.time().'"></script>'."\n";
}
$xoops_module_header .= '<link rel="stylesheet" type="text/css" media="screen" href="'.XOOPS_URL.'/modules/template/css/style.css" />';
$user_define_header = '<link rel="stylesheet" type="text/css" media="screen" href="'.XOOPS_URL.'/modules/template/css/user_style.css" />';

$xoopsTpl->assign(
	array(
		'user_define_header' => $user_define_header,
		'xoops_module_header' => $xoops_module_header,
		'howToUse' => _MI_TEMPLATE_HOW_TO_USE
	)
);

$xoopsOption['template_main'] = 'translation-template-main.html';

include(XOOPS_ROOT_PATH.'/header.php');
include(XOOPS_ROOT_PATH.'/footer.php');
?>