<?php
//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Toolbox. This provides a series of
// multilingual collaboration tools.
// Copyright (C) 2009  NICT Language Grid Project
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
require_once(dirname(__FILE__).'/Toolbox_ObjectGenericHandler.class.php');

class FileSharing_FilesObject extends XoopsSimpleObject {
	function FileSharing_FilesObject() {
		$this->initVar('lid', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('cid', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('title', XOBJ_DTYPE_STRING, '', true, 255);
		$this->initVar('ext', XOBJ_DTYPE_STRING, '', true, 10);
		$this->initVar('submitter', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('status', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('date', XOBJ_DTYPE_INT, time(), false);
		$this->initVar('description', XOBJ_DTYPE_STRING, '', true);
		$this->initVar('create_date', XOBJ_DTYPE_INT, time(), true);
		$this->initVar('edit_date', XOBJ_DTYPE_INT, time(), false);
		$this->initVar('read_permission_type', XOBJ_DTYPE_STRING, 'public', true, 30);
		$this->initVar('read_permission_user', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('edit_permission_type', XOBJ_DTYPE_STRING, 'public', true, 30);
		$this->initVar('edit_permission_user', XOBJ_DTYPE_INT, 0, false);
	}
}

class FileSharing_FilesHandler extends XoopsObjectGenericHandler {
	var $mTable = "filesharing_files";
	var $mPrimary = "lid";
	var $mClass = "FileSharing_FilesObject";
}
?>