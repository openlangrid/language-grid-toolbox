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

class Toolbox_FileSharing_FolderCreateEditManager extends Toolbox_FileSharing_AbstractManager{
	public function __construct() {
		parent::__construct();
	}

	public function create($name,$description,$parentId,$readPermission,$editPermission) {
		if (trim($name) == "" || strlen($name) > 255) {
			return $this->getErrorResponsePayload("Name is invalid.");
		}
		if(is_numeric($parentId)){
			$pcat_obj =& $this->m_folderHandler->get($parentId);
			if ($pcat_obj == null) {
				return $this->getErrorResponsePayload("Parent id is invalid.");
			}
		}
		if(!is_numeric($parentId)){$parentId = 1;}
		
		if($this->check_folder_permission($parentId) == false){
			return $this->getErrorResponsePayload("No edit permission.");
		}
		
		if(!$this->check_samename_folder($parentId,$name)){
			return $this->getErrorResponsePayload($name." is already exists.");
		}
		
		$cat_obj =& $this->m_folderHandler->create(true);
		$cat_obj->set('pid', $parentId);
		$cat_obj->set('title', $name);
		$cat_obj->set('description', $description);
		$cat_obj->set('create_date', time());
		$cat_obj->set('edit_date', time());
		$cat_obj->set('user_id', $this->uid);
		if($readPermission != null){
			$r_uid = $this->getUserIdByUname($readPermission->userId);
			if($r_uid == null){
				return $this->getErrorResponsePayload("read permission userId is invalid.");
			}
			$cat_obj->set('read_permission_type', $readPermission->type);
			$cat_obj->set('read_permission_user', $r_uid);
		}
		if($editPermission != null){
			$e_uid = $this->getUserIdByUname($editPermission->userId);
			if($e_uid == null){
				return $this->getErrorResponsePayload("edit permission userId is invalid.");
			}
			$cat_obj->set('edit_permission_type', $editPermission->type);
			$cat_obj->set('edit_permission_user', $e_uid);
		}
		if (!$this->m_folderHandler->insert($cat_obj, true)) {
			throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
		}
		return $this->getResponsePayload($this->folderObject2responseVo($cat_obj));
	}


	function remove($id) {
		if($id == 1){
			return $this->getErrorResponsePayload("root folder can not delete.");
		}
		
		$cat_obj =& $this->m_folderHandler->get($id);
		if($cat_obj == null){
			return $this->getErrorResponsePayload("id is invalid.");
		}

		if($this->uid != 1){
			if(!$this->check_folder_recurrently($id,$this->uid)){
				return $this->getErrorResponsePayload("No edit permission.");
			}
		}
		
		$this->delete_folder_recurrently($id);
		//if (!$this->m_folderHandler->delete($cat_obj, true)) {
		//	throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
		//}
		
		return $this->getResponsePayload(true);
	}

	function update($id,$name,$description,$parentId,$readPermission,$editPermission){
		if($this->check_folder_permission($id) == false){
			return $this->getErrorResponsePayload("No edit permission.");
		}
		
		if (trim($name) == "" || strlen($name) > 255) {
			return $this->getErrorResponsePayload("Name id is invalid.");
		}

		$cat_obj =& $this->m_folderHandler->get($id);
		if($cat_obj == null){
			return $this->getErrorResponsePayload("id is invalid.");
		}

		if(is_numeric($parentId)){
			$pcat_obj =& $this->m_folderHandler->get($parentId);
			if ($pcat_obj == null) {
				return $this->getErrorResponsePayload("Parent id is invalid.");
			}
		}
		if($parentId == null){
			$parentId = $cat_obj->get('pid');
		}
		
		if($this->check_folder_permission($parentId) == false){
			return $this->getErrorResponsePayload("No edit permission.");
		}

		if(!$this->check_samename_folder($parentId,$name,$id)){
			return $this->getErrorResponsePayload($name." is already exists.");
		}
		
		if($parentId != $cat_obj->get('pid')){	//move
			if($this->uid != 1){
				if(!$this->check_folder_recurrently($parentId,$this->uid)){
					return $this->getErrorResponsePayload("No edit permission.");
				}
			}
		}
		
		
		$cat_obj->set('title', $name);
		$cat_obj->set('edit_date', time());
		$cat_obj->set('user_id', $this->uid);

		if($parentId != null){
			$cat_obj->set('pid', $parentId);
		}
		if($description != null){
			$cat_obj->set('description', $description);
		}
		if($readPermission != null && $isChange){
			$r_uid = $this->getUserIdByUname($readPermission->userId);
			if($r_uid == null){
				return $this->getErrorResponsePayload("read permission userId is invalid.");
			}
			$cat_obj->set('read_permission_type', $readPermission->type);
			$cat_obj->set('read_permission_user', $r_uid);
		}
		if($editPermission != null && $isChange){
			$e_uid = $this->getUserIdByUname($editPermission->userId);
			if($e_uid == null){
				return $this->getErrorResponsePayload("edit permission userId is invalid.");
			}
			$cat_obj->set('edit_permission_type', $editPermission->type);
			$cat_obj->set('edit_permission_user', $e_uid);
		}
		if (!$this->m_folderHandler->insert($cat_obj, true)) {
			throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
		}

		return $this->getResponsePayload($this->folderObject2responseVo($cat_obj));
	}
	
	private function check_folder_recurrently($cid,$uid){
		$mc = new CriteriaCompo();
		$mc->add(new Criteria('cid',$cid));
		$FileObjects =& $this->m_filesHandler->getObjects($mc);
		if(is_array($FileObjects) && count($FileObjects) > 0){
			foreach($FileObjects as $Obj){
				if($Obj->get('edit_permission_type') == 'user' && $Obj->get('edit_permission_user') != $uid){
					return false;
				}
			}
		}
		
		$mc2 = new CriteriaCompo();
		$mc2->add(new Criteria('pid',$cid));
		$FolderObjects =& $this->m_folderHandler->getObjects($mc2);
		if(is_array($FolderObjects) && count($FolderObjects) > 0){
			foreach($FolderObjects as $Obj){
				if($Obj->get('edit_permission_type') == 'user' && $Obj->get('edit_permission_user') != $uid){
					return false;
				}else{
					if(!$this->check_folder_recurrently($Obj->get('cid'),$uid)){
						return false;
					}
				}
			}
		}
		
		return true;
	}

	private function delete_folder_recurrently($cid){
		$mc = new CriteriaCompo();
		$mc->add(new Criteria('cid',$cid));
		$FileObjects =& $this->m_filesHandler->getObjects($mc);
		if(is_array($FileObjects) && count($FileObjects) > 0){
			foreach($FileObjects as $Obj){
				$pre_file = $Obj->get('lid');
				if(trim($Obj->get('ext')) != ""){
					$pre_file .= ".".trim($Obj->get('ext'));
				}
				@unlink( $this->file_path."/".$pre_file ) ;

				if (!$this->m_filesHandler->delete($Obj, true)) {
					throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
				}
			}
		}
		
		$mc2 = new CriteriaCompo();
		$mc2->add(new Criteria('pid',$cid));
		$FolderObjects =& $this->m_folderHandler->getObjects($mc2);
		if(is_array($FolderObjects) && count($FolderObjects) > 0){
			foreach($FolderObjects as $Obj){
				$this->delete_folder_recurrently($Obj->get('cid'));
			}
		}
		
		$cat_obj =& $this->m_folderHandler->get($cid);
		if (!$this->m_folderHandler->delete($cat_obj, true)) {
			throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
		}
	}
	
	private function check_samename_folder($pid,$title,$myid = null){
		$mc = new CriteriaCompo();
		$mc->add(new Criteria('pid',intval($pid)));
		$mc->add(new Criteria('title',$title));
		if(is_numeric($myid)){
			$mc->add(new Criteria('cid',intval($myid),'!='));
		}
		$FolderObjects =& $this->m_folderHandler->getObjects($mc);
		if(is_array($FolderObjects) && count($FolderObjects) > 0){
			return false;
		}else{
			return true;
		}
	}
	
	
}
?>