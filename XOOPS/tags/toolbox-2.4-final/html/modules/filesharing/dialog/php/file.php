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
/* $Id: file.php 4583 2010-10-13 09:53:46Z yoshimura $ */

require_once('../../../../mainfile.php');
require_once(XOOPS_ROOT_PATH.'/modules/collabtrans/helper.php');
require_once(XOOPS_TRUST_PATH.'/modules/collabtrans/class/file.php');
require_once XOOPS_TRUST_PATH.'/modules/collabtrans/class/common_util.php';
require_once(XOOPS_ROOT_PATH.'/api/class/client/ProfileClient.class.php');

define('_COM_DTFMT_YMDHI', 'Y/m/d H:i');

$cid = 0;
if (isset($_GET['cid'])) {
	$cid = $_GET['cid'];
}

$response = array();

$isRootEdit = true;

$response['list'] = array();
if ($cid > 1) {
	$a = Folder::findById($cid);
} else {
	$a = Folder::getRoot();
	$cid = 1;

	$root = XCube_Root::getSingleton();
	$isadmin = $root->mContext->mXoopsUser->isAdmin();
	if (!$isadmin) {
		$handler = xoops_gethandler('config');
		$filesharing_moduleConfig = $handler->getConfigsByDirname('filesharing');
		$filesharing_rootedit = $filesharing_moduleConfig['filesharing_rootedit'];
		if ($filesharing_rootedit != '1') {
			$isRootEdit = false;
		}
	}
}
foreach ($a->getChilds() as $f) {
	$response['list'][] = FileSharingDialog_File_Utils::o2a($f);
}

$response['current'] = $a->getId();
$response['parents'] = array();
foreach ($a->getParents() as $p) {
	$response['parents'][] = FileSharingDialog_File_Utils::o2a($p);
}
$root = FileSharingDialog_File_Utils::o2a($a);
if (!$isRootEdit) {
	$root['canEdit'] = false;
}
$response['parents'][] = $root;


header('Content-Type: application/json; charset=utf-8;');
echo json_encode($response);
exit();

class FileSharingDialog_File_Utils {
	static function o2a($a) {
		return array(
			'type' => is_a($a, 'Folder') ? 'folder' : 'file',
			'id' => $a->getId(),
			'name' => $a->getName(),
			'description' => $a->getDescription(),
			'updateDatetime' => $a->getUpdateDateAsFormatString(),
			'updater' => $a->getUserName(),
			'readPermission' => $a->getReadPermission(),
			'editPermission' => $a->getWritePermission(),
			'canRead' => $a->canRead(),
			'canEdit' => $a->canWrite(),
	)	;
	}
}
?>
