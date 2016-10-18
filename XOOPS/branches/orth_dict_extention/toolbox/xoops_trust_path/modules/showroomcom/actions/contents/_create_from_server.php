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
 
$renderOption['type'] = 'json';

if(!$_POST['fileId'] || !$_GET['topicId']) {
	die();
}

validate();

$file = File::findById(@$_POST['fileId']);

try {
	$content = Com_ContentImage::createWithParams($_GET['topicId'], array(
		'content_title' => $_POST['content_title'],
		'uid' => getLoginUserUid(),
		'file' => $file
	));
	$content -> insert();
	
	
	print json_encode(array(
		"status" => true
	));
} catch(Exception $e) {
	print json_encode(array(
		"status" => false,
		"message" => COM_LABEL_SELECTABLE_FILE_TYPE
	));

}

function validate() {
	$checkContent = Com_Content::findByTopicIdAndTitle($_GET['topicId'], $_POST['content_title']);
	if(!is_null($checkContent)) {
		print json_encode(array(
			"status" => false,
			"message" => COM_MSG_SAME_NAME_CONTENT_ERROR
		));
		
		exit;
	}
}
?>
