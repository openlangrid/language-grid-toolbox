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

include dirname(__FILE__). '/../include_header_resources.php';
include dirname(__FILE__). '/../_header.php';
include dirname(__FILE__). '/../contents/_header.php';


$message = Com_Message::findByMessageId(@$_GET['messageId']);
$xoopsTpl -> assign(array(
	'nowdate' => CommonUtil::formatTimestamp(time(), _COM_DTFMT_YMDHI),
	'message' => $message,
	'postRevisions' => Com_PostRevision::findAllByMessageId(@$_GET['messageId']),
	'messageCount' => count(Com_MessageSimple::findAll($_GET)),
	'topicId' => $message->getTopicId()
));

if($message -> hasContent() && $message -> getContent() -> isAvailable()) {
	$xoopsTpl->assign(array(
		'content' => $message -> getContent(),
		'marker'  => $message -> getContentMarker()
	));
}
?>
