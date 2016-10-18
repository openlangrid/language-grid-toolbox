<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user
// to open or save files on the File Sharing function.
// Copyright (C) 2010  NICT Language Grid Project
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
/* $Id: include.php 4583 2010-10-13 09:53:46Z yoshimura $ */

require_once(dirname(__FILE__).'/include_js.php');
$xoops_module_header = $xoopsTpl->get_template_vars('xoops_module_header');
foreach ($dialogjavascripts as $js) {
	$xoops_module_header .= "\t".'<script charset="utf-8" type="text/javascript" src="'.XOOPS_URL.'/modules/apfilesharing/dialog'.$js.'"></script>'."\n";
}
$xoops_module_header .= '<link rel="stylesheet" type="text/css" media="screen" href="'.XOOPS_URL.'/modules/apfilesharing/dialog/css/apfilesharingdialog.css" />';
$xoops_module_header .= '<link rel="stylesheet" type="text/css" media="screen" href="'.XOOPS_URL.'/modules/apfilesharing/dialog/css/glayer.css" />';

$xoopsTpl->assign('xoops_module_header', $xoops_module_header);

?>
