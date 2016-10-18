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

interface IFileSharingClient {

	/**
	 *
	 * @param [$folderId]
	 * @param [$offset]
	 * @param [$limit]
	 * @return ToolboxVO_FileSharing_File[]
	 */
	public function getAllFiles($folderId,$offset = null,$limit=null);

	/**
	 *
	 * @param [$id]
	 * @return ToolboxVO_FileSharing_File
	 */
	public function getFile($id);

	/**
	 *
	 * @param [$path]
	 * @param [$name]
	 * @param [$description]
	 * @param [$folderId]
	 * @param [$readPermission]
	 * @param [$editPermission]
	 * @return void
	 */
	public function addFile($path,$name,$description,$folderId,$readPermission,$editPermission,$overwrite=false);

	/**
	 *
	 * @param [$id]
	 * @return void
	 */
	public function deleteFile($id);
	
	/**
	 *
	 * @param [$id]
	 * @param [$description]
	 * @param [$folderId]
	 * @param [$readPermission]
	 * @param [$editPermission]
	 * @return void
	 */
	public function updateFile($id,$description,$folderId,$readPermission,$editPermission);
	
	/**
	 *
	 * @param [$parentId]
	 * @return ToolboxVO_FileSharing_FileFolder[]
	 */
	public function getAllFolders($parentId=null);

	/**
	 *
	 * @param [$id]
	 * @return ToolboxVO_FileSharing_FileFolder
	 */
	public function getFolder($id);

	/**
	 *
	 * @param [$name]
	 * @param [$description]
	 * @param [$parentId]
	 * @param [$readPermission]
	 * @param [$editPermission]
	 * @return ToolboxVO_FileSharing_FileFolder
	 */
	public function addFolder($name,$description,$parentId,$readPermission,$editPermission);

	/**
	 *
	 * @param [$id]
	 * @return void
	 */
	public function deleteFolder($id);

	/**
	 *
	 * @param [$id]
	 * @param [$name]
	 * @param [$description]
	 * @param [$parentId]
	 * @param [$readPermission]
	 * @param [$editPermission]
	 * @return ToolboxVO_FileSharing_FileFolder
	 */
	public function updateFolder($id,$name,$description,$parentId,$readPermission,$editPermission);
	
}


class ToolboxVO_FileSharing_File {
	var $id;
	var $name;
	var $description;
	var $path;
	var $folderId;
	var $creationDate;
	var $updateDate;
	var $owner;
	var $readPermission;
	var $editPermission;
}

class ToolboxVO_FileSharing_FileFolder {
	var $id;
	var $name;
	var $description;
	var $parentId;
	var $creationDate;
	var $updateDate;
	var $owner;
	var $readPermission;
	var $editPermission;
}

class ToolboxVO_FileSharing_Permission {
	var $type;
	var $userId;
}


?>
