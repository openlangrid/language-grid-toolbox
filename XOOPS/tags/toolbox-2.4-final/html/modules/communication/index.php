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

require '../../mainfile.php';
require_once dirname(__FILE__).'/config.php';
$mytrustdirname = basename(dirname(__FILE__));
$mydirname = basename(dirname(__FILE__));


// login check
$userId = is_object(@$xoopsUser) ? $xoopsUser->getVar('uid') : 0 ;
if (!$userId) {
	redirect_header(XOOPS_URL.'/');
}

$xoopsTpl->assign('howToUse', XOOPS_URL.'/modules/'.$mydirname.'/how-to-use/'._MI_D3FORUM_HOW_TO_USE_LINK);

if((@$_GET['topicId'] || @$_POST['topicId']) && is_null($_GET['page'])) {

	if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'set XOOPS_TRUST_PATH in mainfile.php' ) ;
	
	require_once dirname( __FILE__ ).'/mytrustdirname.php' ; // set $mytrustdirname
	require 'helper.php';
	require_all_classes($mytrustdirname);
	
	$allow_actions = array(
		"delete", 'index', '_header'
	);

	$topicId = @$_GET['topicId'] ? @$_GET['topicId'] : @$_POST['topicId'];
	$xoopsTpl->assign('topicId', $topicId);

	get_delegator($allow_actions, "index") -> execute();

} else {
	require_once XOOPS_ROOT_PATH.'/modules/toolbox/toolbox.php';
//	$mydirname = basename( dirname( __FILE__ ) ) ;
	$mydirpath = dirname( __FILE__ ) ;
//	$mytrustdirname = basename(dirname(__FILE__));
	require XOOPS_ROOT_PATH.'/modules/'.$mytrustdirname.'/main.php' ;
}

?>