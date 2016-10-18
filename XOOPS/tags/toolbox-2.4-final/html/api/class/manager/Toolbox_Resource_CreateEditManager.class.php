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

require_once(dirname(__FILE__).'/Toolbox_Resource_AbstractManager.class.php');

class Toolbox_Resource_CreateEditManager extends Toolbox_Resource_AbstractManager {

	protected $m_uid;

	public function __construct() {
		parent::__construct();
		$this->m_uid = $this->root->mContext->mXoopsUser->get('uid');
	}

	public function createResource($type, $name, $languages, $readPermission, $editPermission) {

		if (!$this->_validateDuplicateName($name)) {
			return $this->getErrorResponsePayload("name is invalid.");
		}

		$typeId = 0;
		switch (strtoupper($type)) {
			case 'DICTIONARY':
				$typeId = 0;
				break;
			case 'PARALLELTEXT';
				$typeId = 1;
				break;
			case 'QA';
				$typeId = 2;
				break;
			case 'GLOSSARY';
				$typeId = 3;
				break;
			case 'TRANSLATION_TEMPLATE';
				$typeId = 4;
				break;
			default:
				return $this->getErrorResponsePayload("No supported resource type.");
				break;
		}

		if (!is_a($readPermission, "ToolboxVO_Resource_Permission")) {
			return $this->getErrorResponsePayload("readPermission is invalid.");
		}
		if (!is_a($editPermission, "ToolboxVO_Resource_Permission")) {
			return $this->getErrorResponsePayload("editPermission is invalid.");
		}
		if ($languages == null || !is_array($languages) || count($languages) < 2) {
			return $this->getErrorResponsePayload("languages is invalid.");
		}

		$object = $this->create($typeId, $name, $languages, $readPermission, $editPermission);
		return $this->getResponsePayload($this->object2responseVo($object));
	}

	public function deleteResource($name) {
		if ($this->_validateDuplicateName($name)) {
			return $this->getErrorResponsePayload("name is invalid.");
		}
		if($this->delete($name)){
			return $this->getResponsePayload(true);
		}
	}

	public function addLanguage($name, $language) {

		$object = $this->_getByName($name);
		if ($object == null) {
			return $this->getErrorResponsePayload("name is invalid.");
		}
		if(is_array($object->languages)){
			foreach($object->languages as $mlang){
				if(strtoupper(trim($mlang)) == strtoupper(trim($language))){
					return $this->getErrorResponsePayload($language." is already exists.");
				}
			}
		}


		$num = $object->getContentsCount();

		$langRowObj =& $this->m_contentsHandler->create(true);
		$langRowObj->set('user_dictionary_id', $object->get('user_dictionary_id'));
		$langRowObj->set('language', $language);
		$langRowObj->set('row', '0');
		$langRowObj->set('contents', $language);
		$langRowObj->set('delete_flag', '0');
		if (!$this->m_contentsHandler->insert($langRowObj, true)) {
			throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
		}

		for ($row = 1; $row <= $num; $row++) {
			$obj =& $this->m_contentsHandler->create(true);
			$obj->set('user_dictionary_id', $object->get('user_dictionary_id'));
			$obj->set('language', $language);
			$obj->set('row', $row);
			$obj->set('contents', '');
			$obj->set('delete_flag', '0');
			if (!$this->m_contentsHandler->insert($obj, true)) {
				throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
			}
		}

		$object->set('update_date', time());
		$this->m_handler->insert($object, true);

		return $this->getResponsePayload(true);
		//return true;
	}

	public function removeLanguage($name, $language) {
		$object = $this->_getByName($name);
		if ($object == null) {
			return $this->getErrorResponsePayload("name is invalid.");
		}

		$rowContents =& $object->getContents();
		if(is_array($rowContents)){
			foreach ($rowContents as &$contents) {
				if ($contents->get('language') == $language) {
					$contents->set('delete_flag', '1');
					$this->m_contentsHandler->insert($contents, true);
				}
			}
		}

		$object->set('update_date', time());
		$this->m_handler->insert($object, true);

		if ($object->get('type_id') == 2) {

			// QA
			require_once dirname(__FILE__).'/Toolbox_QA_RecordCreateEditManager.class.php';
			$manager = new Toolbox_QA_RecordCreateEditManager();
			$manager->deleteLanguage($object->get('dictionary_name'), $language);
		} else if ($object->get('type_id') == 3) {

			// Glossary
			require_once dirname(__FILE__).'/Toolbox_Glossary_RecordCreateEditManager.class.php';
			$manager = new Toolbox_Glossary_RecordCreateEditManager();
			$manager->deleteLanguage($object->get('dictionary_name'), $language);
		} else if ($object->get('type_id') == 4) {

			// Translation Template
			require_once dirname(__FILE__).'/translation_template/Toolbox_TranslationTemplate_LanguageManager.class.php';
			$manager = new Toolbox_TranslationTemplate_LanguageManager();
			$manager->deleteLanguage($object->get('dictionary_name'), $language);
		}

		return $this->getResponsePayload(true);
	}

	public function setPermission($name, $readPermission, $editPermission) {
		$object = $this->_getByName($name);
		if ($object == null) {
			return $this->getErrorResponsePayload("name is invalid.");
		}

		$permissionObject =& $object->getPermission();
		$this->saveAndUpdatePermission($object, $permissionObject, $readPermission, $editPermission);

		return $this->getResponsePayload(true);
		//return true;
	}

	public function deployService($name) {
		$object = $this->deploy($name, '1');
		if ($object != null) {
			return $this->getResponsePayload($this->object2responseVo($object));
		} else {
			return $this->getErrorResponsePayload("invalid.");
		}
	}

	public function undeployService($name) {
		$object = $this->deploy($name, '0');
		if ($object != null) {
			return $this->getResponsePayload($this->object2responseVo($object));
		} else {
			return $this->getErrorResponsePayload("invalid.");
		}
	}

	private function create($typeId, $name, $languages, $readPermission, $editPermission) {

		$object =& $this->m_handler->create(true);

		$UserID = $this->getPermissionUserId($readPermission, $editPermission);

		$object->set('user_id', $UserID);
		$object->set('type_id', $typeId);
		$object->set('dictionary_name', $name);
		$object->set('create_date', time());
		$object->set('update_date', time());
		$object->set('delete_flag', '0');
		$object->set('deploy_flag', '0');

		if (!$this->m_handler->insert($object, true)) {
			throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
		}

		$this->saveAndUpdatePermission($object, null, $readPermission, $editPermission);

		foreach ($languages as $language) {
			$contentsObject =& $this->m_contentsHandler->create(true);

			$contentsObject->set('user_dictionary_id', $object->get('user_dictionary_id'));
			$contentsObject->set('row', '0');
			$contentsObject->set('language', $language);
			$contentsObject->set('contents', $language);
			$contentsObject->set('delete_flag', '0');

			if (!$this->m_contentsHandler->insert($contentsObject, true)) {
				throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
			}
		}

		return $this->m_handler->get($object->get('user_dictionary_id'));
	}

	private function delete($name) {
		$mCriteria =& new CriteriaCompo();
		$mCriteria->add(new Criteria('dictionary_name', $name));
		$mCriteria->add(new Criteria('delete_flag', '0'));
		$objects =& $this->m_handler->getObjects($mCriteria);
		if (count($objects) != 1) {
			return false;
		}

		$object =& $objects[0];

		if ($object->get('type_id') == 2) {

			// When QA delete
			require_once dirname(__FILE__).'/Toolbox_QA_RecordCreateEditManager.class.php';
			$manager = new Toolbox_QA_RecordCreateEditManager();
			$manager->deleteAllRecords($object->get('dictionary_name'));
		} else if ($object->get('type_id') == 3) {

			// When Glossary delete
			require_once dirname(__FILE__).'/Toolbox_Glossary_RecordCreateEditManager.class.php';
			$manager = new Toolbox_Glossary_RecordCreateEditManager();
			$manager->deleteAllRecords($object->get('dictionary_name'));
		} else if ($object->get('type_id') == 4) {

			// When TranslationTemplate delete
			require_once dirname(__FILE__).'/translation_template/Toolbox_TranslationTemplate_RecordCreateEditManager.class.php';
			$manager = new Toolbox_TranslationTemplate_RecordCreateEditManager();
			$manager->deleteRecordsByResourceId($object->get('user_dictionary_id'));
		}

		$object->set('delete_flag', '1');
		if (!$this->m_handler->insert($object, true)) {
			throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
		}

		$permission =& $object->getPermission();
		if ($permission != null) {
			$permission->set('delete_flag', '1');
			if (!$this->m_permissionHandler->insert($permission, true)) {
				throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
			}
		}

		$rowContents =& $object->getContents();
		if(is_array($rowContents)){
			foreach ($rowContents as &$contents) {
				$contents->set('delete_flag', '1');
				if (!$this->m_contentsHandler->insert($contents, true)) {
					throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
				}
			}
		}

// user_dictionaryテーブルを削除した後だとダメなので、上の移動。
//		if ($object->get('type_id') == 2) {
//
//			// When QA delete
//			require_once dirname(__FILE__).'/Toolbox_QA_RecordCreateEditManager.class.php';
//			$manager = new Toolbox_QA_RecordCreateEditManager();
//			$manager->deleteAllRecords($object->get('dictionary_name'));
//		} else if ($object->get('type_id') == 3) {
//
//			// When Glossary delete
//			require_once dirname(__FILE__).'/Toolbox_Glossary_RecordCreateEditManager.class.php';
//			$manager = new Toolbox_Glossary_RecordCreateEditManager();
//			$manager->deleteAllRecords($object->get('dictionary_name'));
//		} else if ($object->get('type_id') == 4) {
//
//			// When TranslationTemplate delete
//			require_once dirname(__FILE__).'/translation_template/Toolbox_TranslationTemplate_RecordCreateEditManager.class.php';
//			$manager = new Toolbox_TranslationTemplate_RecordCreateEditManager();
//			$manager->deleteRecordsByResourceId($object->get('user_dictionary_id'));
//		}

		return true;
	}

	private function saveAndUpdatePermission($object, $permissionObject, $readPermission, $editPermission) {

		$ParentID = $this->getPermissionUserId($readPermission, $editPermission);
		if($object->get('user_id') != $ParentID){
			$object->set('user_id', $ParentID);
			if (!$this->m_handler->insert($object, true)) {
				throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
			}
		}

		if (strtoupper($readPermission->type) == 'USER' && strtoupper($editPermission->type) == 'USER') {
			if ($permissionObject != null) {
				$this->m_permissionHandler->delete($permissionObject, true);
			}
			return null;
		}

		if ($permissionObject == null) {
			$permissionObject =& $this->m_permissionHandler->create(true);
		}
		$permissionObject->set('user_dictionary_id', $object->get('user_dictionary_id'));
		$permissionObject->set('permission_type', 'all');
		$permissionObject->set('permission_type_id', '0');
		$permissionObject->set('use', '1');
		$permissionObject->set('delete_flag', '0');

		if (strtoupper($readPermission->type) == 'PUBLIC') {
			$permissionObject->set('view', true);
		} else {
			$permissionObject->set('view', false);
		}

		if (strtoupper($editPermission->type) == 'PUBLIC') {
			$permissionObject->set('edit', true);
		} else {
			$permissionObject->set('edit', false);
		}
		if (!$this->m_permissionHandler->insert($permissionObject, true)) {
			throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
		}

		return $permissionObject;
	}

	private function deploy($name, $deploy) {
		$object = $this->_getByName($name);
		if ($object == null) {
			return $this->getErrorResponsePayload("name is invalid.");
		}
		$object->set('deploy_flag', $deploy);
		$object->set('update_date', time());

		if (!$this->m_handler->insert($object, true)) {
			throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
		}

		return $object;
	}

	//private function getPermissionUserId($readPermission, $editPermission){
	public function getPermissionUserId($readPermission, $editPermission){
		$parentUid = $this->m_uid;

		$readUser = null;
		$editUser = null;

		if(trim($readPermission->userId) != ""){
			$result = $this->getUserByUname($readPermission->userId);
			if($result != null){
				$readUser = $result->get('uid');
			}
		}
		if(trim($editPermission->userId) != ""){
			$result = $this->getUserByUname($editPermission->userId);
			if($result != null){
				$editUser = $result->get('uid');
			}
		}

		if (strtoupper($readPermission->type) == 'USER' || strtoupper($editPermission->type) == 'USER') {
			if (strtoupper($readPermission->type) == 'USER' && strtoupper($editPermission->type) == 'USER') {
				if($readUser != null){
					if($editUser != null){
						if($readUser != $editUser){
							//throw new Exception('Different userID is not accepted.');
						}else{
							$parentUid = $readUser;
						}
					}
				}else{
					if($editUser != null && $editUser != 1){
						$parentUid = $editUser;
					}
				}
			}else{
				if (strtoupper($readPermission->type) == 'USER' && $readUser != null){
					$parentUid = $readUser;
				}
				if (strtoupper($editPermission->type) == 'USER' && $editUser != null){
					$parentUid = $editUser;
				}
			}
		}
		return $parentUid;
	}

	public function updateTime($name) {
		$object = $this->_getByName($name);
		$object->set('update_date', time());
		$this->m_handler->insert($object, true);
	}
}
?>