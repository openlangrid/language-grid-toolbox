<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts.
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
require_once dirname(__FILE__).'/../../../../mainfile.php';
require_once XOOPS_ROOT_PATH.'/api/class/client/FileSharingClient.class.php';

$result = array(
	'status' => 'OK',
	'message' => 'Success',
	'contents' => array()
);

try {
	$filename = $_POST['fileName'];
	$basename = basename($_POST['basename']);
	$description = $_POST['description'];
	$folderId = $_POST['folderId'];
	$readPermission = $_POST['readPermission'];
	$editPermission = $_POST['editPermission'];
	
	$root = XCube_Root::getSingleton();
	$loginId = $root->mContext->mXoopsUser->get('uname');

	// $path = XOOPS_ROOT_PATH.'/uploads/'.$basename;
	// copy(XOOPS_ROOT_PATH.'/cache/'.$basename, $path);
	$path = XOOPS_ROOT_PATH.'/cache/'.$basename;

	$read = new ToolboxVO_FileSharing_Permission();
	$read->type = $_POST['readPermission'];
	$read->userId = $loginId;
	$edit = new ToolboxVO_FileSharing_Permission();
	$edit->type = $_POST['editPermission'];
	$edit->userId = $loginId;
	
	$client = new FileSharingClient();

	$result = $client->addFile($path, $filename, $description, $folderId, $read, $edit, true);

	if (strtoupper($result['status']) == 'ERROR') {
		throw new Exception($result['message']);
	}

	return $result;
} catch (Exception $e) {
	$result['message'] = 'Error';
}

echo json_encode($result);
?>