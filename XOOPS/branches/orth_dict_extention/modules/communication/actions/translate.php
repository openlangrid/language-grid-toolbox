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

$xoopsOption['template_main'] = $mytrustdirname . '_translate.html';
require_once( dirname(__FILE__) . '/language.php');
require_once( dirname(__FILE__) . '/../class/util/user.php');

// Include XOOPS header
include(XOOPS_ROOT_PATH . '/header.php');
include(dirname(__FILE__). '/include_header_resources.php');
$xoopsTpl -> assign('mod_url', XOOPS_URL.'/modules/'.$GLOBALS['mydirname']);

$message = $_POST['message'];
$parentId = $_REQUEST['parentId'];
$attachementContentId = $_POST['contentId'];
$marker = $_POST['marker'];

if($_SERVER["REQUEST_METHOD"] == "POST"){
	if($topicId == null || $message == null || $message['body'] == null){
		die();
	}
}

$xoopsTpl -> assign('message', $message);
$xoopsTpl -> assign('parentId', $parentId);
$xoopsTpl -> assign('attachementContentId', $attachementContentId);
$xoopsTpl -> assign('marker', $marker);

if(false/* 原文 check($_POST['message']) */){
	die();
}

//User info
$uid = $xoopsUser->getVar('uid');
$user = new User($uid);
$xoopsTpl -> assign('xoopsUser', $xoopsUser);
$xoopsTpl -> assign('user', $user);

//date info
$nowdate = time();
$xoopsTpl -> assign('nowdate', $nowdate);

// bread path
require_once (dirname(__FILE__).'/bread-path.php');

// active user
require_once (dirname(__FILE__). '/active_users/show.php');

// messages title
require_once ('messages/title.php');
// messages list
require_once ('messages/list.php');
// contents show
include 'contents/show.php';

include(XOOPS_ROOT_PATH . '/footer.php');
?>


