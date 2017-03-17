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

require_once(dirname(__FILE__).'/Toolbox_AbstractManager.class.php');
require_once(dirname(__FILE__).'/../../class/handler/FileSharing_FolderHandler.class.php');
require_once(dirname(__FILE__).'/../../class/handler/FileSharing_FilesHandler.class.php');
require_once(dirname(__FILE__).'/../../class/handler/Profile_UsersHandler.class.php');

abstract class Toolbox_FileSharing_AbstractManager extends Toolbox_AbstractManager {
	protected $m_folderHandler;
	protected $m_filesHandler;
	protected $dir_name;
	protected $file_path;
	protected $maxfilesize;
	//protected $root_icon_path;
	//protected $icon_path;
	protected $rootedit;

	public function __construct() {
		parent::__construct();

		$this->dir_name = "filesharing";

		$this->m_folderHandler =& new FileSharing_FolderHandler($this->db);
		$this->m_filesHandler =& new FileSharing_FilesHandler($this->db);
		$this->m_userHandler =& new Profile_UsersHandler($this->db);

		$sql  = "";
		$sql .= "SELECT mid ";
		$sql .= " FROM ".$this->db->prefix('modules')." ";
		$sql .= " WHERE dirname='".$this->dir_name."' ";
		$rs = $this->db->query( $sql ) ;
		list( $filesharing_mid ) = $this->db->fetchRow( $rs ) ;

		$sql  = "";
		$sql .= "SELECT conf_name,conf_value ";
		$sql .= " FROM ".$this->db->prefix('config')." ";
		$sql .= " WHERE conf_modid=".$filesharing_mid." ";
		$sql .= " AND (conf_name = 'filesharing_filespath' ";
		$sql .= " OR conf_name = 'filesharing_fsize' ";
		$sql .= " OR conf_name = 'filesharing_rootedit') ";
		$rs = $this->db->query( $sql ) ;
		while( list($key,$val) = $this->db->fetchRow( $rs ) ) {
			$filesharing_configs[$key] = $val ;
		}
		$this->file_path = XOOPS_ROOT_PATH.$filesharing_configs['filesharing_filespath'];
		$this->maxfilesize = $filesharing_configs['filesharing_fsize'];
		//$this->root_icon_path = XOOPS_ROOT_PATH."/modules/".$this->dir_name."/icons";
		//$this->icon_path = dirname($this->file_path)."/icons";
		$this->rootedit = $filesharing_configs['filesharing_rootedit'];
	}

	protected function folderObject2responseVo($object) {
		$folderVO =& new ToolboxVO_FileSharing_FileFolder();

		$folderVO->id = $object->get('cid');
		$folderVO->name = $object->get('title');
		$folderVO->description = $object->get('description');
		$folderVO->parentId = $object->get('pid');
		$folderVO->creationDate = $object->get('create_date');
		$folderVO->updateDate = $object->get('edit_date');
		$folderVO->owner = $this->getUname($object->get('user_id'));
		
		$readVO =& new ToolboxVO_FileSharing_Permission();
		$readVO->type = $object->get('read_permission_type');
		$readVO->userId = $this->getUname($object->get('read_permission_user'));
		$folderVO->readPermission = $readVO;

		$editVO =& new ToolboxVO_FileSharing_Permission();
		$editVO->type = $object->get('edit_permission_type');
		$editVO->userId = $this->getUname($object->get('edit_permission_user'));
		$folderVO->editPermission = $editVO;
		
		return $folderVO;
	}

	protected function fileObject2responseVO($object) {
		$fileVO =& new ToolboxVO_FileSharing_File();

		$fileVO->id = $object->get('lid');
		$fileVO->name = $object->get('title');
		$fileVO->description = $object->get('description');
		$fileVO->folderId = $object->get('cid');
		$fileVO->creationDate = $object->get('create_date');
		$fileVO->updateDate = $object->get('edit_date');
		$fileVO->owner = $this->getUname($object->get('submitter'));
		$fileVO->creationDate = $object->get('date');
		$fileVO->updateDate = $object->get('date');
		
		$readVO =& new ToolboxVO_FileSharing_Permission();
		$readVO->type = $object->get('read_permission_type');
		$readVO->userId = $this->getUname($object->get('read_permission_user'));
		$fileVO->readPermission = $readVO;

		$editVO =& new ToolboxVO_FileSharing_Permission();
		$editVO->type = $object->get('edit_permission_type');
		$editVO->userId = $this->getUname($object->get('edit_permission_user'));
		$fileVO->editPermission = $editVO;

		
		$fileVO->path = $this->file_path."/".$object->get('lid');
		if(trim($object->get('ext')) != ""){
			$fileVO->path .= ".".trim($object->get('ext'));
		}
		
		return $fileVO;
	}
	
	protected function getUname($uid) {
		$obj =& $this->m_userHandler->get($uid);
		if ($obj != null) {
			return $obj->get('uname');
		}
	}
	
	protected function getUserIdByUname($uname) {
		$mc =& new CriteriaCompo();
		$mc->add(new Criteria('uname', $uname));
		$obj =& $this->m_userHandler->getObjects($mc);
		if ($obj != null && count($obj) > 0) {
			return $obj[0]->get('uid');
		}
		return null;
	}
	
	protected function check_folder_permission($cid){
		if($this->uid == 1){
			return true;
		}else{
			if($cid == 1){
				if($this->rootedit == 0){
					return false;
				}else{
					return true;
				}
			}else{
				$mc =& new CriteriaCompo();
				$mc->add(new Criteria('cid',intval($cid)));
				$obj =& $this->m_folderHandler->getObjects($mc);
				if ($obj != null && count($obj) > 0) {
					if($obj[0]->get('edit_permission_type') == 'public' 
					|| ($obj[0]->get('edit_permission_type') == 'user' 
					&& $obj[0]->get('edit_permission_user') == $this->uid)){
						return true;
					}else{
						return false;
					}
				}else{
					return false;
				}
			}
		}
	}
}
?>