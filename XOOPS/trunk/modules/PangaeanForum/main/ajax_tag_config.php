<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides a multilingual
// BBS, where messages can be displayed in multiple languages.
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
error_reporting(E_ALL);
ini_set('display_errors', 'On');

require_once dirname(__FILE__).'/../class/tag/Tag.class.php';

try {
	$action = $_POST['action'];

	$tag = new Tag();

	$contents = false;

	switch ($action) {
//		case 'load':
//			$contents = $tag->loadTagSets();
//			break;
		case 'saveTagSet':
			$tagSetId = $_POST['tagSetId'];
			$isNew = $_POST['newFlag'] ? true : false;
			$names = $_POST['expressions'];
			$contents = $tag->saveTagSet($tagSetId, $names, $isNew);
			if (!$contents) {
				throw new Exception("save tag set in error.");
			}
			break;
		case 'deleteTagSet':
			$tagSetId = $_POST['tagSetId'];
			$contents = $tag->deleteTagSet($tagSetId);
			if (!$contents) {
				throw new Exception("delete tag set in error.");
			}
			break;
		case 'saveTag':
			$tagSetId = $_POST['tagSetId'];
			$tagId = $_POST['tagId'];
			$isNew = $_POST['newFlag'] ? true : false;
			$words = $_POST['expressions'];
			$contents = $tag->saveTag($tagSetId, $tagId, $words, $isNew);
			if (!$contents) {
				throw new Exception("save tag in error.");
			}
			break;
		case 'deleteTag':
			$tagSetId = $_POST['tagSetId'];
			$tagId = $_POST['tagId'];
			$contents = $tag->deleteTag($tagSetId, $tagId);
			if (!$contents) {
				throw new Exception("delete tag in error.");
			}
			break;
		default:
			throw new Exception("Not defined action.");
			break;
	}

	if (!$contents) {
		throw new Exception("contents is not found.");
	}

	echo json_encode(array('status'=>'OK', 'message'=>'NoError', 'contents'=>$contents));
	exit();

} catch (Exception $e) {
	echo json_encode(array('status'=>'Error', 'message'=>$e->getMessage()));
}

?>