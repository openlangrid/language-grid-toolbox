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
require_once(dirname(__FILE__).'/Toolbox_FileSharing_AbstractManager.class.php');

class Toolbox_FileSharing_FileCreateEditManager extends Toolbox_FileSharing_AbstractManager{
	public function __construct() {
		parent::__construct();
	}
	
	public function create($path,$name,$description,$folderId,$readPermission,$editPermission,$overwrite = false) {
		if(!is_dir($this->file_path)){
			return $this->getErrorResponsePayload("There are no upload directories.");
		}

		$cat_obj =& $this->m_folderHandler->get($folderId);
		if ($cat_obj == null) {
			return $this->getErrorResponsePayload("Folder id is invalid.");
		}
		if($this->check_folder_permission($folderId) == false){
			return $this->getErrorResponsePayload("No edit permission.");
		}
		
		if (trim($name) == "" || strlen($name) > 255) {
			return $this->getErrorResponsePayload("Name is invalid.");
		}
		
		if(!file_exists($path)){
			return $this->getErrorResponsePayload("File ".$path." is nothing.");
		}
		if(filesize($path) > ($this->maxfilesize * 1000000)){
			return $this->getErrorResponsePayload("Filesize over ".$this->maxfilesize." MB.");
		}
		
		$ext = substr( strrchr($path , '.' ) , 1 ) ;
		if(strlen($ext) > 10){$ext = substr($ext,0,10);}
		
		$is_overwrite = false;
		if($this->check_samename_file($folderId,$name) == false){
			if($overwrite == false){
				return $this->getErrorResponsePayload($name." is already exists.");
			}else{
				$is_overwrite = true;

				$mc = new CriteriaCompo();
				$mc->add(new Criteria('cid',intval($folderId)));
				$mc->add(new Criteria('title',$name));
				$Objects =& $this->m_filesHandler->getObjects($mc);
				
				$file_obj = $Objects[0];
			}
		}else{
			$file_obj =& $this->m_filesHandler->create(true);
		}
		
		$file_obj->set('cid', $folderId);
		$file_obj->set('title', $name);
		$file_obj->set('ext', $ext);
		$file_obj->set('submitter', $this->uid);
		$file_obj->set('date', time());
		$file_obj->set('description', $description);
		$file_obj->set('edit_date', time());
		if($is_overwrite){
			$file_obj->set('status', 2);
		}else{
			$file_obj->set('status', 1);
			$file_obj->set('create_date', time());
		}
		
		if($readPermission != null){
			$r_uid = $this->getUserIdByUname($readPermission->userId);
			if($r_uid == null){
				return $this->getErrorResponsePayload("read permission userId is invalid.");
			}
			$file_obj->set('read_permission_type', $readPermission->type);
			$file_obj->set('read_permission_user', $r_uid);
		}
		if($editPermission != null){
			$e_uid = $this->getUserIdByUname($editPermission->userId);
			if($e_uid == null){
				return $this->getErrorResponsePayload("edit permission userId is invalid.");
			}
			$file_obj->set('edit_permission_type', $editPermission->type);
			$file_obj->set('edit_permission_user', $e_uid);
		}
		if (!$this->m_filesHandler->insert($file_obj, true)) {
			throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
		}
		$node = $file_obj->get('lid');
		//copy target file
		$target_path = $this->file_path."/".$node;
		if(trim($ext) != ""){$target_path .= ".".trim($ext);}
		@unlink( $target_path ) ;
		if(!copy( $path , $target_path )){
			@$this->m_filesHandler->delete($file_obj, true);
			throw new Exception('file copy failed.');
		}

		//copy icon file
		/*
		@unlink( $this->icon_path."/".$node.".gif" ) ;
		$copy_success = false;
		if( file_exists( $this->root_icon_path."/".$ext.".gif") ) {
			$copy_success = copy( $this->root_icon_path."/".$ext.".gif" , $this->icon_path."/".$node.".gif" ) ;
		}
		if( !$copy_success ) {
			@copy( $this->root_icon_path."/default.gif" , $this->icon_path."/".$node.".gif" ) ;
		}
		*/
		return $this->getResponsePayload($this->fileObject2responseVO($file_obj));
	}


	function remove($id) {
		$file_obj =& $this->m_filesHandler->get($id);
		if($file_obj == null){
			return $this->getErrorResponsePayload("id is invalid.");
		}
		$pre_file = $file_obj->get('lid');
		if(trim($file_obj->get('ext')) != ""){
			$pre_file .= ".".trim($file_obj->get('ext'));
		}
		@unlink( $this->file_path."/".$pre_file ) ;

		if (!$this->m_filesHandler->delete($file_obj, true)) {
			throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
		}
		return $this->getResponsePayload(true);
	}
	
	function update($id,$description,$folderId,$readPermission,$editPermission){
		$file_obj =& $this->m_filesHandler->get($id);
		if($file_obj == null){
			return $this->getErrorResponsePayload("id is invalid.");
		}
		
		$isChange = false;
		if($file_obj->get('submitter') == $this->uid || $this->uid == 1){
			$isChange = true;
		}
		
		$pre_file = $file_obj->get('lid');
		if(trim($file_obj->get('ext')) != ""){
			$pre_file .= ".".trim($file_obj->get('ext'));
		}
		
		/*
		if(!is_dir($this->file_path)){
			return $this->getErrorResponsePayload("There are no upload directories.");
		}

		if (trim($name) == "" || strlen($name) > 255) {
			return $this->getErrorResponsePayload("Name is invalid.");
		}
		if(!file_exists($path)){
			return $this->getErrorResponsePayload("File ".$path." is nothing.");
		}
		
		$ext = substr( strrchr( $path , '.' ) , 1 ) ;
		if(strlen($ext) > 10){$ext = substr($ext,0,10);}
		*/
		if($folderId != null){
			$cat_obj =& $this->m_folderHandler->get($folderId);
			if ($cat_obj == null) {
				return $this->getErrorResponsePayload("Folder id is invalid.");
			}
			if(!$this->check_samename_file($folderId,$file_obj->get('title'),$id)){
				return $this->getErrorResponsePayload($file_obj->get('title')." is already exists.");
			}
		}else{
			$folderId = $file_obj->get('cid');
		}

		if($this->check_folder_permission($folderId) == false){
			return $this->getErrorResponsePayload("No edit permission.");
		}
		
		if($description != null){
			$file_obj->set('description', $description);
		}
		$file_obj->set('cid', $folderId);
		$file_obj->set('submitter', $this->uid);
		$file_obj->set('status', 2);
		$file_obj->set('date', time());
		$file_obj->set('edit_date', time());
		if($readPermission != null && $isChange){
			$r_uid = $this->getUserIdByUname($readPermission->userId);
			if($r_uid == null){
				return $this->getErrorResponsePayload("read permission userId is invalid.");
			}
			$file_obj->set('read_permission_type', $readPermission->type);
			$file_obj->set('read_permission_user', $r_uid);
		}
		if($editPermission != null && $isChange){
			$e_uid = $this->getUserIdByUname($editPermission->userId);
			if($e_uid == null){
				return $this->getErrorResponsePayload("edit permission userId is invalid.");
			}
			$file_obj->set('edit_permission_type', $editPermission->type);
			$file_obj->set('edit_permission_user', $e_uid);
		}
		
		if (!$this->m_filesHandler->insert($file_obj, true)) {
			throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
		}
		
		/*
		@unlink( $this->file_path."/".$pre_file ) ;
		$node = $file_obj->get('lid');
		//copy target file
		$target_path = $this->file_path."/".$node;
		if(trim($ext) != ""){$target_path .= ".".trim($ext);}
		if(!copy( $path , $target_path )){
			@$this->m_filesHandler->delete($file_obj, true);
			throw new Exception('file copy failed.');
		}
		*/
		
		//copy icon file
		/*
		@unlink( $this->icon_path."/".$node.".gif" ) ;
		$copy_success = false;
		if( file_exists( $this->root_icon_path."/".$ext.".gif") ) {
			$copy_success = copy( $this->root_icon_path."/".$ext.".gif" , $this->icon_path."/".$node.".gif" ) ;
		}
		if( !$copy_success ) {
			@copy( $this->root_icon_path."/default.gif" , $this->icon_path."/".$node.".gif" ) ;
		}
		*/
		return $this->getResponsePayload(true);
	}

	private function check_samename_file($cid,$title,$myid = null){
		$mc = new CriteriaCompo();
		$mc->add(new Criteria('cid',intval($cid)));
		$mc->add(new Criteria('title',$title));
		if(is_numeric($myid)){
			$mc->add(new Criteria('lid',intval($myid),'!='));
		}
		$Objects =& $this->m_filesHandler->getObjects($mc);
		if(is_array($Objects) && count($Objects) > 0){
			return false;
		}else{
			return true;
		}
	}
}
?>