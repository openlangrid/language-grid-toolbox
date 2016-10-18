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

$xoopsOption['template_main'] = $mytrustdirname . '_' . 'list.html';


// Include Xoops header
include(XOOPS_ROOT_PATH . '/header.php');
include(dirname(__FILE__). '/include_header_resources.php');

$xoopsTpl->assign('mydirname', $mydirname);
$xoopsTpl->assign('mod_url', XOOPS_URL.'/modules/'.$GLOBALS['mydirname']);

// bread path
require_once (dirname(__FILE__).'/bread-path.php');

// langage setting
require_once (dirname(__FILE__). '/language.php');

// active user
require_once (dirname(__FILE__). '/active_users/show.php');

// messages title
require_once (dirname(__FILE__). '/messages/title.php');
// messages list
require_once (dirname(__FILE__). '/messages/_list.php');

// contents show
require_once (dirname(__FILE__). '/contents/show.php');

include(XOOPS_ROOT_PATH . '/footer.php');

?>
