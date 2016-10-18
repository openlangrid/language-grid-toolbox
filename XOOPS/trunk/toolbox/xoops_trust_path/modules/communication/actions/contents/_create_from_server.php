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

try {
	$error = 0;
	$errcount = 0;

	set_time_limit(0);
	for($i=0; $i<count($_POST['fileId']); $i++) {	
		$file = File::findById(@$_POST['fileId'][$i]);
		
		$title = '';
		if(!$_POST['content_title']) {
			$title = $file -> getName();
		}else {
			$title = $_POST['content_title'].':'.makeNum($i+1);
		}
		
		$error = validate($title);
		if($error==1) {
			continue;
			$errcount++;
		}
		
		$content = Com_ContentImage::createWithParams($_GET['topicId'], array(
			'content_title' => $title,
			'uid' => getLoginUserUid(),
			'file' => $file
		));
		$content -> insert();		
		sleep(1);
	}
	
	print json_encode(array(
		"status" => true,
	));
	
} catch(Exception $e) {
	print json_encode(array(
		"status" => false,
		"message" => COM_LABEL_SELECTABLE_FILE_TYPE
	));

}


function makeNum($num) {
	$buf = $num;
	while(strlen($buf)<4) {
		$buf = '0'.$buf;
	}
	
	return $buf;
}


function validate($title) {
	$checkContent = Com_Content::findAvailableContentsByTopicIdAndTitle($_GET['topicId'], $title);
	if(!is_null($checkContent)) {
		return 1;
	}else {
		return 0;
	}
}
?>
