<?php
//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Toolbox. This provides a series of
// multilingual collaboration tools.
// Copyright (C) 2010 CITY OF KYOTO All Rights Reserved.
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
require_once(dirname(__FILE__).'/Toolbox_BBS_AbstractManager.class.php');
require_once(dirname(__FILE__).'/../handler/CommunityResourceHandler.class.php');
require_once(dirname(__FILE__).'/../handler/CommunityResourceContentsHandler.class.php');
require_once(dirname(__FILE__).'/../handler/CommunityResourcePermissionHandler.class.php');
require_once(dirname(__FILE__).'/../handler/Profile_UsersHandler.class.php');

class Toolbox_Resource_AbstractManager extends Toolbox_AbstractManager {

	protected $m_handler;
	protected $m_contentsHandler;
	protected $m_permissionHandler;
	protected $m_userHandler;

	public function __construct() {
		parent::__construct();
		$this->m_handler = new CommunityResourceHandler($this->db);
		$this->m_contentsHandler = new CommunityResourceContentsHandler($this->db);
		$this->m_permissionHandler = new CommunityResourcePermissionHandler($this->db);
		$this->m_userHandler = new Profile_UsersHandler($this->db);
	}

	protected function object2responseVo($object) {
		$resource = new ToolboxVO_Resource_LanguageResource();

		$resource->name = $object->get('dictionary_name');
		switch($object->get('type_id')){
			case 0:
				$resource->type = 'DICTIONARY';
				$resource->entryCount = $object->getContentsCount();
				break;
			case 1:
				$resource->type = 'PARALLELTEXT';
				$resource->entryCount = $object->getContentsCount();
				break;
			case 2:
				$resource->type = 'QA';
				require_once dirname(__FILE__).'/Toolbox_QA_RecordReadManager.class.php';
				$manager = new Toolbox_QA_RecordReadManager();
				$resource->entryCount = $manager->getCount($resource->name);
				break;
			case 3:
				$resource->type = 'GLOSSARY';
				require_once dirname(__FILE__).'/Toolbox_Glossary_RecordReadManager.class.php';
				$manager = new Toolbox_Glossary_RecordReadManager();
				$resource->entryCount = $manager->getCount($resource->name);
				break;
			case 4:
				$resource->type = 'TRANSLATION_TEMPLATE';
				require_once dirname(__FILE__).'/translation_template/Toolbox_TranslationTemplate_RecordReadManager.class.php';
				$manager = new Toolbox_TranslationTemplate_RecordReadManager();
				$resource->entryCount = $manager->getCount($resource->name);
				break;
			default:
				$resource->type = '';
				$resource->entryCount = $object->getContentsCount();
				break;
		}

		$uname = '';
		$userHandler = xoops_gethandler('user');
		$user = $userHandler->get($object->get('user_id'));
		if ($user !== null && isset($user) && is_object($user)) {
			$uname = $user->getVar('uname');
			unset($user);
		} else {
			$uname = '';
		}
		unset($userHandler);
		$resource->creator = $uname;
		$resource->lastUpdate = $object->get('update_date');

		$permission =& $object->getPermission();
		$readPermission = new ToolboxVO_Resource_Permission();
//		$editPermission = new ToolboxVO_Resource_Permission();
		$userObj = $this->m_userHandler->get($object->get('user_id'));
		$readPermission->type = 'USER';
//		$readPermission->userId = $userObj->get('uname');
		$readPermission->userId = $uname;
		$readPermission->groupId = '';
		$editPermission->type = 'USER';
//		$editPermission->userId = $userObj->get('uname');
		$editPermission->userId = $uname;
		$editPermission->groupId = '';

		if ($permission != null && $permission->get('view') == '1') {
			$readPermission->type = 'PUBLIC';
			$readPermission->userId = '';
			$readPermission->groupId = '';
		}
		if ($permission != null && $permission->get('edit') == '1') {
			$editPermission->type = 'PUBLIC';
			$editPermission->userId = '';
			$editPermission->groupId = '';
		}
		$resource->readPermission = $readPermission;
		$resource->editPermission = $editPermission;
		$resource->languages = $object->getLanguages();
		$resource->isDeploy = $object->get('deploy_flag');

		unset($object);

		return $resource;
	}

	protected function _getByName($name) {
		$mCriteria = new CriteriaCompo();
		$mCriteria->add(new Criteria('dictionary_name', $name));
		$mCriteria->add(new Criteria('delete_flag', '0'));
		$objects =& $this->m_handler->getObjects($mCriteria);
		if (count($objects)) {
			return $objects[0];
		}
		return null;
	}

	protected function _validateDuplicateName($name) {
		return $this->_getByName($name) == null;
	}

	protected function getUserByUname($uname) {
		$mc = new CriteriaCompo();
		$mc->add(new Criteria('uname', $uname));
		$obj =& $this->m_userHandler->getObjects($mc);
		if ($obj != null && count($obj) > 0) {
			return $obj[0];
		}
		return null;
	}

}
?>