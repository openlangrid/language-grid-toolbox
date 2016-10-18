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

require_once(dirname(__FILE__).'/../../IFileSharingClient.interface.php');
require_once(dirname(__FILE__).'/Toolbox_AbstractClient.class.php');
require_once(dirname(__FILE__).'/../../class/manager/Toolbox_FileSharing_FolderGetAllManager.class.php');
require_once(dirname(__FILE__).'/../../class/manager/Toolbox_FileSharing_FolderCreateEditManager.class.php');
require_once(dirname(__FILE__).'/../../class/manager/Toolbox_FileSharing_FileGetAllManager.class.php');
require_once(dirname(__FILE__).'/../../class/manager/Toolbox_FileSharing_FileCreateEditManager.class.php');

class FileSharingClient extends Toolbox_AbstractClient implements IFileSharingClient {

	public function __construct() {
		parent::__construct();
	}

	/**
	 *
	 * @param [$folderId]
	 * @param [$offset]
	 * @param [$limit]
	 * @return ToolboxVO_FileSharing_File[]
	 */
	public function getAllFiles($folderId,$offset = null,$limit=null) {
		$manager = new Toolbox_FileSharing_FileGetAllManager();
		return $manager->getFileList($folderId,$offset,$limit);
	}

	/**
	 *
	 * @param [$id]
	 * @return ToolboxVO_FileSharing_File
	 */
	public function getFile($id) {
		$manager = new Toolbox_FileSharing_FileGetAllManager();
		return $manager->getFile($id);
	}

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
	public function addFile($path,$name,$description,$folderId,$readPermission,$editPermission,$overwrite=false) {
		$manager = new Toolbox_FileSharing_FileCreateEditManager();
		return $manager->create($path,$name,$description,$folderId,$readPermission,$editPermission,$overwrite);
	}

	/**
	 *
	 * @param [$id]
	 * @return void
	 */
	public function deleteFile($id){
		$manager = new Toolbox_FileSharing_FileCreateEditManager();
		return $manager->remove($id);
	}

	/**
	 *
	 * @param [$id]
	 * @param [$description]
	 * @param [$folderId]
	 * @param [$readPermission]
	 * @param [$editPermission]
	 * @return void
	 */
	public function updateFile($id,$description,$folderId,$readPermission,$editPermission) {
		$manager = new Toolbox_FileSharing_FileCreateEditManager();
		return $manager->update($id,$description,$folderId,$readPermission,$editPermission);
	}

	/**
	 *
	 * @param [$parentId]
	 * @return ToolboxVO_FileSharing_FileFolder[]
	 */
	public function getAllFolders($parentId=null){
		$manager = new Toolbox_FileSharing_FolderGetAllManager();
		return $manager->getFolderList($parentId);
	}

	/**
	 *
	 * @param [$id]
	 * @return ToolboxVO_FileSharing_FileFolder
	 */
	public function getFolder($id){
		$manager = new Toolbox_FileSharing_FolderGetAllManager();
		return $manager->getFolder($id);
	}

	/**
	 *
	 * @param [$name]
	 * @param [$description]
	 * @param [$parentId]
	 * @param [$readPermission]
	 * @param [$editPermission]
	 * @return ToolboxVO_FileSharing_FileFolder
	 */
	public function addFolder($name,$description,$parentId,$readPermission,$editPermission) {
		$manager = new Toolbox_FileSharing_FolderCreateEditManager();
		return $manager->create($name,$description,$parentId,$readPermission,$editPermission);
	}

	/**
	 *
	 * @param [$id]
	 * @return void
	 */
	public function deleteFolder($id) {
		$manager = new Toolbox_FileSharing_FolderCreateEditManager();
		return $manager->remove($id);
	}

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
	public function updateFolder($id,$name,$description,$parentId,$readPermission,$editPermission) {
		$manager = new Toolbox_FileSharing_FolderCreateEditManager();
		return $manager->update($id,$name,$description,$parentId,$readPermission,$editPermission);
	}
}
?>
